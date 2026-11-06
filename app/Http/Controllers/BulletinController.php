<?php

namespace App\Http\Controllers;

use App\Models\Bulletin;
use App\Models\BulletinItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BulletinController extends Controller
{
    /**
     * Display a listing of bulletins.
     */
    public function index(): View
    {
        $bulletins = Bulletin::with(['items'])
            ->latest('edition_date')
            ->paginate(10);

        return view('bulletins.index', compact('bulletins'));
    }

    /**
     * Show the form for creating a new bulletin.
     */
    public function create(): View
    {
        $today = now();
        $bulletin = new Bulletin([
            'title' => 'BOLETIM',
            'sub_title' => 'PORTAL NOVA ALIANÇA',
            'tagline' => 'INFORMAÇÃO • CIDADANIA • 24 HORAS NO AR',
            'edition_date' => $today->format('Y-m-d'),
            'edition_time' => $today->format('H\hi'),
            'badge_text' => '24 HORAS NO AR',
            'header_location' => 'GOIÂNIA & REGIÃO',
            'section_1_title' => 'CIDADE & METROPOLITANA',
            'section_1_color' => '#003566',
            'section_2_title' => 'GOIÁS & BRASIL',
            'section_2_color' => '#005f73',
            'footer_title' => 'PORTAL DE NOTÍCIAS NOVA ALIANÇA',
            'footer_slogan' => 'Aconteceu virou notícia. 24 horas no ar.',
            'footer_whatsapp' => '(62) 99999-9999',
            'footer_instagram' => '@portalnovaalianca',
            'footer_website' => 'www.portalnovaalianca.com.br',
            'footer_editorial_note' => 'Envie sugestões de pauta, fotos e vídeos para o nosso WhatsApp oficial da Redação.',
            'theme_style' => 'classic_blue',
        ]);

        return view('bulletins.create', compact('bulletin'));
    }

    /**
     * Store a newly created bulletin in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'sub_title' => ['nullable', 'string', 'max:150'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'edition_date' => ['required', 'date'],
            'edition_time' => ['nullable', 'string', 'max:50'],
            'badge_text' => ['nullable', 'string', 'max:100'],
            'header_location' => ['nullable', 'string', 'max:150'],
            'section_1_title' => ['required', 'string', 'max:150'],
            'section_1_color' => ['nullable', 'string', 'max:20'],
            'section_2_title' => ['required', 'string', 'max:150'],
            'section_2_color' => ['nullable', 'string', 'max:20'],
            'footer_title' => ['nullable', 'string', 'max:150'],
            'footer_slogan' => ['nullable', 'string', 'max:255'],
            'footer_whatsapp' => ['nullable', 'string', 'max:50'],
            'footer_instagram' => ['nullable', 'string', 'max:100'],
            'footer_website' => ['nullable', 'string', 'max:150'],
            'footer_editorial_note' => ['nullable', 'string', 'max:500'],
            'theme_style' => ['nullable', 'string'],
            'items' => ['nullable', 'array'],
        ]);

        $validated['user_id'] = Auth::id();

        DB::beginTransaction();
        try {
            $bulletin = Bulletin::create($validated);

            if (!empty($request->input('items'))) {
                foreach ($request->input('items') as $index => $itemData) {
                    if (empty($itemData['title']) && empty($itemData['description'])) {
                        continue;
                    }

                    BulletinItem::create([
                        'bulletin_id' => $bulletin->id,
                        'section' => intval($itemData['section'] ?? 1),
                        'position' => intval($itemData['position'] ?? ($index + 1)),
                        'title' => $itemData['title'] ?? 'Sem título',
                        'description' => $itemData['description'] ?? '',
                        'image_url' => $itemData['image_url'] ?? null,
                        'source' => $itemData['source'] ?? 'Redação',
                        'date_text' => $itemData['date_text'] ?? now()->format('d/m/Y'),
                        'source_url' => $itemData['source_url'] ?? null,
                        'badge' => $itemData['badge'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('bulletins.show', $bulletin)
                ->with('success', 'Folder criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao salvar folder: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified bulletin (The interactive folder view).
     */
    public function show(Bulletin $bulletin): View
    {
        $bulletin->load(['sectionOneItems', 'sectionTwoItems']);
        return view('bulletins.show', compact('bulletin'));
    }

    /**
     * Render only the flyer component for on-demand image generation / sharing.
     */
    public function renderOnly(Bulletin $bulletin): View
    {
        $bulletin->load(['sectionOneItems', 'sectionTwoItems']);
        return view('bulletins.components.folder-render', compact('bulletin'));
    }

    /**
     * Public view for sharing the folder link.
     */
    public function publicView(string $slug): View
    {
        $bulletin = Bulletin::where('slug', $slug)
            ->with(['sectionOneItems', 'sectionTwoItems'])
            ->firstOrFail();

        return view('bulletins.public', compact('bulletin'));
    }

    /**
     * Show the form for editing the specified bulletin.
     */
    public function edit(Bulletin $bulletin): View
    {
        $bulletin->load(['sectionOneItems', 'sectionTwoItems', 'items']);
        return view('bulletins.edit', compact('bulletin'));
    }

    /**
     * Update the specified bulletin in storage.
     */
    public function update(Request $request, Bulletin $bulletin): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'sub_title' => ['nullable', 'string', 'max:150'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'edition_date' => ['required', 'date'],
            'edition_time' => ['nullable', 'string', 'max:50'],
            'badge_text' => ['nullable', 'string', 'max:100'],
            'header_location' => ['nullable', 'string', 'max:150'],
            'section_1_title' => ['required', 'string', 'max:150'],
            'section_1_color' => ['nullable', 'string', 'max:20'],
            'section_2_title' => ['required', 'string', 'max:150'],
            'section_2_color' => ['nullable', 'string', 'max:20'],
            'footer_title' => ['nullable', 'string', 'max:150'],
            'footer_slogan' => ['nullable', 'string', 'max:255'],
            'footer_whatsapp' => ['nullable', 'string', 'max:50'],
            'footer_instagram' => ['nullable', 'string', 'max:100'],
            'footer_website' => ['nullable', 'string', 'max:150'],
            'footer_editorial_note' => ['nullable', 'string', 'max:500'],
            'theme_style' => ['nullable', 'string'],
            'items' => ['nullable', 'array'],
        ]);

        DB::beginTransaction();
        try {
            $bulletin->update($validated);

            // Re-sync items
            $bulletin->items()->delete();

            if (!empty($request->input('items'))) {
                foreach ($request->input('items') as $index => $itemData) {
                    if (empty($itemData['title']) && empty($itemData['description'])) {
                        continue;
                    }

                    BulletinItem::create([
                        'bulletin_id' => $bulletin->id,
                        'section' => intval($itemData['section'] ?? 1),
                        'position' => intval($itemData['position'] ?? ($index + 1)),
                        'title' => $itemData['title'] ?? 'Sem título',
                        'description' => $itemData['description'] ?? '',
                        'image_url' => $itemData['image_url'] ?? null,
                        'source' => $itemData['source'] ?? 'Redação',
                        'date_text' => $itemData['date_text'] ?? now()->format('d/m/Y'),
                        'source_url' => $itemData['source_url'] ?? null,
                        'badge' => $itemData['badge'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('bulletins.show', $bulletin)
                ->with('success', 'Folder atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao atualizar folder: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified bulletin from storage.
     */
    public function destroy(Bulletin $bulletin): RedirectResponse
    {
        $bulletin->delete();
        return redirect()->route('bulletins.index')
            ->with('success', 'Folder excluído com sucesso!');
    }

    /**
     * Duplicate a bulletin to quickly start a new edition.
     */
    public function duplicate(Bulletin $bulletin): RedirectResponse
    {
        $newBulletin = $bulletin->replicate(['slug', 'created_at', 'updated_at']);
        $newBulletin->edition_date = now();
        $newBulletin->edition_time = now()->format('H\hi');
        $newBulletin->slug = Str::slug($newBulletin->title . '-' . now()->format('Y-m-d')) . '-' . Str::random(5);
        $newBulletin->save();

        foreach ($bulletin->items as $item) {
            $newItem = $item->replicate(['created_at', 'updated_at']);
            $newItem->bulletin_id = $newBulletin->id;
            $newItem->date_text = now()->format('d/m/Y');
            $newItem->save();
        }

        return redirect()->route('bulletins.edit', $newBulletin)
            ->with('success', 'Folder duplicado! Você pode alterar as notícias para a nova edição.');
    }

    /**
     * Create a demo bulletin with realistic news for testing.
     */
    public function seedDemo(): RedirectResponse
    {
        $bulletin = Bulletin::create([
            'user_id' => Auth::id(),
            'title' => 'BOLETIM',
            'sub_title' => 'PORTAL NOVA ALIANÇA',
            'tagline' => 'INFORMAÇÃO • CIDADANIA • ACONTECEU VIROU NOTÍCIA',
            'edition_date' => now()->format('Y-m-d'),
            'edition_time' => now()->format('H\hi'),
            'badge_text' => '24 HORAS NO AR',
            'header_location' => 'GOIÂNIA E REGIÃO METROPOLITANA',
            'section_1_title' => 'CIDADE & METROPOLITANA',
            'section_1_color' => '#0a369d',
            'section_2_title' => 'GOIÁS & BRASIL',
            'section_2_color' => '#0077b6',
            'footer_title' => 'PORTAL DE NOTÍCIAS NOVA ALIANÇA',
            'footer_slogan' => 'Aconteceu virou notícia. 24 horas no ar.',
            'footer_whatsapp' => '(62) 99999-9999',
            'footer_instagram' => '@portalnovaalianca',
            'footer_website' => 'www.portalnovaalianca.com.br',
            'footer_editorial_note' => 'O portal com as notícias mais atualizadas em tempo real para você se manter informado.',
            'theme_style' => 'classic_blue',
            'status' => 'published',
        ]);

        $sampleNews = [
            // Section 1 (4 items)
            [
                'section' => 1,
                'position' => 1,
                'title' => 'Exposição de Orquídeas e Flores chega ao último dia com grande público',
                'description' => 'A 24ª Exposição de Orquídeas e Rosas do Deserto acontece hoje na sede central. Visitação das 8h às 17h, com entrada gratuita e doação de alimentos para entidades sociais.',
                'image_url' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?w=600&auto=format&fit=crop&q=80',
                'source' => 'Fonte: Secretaria Municipal de Cultura',
                'date_text' => now()->format('d/m/Y'),
            ],
            [
                'section' => 1,
                'position' => 2,
                'title' => 'Recuperação asfáltica e recapeamento avançam em diversos bairros',
                'description' => 'Equipes de obras mantém o cronograma de serviços de recuperação de vias e avenidas neste fim de semana, com frentes ativas em diferentes setores e sinalização reforçada.',
                'image_url' => 'https://images.unsplash.com/photo-1584467735871-8e85353a8413?w=600&auto=format&fit=crop&q=80',
                'source' => 'Fonte: Diretoria de Infraestrutura',
                'date_text' => now()->format('d/m/Y'),
            ],
            [
                'section' => 1,
                'position' => 3,
                'title' => 'Feira de adoção de animais promove acolhimento e vacinação gratuita',
                'description' => 'A campanha realiza feira de adoção responsável de cães e gatos, com vacinação e orientações veterinárias para as famílias durante toda a programação.',
                'image_url' => 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?w=600&auto=format&fit=crop&q=80',
                'source' => 'Fonte: Centro de Zoonoses / Proteção Animal',
                'date_text' => now()->format('d/m/Y'),
            ],
            [
                'section' => 1,
                'position' => 4,
                'title' => 'Novo incentivo para microempreendedores é aprovado na Câmara',
                'description' => 'A medida prevê redução de taxas e facilidade para regularização de pequenas empresas e trabalhadores autônomos, fortalecendo a economia local.',
                'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&auto=format&fit=crop&q=80',
                'source' => 'Fonte: Câmara Municipal de Notícias',
                'date_text' => now()->subDay()->format('d/m/Y'),
            ],
            // Section 2 (4 items)
            [
                'section' => 2,
                'position' => 1,
                'title' => 'Campanha Estadual de Doação de Órgãos mobiliza hospitais e famílias',
                'description' => 'A data estadual reforça a importância do diálogo e da conscientização sobre a doação de órgãos e tecidos, que salvam centenas de vidas todos os anos.',
                'image_url' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=600&auto=format&fit=crop&q=80',
                'source' => 'Fonte: Secretaria de Saúde do Estado',
                'date_text' => now()->format('d/m/Y'),
            ],
            [
                'section' => 2,
                'position' => 2,
                'title' => 'Preparativos eleitorais: Justiça Eleitoral orienta eleitores',
                'description' => 'O Tribunal Eleitoral orienta os cidadãos a conferirem com antecedência o local de votação, portando documento oficial com foto ou o aplicativo oficial no celular.',
                'image_url' => 'https://images.unsplash.com/photo-1540910419892-4a36d2c3266c?w=600&auto=format&fit=crop&q=80',
                'source' => 'Fonte: Tribunal Superior Eleitoral',
                'date_text' => now()->format('d/m/Y'),
            ],
            [
                'section' => 2,
                'position' => 3,
                'title' => 'Safra e plantio da soja batem novas expectativas de produtividade',
                'description' => 'O calendário fitossanitário estabelece o início oficial da semeadura com tecnologia de ponta no campo e condições climáticas favoráveis para os produtores.',
                'image_url' => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=600&auto=format&fit=crop&q=80',
                'source' => 'Fonte: Federação de Agricultura e Pecuária',
                'date_text' => now()->subDays(2)->format('d/m/Y'),
            ],
            [
                'section' => 2,
                'position' => 4,
                'title' => 'Programa Nota Fiscal distribui premiações para consumidores cadastrados',
                'description' => 'O sorteio mensal contemplou milhares de participantes em todo o estado. Os valores já podem ser resgatados ou transferidos diretamente pelo portal de serviços.',
                'image_url' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=600&auto=format&fit=crop&q=80',
                'source' => 'Fonte: Secretaria de Economia do Estado',
                'date_text' => now()->subDays(3)->format('d/m/Y'),
            ],
        ];

        foreach ($sampleNews as $item) {
            BulletinItem::create(array_merge($item, ['bulletin_id' => $bulletin->id]));
        }

        return redirect()->route('bulletins.show', $bulletin)
            ->with('success', 'Folder demonstrativo criado com sucesso!');
    }
}
