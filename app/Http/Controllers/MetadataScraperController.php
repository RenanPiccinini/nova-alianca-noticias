<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class MetadataScraperController extends Controller
{
    /**
     * Fetch OpenGraph and HTML metadata from a given news URL.
     */
    public function scrape(Request $request): JsonResponse
    {
        $request->validate([
            'url' => ['required', 'url'],
        ]);

        $url = trim($request->input('url'));

        try {
            $client = new Client([
                'timeout' => 12,
                'verify' => false,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
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

            // Clean title: often portals have " - G1", " | Notícias UOL", etc. at the end
            $title = $this->cleanTitle($title);

            // 2. Description
            $description = $this->extractMeta($crawler, [
                'meta[property="og:description"]',
                'meta[name="twitter:description"]',
                'meta[name="description"]',
            ]);

            if (empty($description)) {
                // Try first paragraph with more than 30 chars
                $crawler->filter('article p, .entry-content p, .content-text p, p')->each(function (Crawler $node) use (&$description) {
                    $text = trim($node->text());
                    if (empty($description) && strlen($text) > 40 && !str_starts_with($text, '©')) {
                        $description = $text;
                    }
                });
            }

            // 3. Image URL
            $remoteImageUrl = $this->extractMeta($crawler, [
                'meta[property="og:image"]',
                'meta[property="og:image:secure_url"]',
                'meta[name="twitter:image"]',
                'meta[name="twitter:image:src"]',
            ]);

            $localImageUrl = null;
            if (!empty($remoteImageUrl)) {
                // If relative url, make absolute
                if (!preg_match('~^(?:f|ht)tps?://~i', $remoteImageUrl)) {
                    $parsedUrl = parse_url($url);
                    $base = ($parsedUrl['scheme'] ?? 'https') . '://' . ($parsedUrl['host'] ?? '');
                    $remoteImageUrl = rtrim($base, '/') . '/' . ltrim($remoteImageUrl, '/');
                }

                // Download and save locally for CORS-free rendering
                $localImageUrl = $this->downloadAndStoreImage($remoteImageUrl, $client);
            }

            // 4. Source Name
            $siteName = $this->extractMeta($crawler, [
                'meta[property="og:site_name"]',
                'meta[name="application-name"]',
            ]);

            if (empty($siteName)) {
                $host = parse_url($url, PHP_URL_HOST) ?? '';
                $host = preg_replace('/^www\./', '', $host);
                $parts = explode('.', $host);
                $siteName = ucfirst($parts[0] ?? 'Notícia');
            }

            // 5. Date
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

            return response()->json([
                'success' => true,
                'data' => [
                    'url' => $url,
                    'title' => Str::limit($title, 120, '...'),
                    'description' => Str::limit($description, 280, '...'),
                    'image_url' => $localImageUrl ?: $remoteImageUrl,
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
     * Upload an image directly from user computer.
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:10240'], // 10MB max
        ]);

        $file = $request->file('image');
        $fileName = 'news_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('news', $fileName, 'public');

        return response()->json([
            'success' => true,
            'image_url' => '/storage/' . $path,
        ]);
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
                // Ignore and check next selector
            }
        }
        return null;
    }

    private function cleanTitle(?string $title): string
    {
        if (empty($title)) {
            return '';
        }
        // Remove trailing portal branding, e.g., "Título da notícia | G1", "Título - Folha", etc.
        $title = preg_replace('/\s*[-|–—]\s*(G1|UOL|CNN|Folha|Metrópoles|O Globo|Band|R7|Terra|Correio).*$/i', '', $title);
        return trim($title);
    }

    private function downloadAndStoreImage(string $imageUrl, Client $client): ?string
    {
        try {
            $imgRes = $client->get($imageUrl, [
                'timeout' => 8,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'Accept' => 'image/*',
                ],
            ]);

            $content = (string) $imgRes->getBody();
            if (strlen($content) < 500) {
                return null;
            }

            // Determine extension
            $contentType = $imgRes->getHeaderLine('Content-Type');
            $ext = 'jpg';
            if (str_contains($contentType, 'png')) {
                $ext = 'png';
            } elseif (str_contains($contentType, 'webp')) {
                $ext = 'webp';
            }

            $fileName = 'news_' . uniqid() . '.' . $ext;
            Storage::disk('public')->put('news/' . $fileName, $content);

            return '/storage/news/' . $fileName;
        } catch (\Exception $e) {
            Log::info('Image download failed: ' . $e->getMessage());
            return null;
        }
    }
}
