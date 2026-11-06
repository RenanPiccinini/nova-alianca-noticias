<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class MetadataScraperController extends Controller
{
    /**
     * Fetch OpenGraph, Twitter, JSON-LD and HTML metadata from a given news URL.
     */
    public function scrape(Request $request): JsonResponse
    {
        $request->validate([
            'url' => ['required', 'url'],
        ]);

        $url = trim($request->input('url'));

        try {
            $client = new Client([
                'timeout' => 15,
                'verify' => false,
                'allow_redirects' => ['max' => 5],
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                    'Accept-Language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
                    'Referer' => $url,
                ],
            ]);

            $response = $client->get($url);
            $html = (string) $response->getBody();

            $crawler = new Crawler($html, $url);

            // 1. Title
            $title = $this->extractMeta($crawler, [
                'meta[property="og:title"]',
                'meta[name="twitter:title"]',
                'title',
                'h1',
            ]);
            $title = $this->cleanTitle($title);

            // 2. Description / Summary
            $description = $this->extractMeta($crawler, [
                'meta[property="og:description"]',
                'meta[name="twitter:description"]',
                'meta[name="description"]',
            ]);

            if (empty($description)) {
                // Try to find the lead paragraph
                $crawler->filter('article p, .entry-content p, .content-text p, .materia-conteudo p, p')->each(function (Crawler $node) use (&$description) {
                    $text = trim($node->text());
                    if (empty($description) && strlen($text) > 40 && !str_starts_with($text, '©') && !str_starts_with($text, 'Compartilhe')) {
                        $description = $text;
                    }
                });
            }

            // 3. Featured Image (Multi-strategy)
            $remoteImageUrl = $this->extractBestImage($crawler, $html, $url);

            // 4. Download and permanently store image in public/uploads/news/
            $localImageUrl = null;
            if (!empty($remoteImageUrl)) {
                $remoteImageUrl = $this->normalizeUrl($remoteImageUrl, $url);
                $localImageUrl = $this->downloadAndSaveImagePermanently($remoteImageUrl, $url, $client);
            }

            // 5. Source Name
            $siteName = $this->extractMeta($crawler, [
                'meta[property="og:site_name"]',
                'meta[name="application-name"]',
            ]);

            if (empty($siteName)) {
                $host = parse_url($url, PHP_URL_HOST) ?? '';
                $host = preg_replace('/^www\./', '', $host);
                $parts = explode('.', $host);
                $siteName = ucfirst($parts[0] ?? 'Redação');
            }

            // 6. Publication Date
            $publishedTime = $this->extractMeta($crawler, [
                'meta[property="article:published_time"]',
                'meta[name="article:published_time"]',
                'meta[name="publish-date"]',
                'meta[name="date"]',
                'time[datetime]',
            ]);

            $dateFormatted = now()->format('d/m/Y');
            if (!empty($publishedTime)) {
                try {
                    $dateFormatted = \Carbon\Carbon::parse($publishedTime)->format('d/m/Y');
                } catch (\Exception $e) {
                    $dateFormatted = now()->format('d/m/Y');
                }
            }

            // Final Image Choice: Prefer local permanent path; if download failed, fallback to remote
            $finalImageUrl = $localImageUrl ?: $remoteImageUrl;

            return response()->json([
                'success' => true,
                'data' => [
                    'url' => $url,
                    'title' => Str::limit($title, 130, '...'),
                    'description' => Str::limit($description, 280, '...'),
                    'image_url' => $finalImageUrl,
                    'remote_image_url' => $remoteImageUrl,
                    'source' => 'Fonte: ' . $siteName,
                    'date_text' => $dateFormatted,
                ],
            ]);
        } catch (\Exception $e) {
            Log::warning('MetadataScraper error: ' . $e->getMessage(), ['url' => $url]);
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível extrair automaticamente todos os dados deste link (' . $e->getMessage() . '). Você pode preencher manualmente.',
            ], 422);
        }
    }

    /**
     * Upload an image directly from the user's computer.
     * Saved permanently into public/uploads/news/ (no symlink required).
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:15360'], // 15MB max
        ]);

        $file = $request->file('image');
        $uploadDir = public_path('uploads/news');

        if (!File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true, true);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $fileName = 'news_' . uniqid() . '_' . time() . '.' . $extension;

        $file->move($uploadDir, $fileName);

        return response()->json([
            'success' => true,
            'image_url' => '/uploads/news/' . $fileName,
        ]);
    }

    /**
     * Fallback proxy to stream any remote image bypassing hotlink/CORS blocks.
     */
    public function proxyImage(Request $request)
    {
        $url = $request->query('url');
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            abort(404);
        }

        try {
            $client = new Client([
                'timeout' => 10,
                'verify' => false,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko)',
                    'Accept' => 'image/*,*/*',
                    'Referer' => $url,
                ]
            ]);

            $res = $client->get($url);
            $contentType = $res->getHeaderLine('Content-Type') ?: 'image/jpeg';
            $body = $res->getBody();

            return response($body, 200, [
                'Content-Type' => $contentType,
                'Cache-Control' => 'public, max-age=86400',
            ]);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Comprehensive multi-strategy image extraction from HTML.
     */
    private function extractBestImage(Crawler $crawler, string $html, string $pageUrl): ?string
    {
        // Strategy 1: Standard Meta tags
        $metaSelectors = [
            'meta[property="og:image"]',
            'meta[property="og:image:url"]',
            'meta[property="og:image:secure_url"]',
            'meta[name="twitter:image"]',
            'meta[name="twitter:image:src"]',
            'link[rel="image_src"]',
            'meta[itemprop="image"]',
        ];

        foreach ($metaSelectors as $selector) {
            $val = $this->extractMeta($crawler, [$selector]);
            if (!empty($val) && !$this->isInvalidImage($val)) {
                return $val;
            }
        }

        // Strategy 2: JSON-LD Schema (Article, NewsArticle, BlogPosting)
        try {
            $crawler->filter('script[type="application/ld+json"]')->each(function (Crawler $node) use (&$jsonImg) {
                if (!empty($jsonImg)) return;
                $text = trim($node->text());
                $data = json_decode($text, true);
                if (is_array($data)) {
                    $jsonImg = $this->findImageInJsonLd($data);
                }
            });
            if (!empty($jsonImg) && !$this->isInvalidImage($jsonImg)) {
                return $jsonImg;
            }
        } catch (\Exception $e) {
            // Ignore json error
        }

        // Strategy 3: Article / WordPress / Featured Content Images
        $imgSelectors = [
            'article img',
            '.wp-post-image',
            '.featured-image img',
            '.post-thumbnail img',
            '.materia-conteudo img',
            '.entry-content img',
            'figure img',
            'img[src*="uploads"]',
            'img',
        ];

        foreach ($imgSelectors as $sel) {
            try {
                $nodes = $crawler->filter($sel);
                foreach ($nodes as $domNode) {
                    // Check standard src and lazy-load attributes
                    $src = $domNode->getAttribute('src')
                        ?: $domNode->getAttribute('data-src')
                        ?: $domNode->getAttribute('data-original')
                        ?: $domNode->getAttribute('data-lazy-src');

                    // Check srcset
                    if (empty($src) && $domNode->hasAttribute('srcset')) {
                        $srcset = $domNode->getAttribute('srcset');
                        $parts = explode(',', $srcset);
                        if (!empty($parts)) {
                            $first = trim(explode(' ', trim(end($parts)))[0]);
                            if (!empty($first)) $src = $first;
                        }
                    }

                    if (!empty($src) && !$this->isInvalidImage($src)) {
                        return $src;
                    }
                }
            } catch (\Exception $e) {
                // Continue
            }
        }

        return null;
    }

    private function findImageInJsonLd(array $data): ?string
    {
        if (isset($data['image'])) {
            if (is_string($data['image'])) return $data['image'];
            if (is_array($data['image'])) {
                if (isset($data['image']['url'])) return $data['image']['url'];
                if (isset($data['image'][0])) {
                    return is_string($data['image'][0]) ? $data['image'][0] : ($data['image'][0]['url'] ?? null);
                }
            }
        }

        if (isset($data['@graph']) && is_array($data['@graph'])) {
            foreach ($data['@graph'] as $item) {
                $found = $this->findImageInJsonLd($item);
                if (!empty($found)) return $found;
            }
        }

        return null;
    }

    private function isInvalidImage(?string $url): bool
    {
        if (empty($url)) return true;
        // Ignore 1x1 tracking pixels, data gifs, or small icons
        if (str_starts_with($url, 'data:image/gif')) return true;
        if (str_contains($url, '1x1') || str_contains($url, 'blank.gif') || str_contains($url, 'spacer.gif')) return true;
        return false;
    }

    private function normalizeUrl(string $imgUrl, string $baseUrl): string
    {
        $imgUrl = trim($imgUrl);
        // Protocol relative //domain.com/image.jpg
        if (str_starts_with($imgUrl, '//')) {
            $scheme = parse_url($baseUrl, PHP_URL_SCHEME) ?: 'https';
            return $scheme . ':' . $imgUrl;
        }

        // Relative URL /path/to/image.jpg
        if (!preg_match('~^(?:f|ht)tps?://~i', $imgUrl)) {
            $parsed = parse_url($baseUrl);
            $base = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? '');
            if (isset($parsed['port'])) {
                $base .= ':' . $parsed['port'];
            }

            if (str_starts_with($imgUrl, '/')) {
                return $base . $imgUrl;
            }

            $path = dirname($parsed['path'] ?? '/');
            return rtrim($base . $path, '/') . '/' . ltrim($imgUrl, '/');
        }

        return $imgUrl;
    }

    /**
     * Download image and save PERMANENTLY into public/uploads/news/.
     * This avoids any symlink or storage permission issues on any server (Hostinger, cPanel, etc.).
     */
    private function downloadAndSaveImagePermanently(string $imageUrl, string $refererUrl, Client $client): ?string
    {
        try {
            $uploadDir = public_path('uploads/news');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }

            $imgRes = $client->get($imageUrl, [
                'timeout' => 15,
                'verify' => false,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36',
                    'Accept' => 'image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
                    'Referer' => $refererUrl,
                ],
            ]);

            $content = (string) $imgRes->getBody();
            if (strlen($content) < 300) {
                return null;
            }

            // Determine file extension
            $contentType = $imgRes->getHeaderLine('Content-Type');
            $ext = 'jpg';
            if (str_contains($contentType, 'png')) {
                $ext = 'png';
            } elseif (str_contains($contentType, 'webp')) {
                $ext = 'webp';
            } elseif (str_contains($contentType, 'gif')) {
                $ext = 'gif';
            } else {
                $urlPath = parse_url($imageUrl, PHP_URL_PATH) ?? '';
                $pathExt = strtolower(pathinfo($urlPath, PATHINFO_EXTENSION));
                if (in_array($pathExt, ['jpg', 'jpeg', 'png', 'webp', 'avif', 'gif'])) {
                    $ext = $pathExt;
                }
            }

            $fileName = 'news_' . uniqid() . '_' . time() . '.' . $ext;
            $destPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

            file_put_contents($destPath, $content);

            // Return permanent direct URL
            return '/uploads/news/' . $fileName;
        } catch (\Exception $e) {
            Log::warning('Image download failed: ' . $e->getMessage(), ['imageUrl' => $imageUrl]);
            return null;
        }
    }

    private function extractMeta(Crawler $crawler, array $selectors): ?string
    {
        foreach ($selectors as $selector) {
            try {
                $node = $crawler->filter($selector);
                if ($node->count() > 0) {
                    if (str_contains($selector, 'meta')) {
                        $content = $node->attr('content');
                        if (!empty($content)) {
                            return trim($content);
                        }
                    } elseif ($selector === 'time[datetime]') {
                        $content = $node->attr('datetime') ?: $node->text();
                        if (!empty($content)) {
                            return trim($content);
                        }
                    } else {
                        $text = $node->text();
                        if (!empty($text)) {
                            return trim($text);
                        }
                    }
                }
            } catch (\Exception $e) {
                // Ignore and try next
            }
        }
        return null;
    }

    private function cleanTitle(?string $title): string
    {
        if (empty($title)) return '';
        $title = preg_replace('/\s*[-|–—]\s*(G1|UOL|CNN|Folha|Metrópoles|O Globo|Band|R7|Terra|Correio|Portal).*$/i', '', $title);
        return trim($title);
    }
}
