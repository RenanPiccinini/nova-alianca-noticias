<?php

namespace Database\Seeders;

use App\Models\Bulletin;
use App\Models\BulletinItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@novaalianca.com'],
            [
                'name' => 'Redação Nova Aliança',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Check if a demo bulletin already exists
        if (Bulletin::count() === 0) {
            $bulletin = Bulletin::create([
                'user_id' => $user->id,
                'title' => 'BOLETIM',
                'sub_title' => 'PORTAL NOVA ALIANÇA',
                'tagline' => 'INFORMAÇÃO • CIDADANIA • ACONTECEU VIROU NOTÍCIA',
                'edition_date' => now()->format('Y-m-d'),
                'edition_time' => '07h15',
                'badge_text' => '24 HORAS NO AR',
                'header_location' => 'GOIÂNIA & REGIÃO',
                'section_1_title' => 'CIDADE & METROPOLITANA',
                'section_1_color' => '#0a369d',
                'section_2_title' => 'GOIÁS & BRASIL',
                'section_2_color' => '#0077b6',
                'footer_title' => 'PORTAL DE NOTÍCIAS NOVA ALIANÇA',
                'footer_slogan' => 'Aconteceu virou notícia. 24 horas no ar.',
                'footer_whatsapp' => '(62) 99999-9999',
                'footer_instagram' => '@portalnovaalianca',
                'footer_website' => 'www.portalnovaalianca.com.br',
                'footer_editorial_note' => 'O portal com notícias em tempo real de Goiás e do Brasil.',
                'theme_style' => 'classic_blue',
                'status' => 'published',
                'slug' => 'boletim-portal-nova-alianca-' . now()->format('Y-m-d') . '-' . Str::random(4),
            ]);

            $sampleNews = [
                // Section 1: Cidade & Região (4 items)
                [
                    'section' => 1,
                    'position' => 1,
                    'title' => 'Exposição de Orquídeas e Flores chega ao último dia com grande visitação',
                    'description' => 'A 24ª Exposição de Orquídeas e Rosas do Deserto acontece hoje com visitação gratuita e arrecadação de alimentos para entidades assistenciais.',
                    'image_url' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?w=600&auto=format&fit=crop&q=80',
                    'source' => 'Fonte: Secretaria Municipal de Cultura',
                    'date_text' => now()->format('d/m/Y'),
                ],
                [
                    'section' => 1,
                    'position' => 2,
                    'title' => 'Recuperação asfáltica e recapeamento avançam em diversos setores',
                    'description' => 'Equipes de obras mantém o cronograma de serviços de recuperação de pistas e avenidas neste fim de semana, com frentes ativas e trânsito monitorado.',
                    'image_url' => 'https://images.unsplash.com/photo-1584467735871-8e85353a8413?w=600&auto=format&fit=crop&q=80',
                    'source' => 'Fonte: Diretoria de Infraestrutura',
                    'date_text' => now()->format('d/m/Y'),
                ],
                [
                    'section' => 1,
                    'position' => 3,
                    'title' => 'Feira de adoção de animais promove acolhimento e vacinação gratuita',
                    'description' => 'A campanha realiza feira de adoção responsável de cães e gatos com vacinação, vermifugação e orientações veterinárias para as famílias.',
                    'image_url' => 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?w=600&auto=format&fit=crop&q=80',
                    'source' => 'Fonte: Centro de Zoonoses / Proteção Animal',
                    'date_text' => now()->format('d/m/Y'),
                ],
                [
                    'section' => 1,
                    'position' => 4,
                    'title' => 'Novo incentivo para microempreendedores é aprovado pelo legislativo',
                    'description' => 'A proposta aprovada prevê redução de tributos municipais e facilidades para abertura e regularização de pequenos negócios locais.',
                    'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&auto=format&fit=crop&q=80',
                    'source' => 'Fonte: Câmara Municipal de Notícias',
                    'date_text' => now()->subDay()->format('d/m/Y'),
                ],
                // Section 2: Goiás & Brasil (4 items)
                [
                    'section' => 2,
                    'position' => 1,
                    'title' => 'Campanha Estadual de Doação de Órgãos mobiliza hospitais e famílias',
                    'description' => 'A data estadual reforça a importância da conscientização sobre a doação de órgãos e tecidos, que salvam centenas de vidas em todo o país.',
                    'image_url' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=600&auto=format&fit=crop&q=80',
                    'source' => 'Fonte: Secretaria de Saúde do Estado',
                    'date_text' => now()->format('d/m/Y'),
                ],
                [
                    'section' => 2,
                    'position' => 2,
                    'title' => 'Preparativos eleitorais: Justiça Eleitoral orienta eleitores',
                    'description' => 'O Tribunal Eleitoral orienta os cidadãos a conferirem com antecedência o local de votação, portando documento oficial com foto ou e-Título.',
                    'image_url' => 'https://images.unsplash.com/photo-1540910419892-4a36d2c3266c?w=600&auto=format&fit=crop&q=80',
                    'source' => 'Fonte: Tribunal Superior Eleitoral',
                    'date_text' => now()->format('d/m/Y'),
                ],
                [
                    'section' => 2,
                    'position' => 3,
                    'title' => 'Safra e plantio da soja batem novas expectativas de produtividade',
                    'description' => 'O calendário fitossanitário estabelece o início oficial da semeadura com tecnologia no campo e condições climáticas favoráveis para os produtores.',
                    'image_url' => 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=600&auto=format&fit=crop&q=80',
                    'source' => 'Fonte: Federação de Agricultura e Pecuária',
                    'date_text' => now()->subDays(2)->format('d/m/Y'),
                ],
                [
                    'section' => 2,
                    'position' => 4,
                    'title' => 'Programa Nota Fiscal distribui premiações para consumidores cadastrados',
                    'description' => 'O sorteio mensal contemplou milhares de participantes em todo o estado. Os valores já podem ser resgatados ou transferidos diretamente pelo portal.',
                    'image_url' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=600&auto=format&fit=crop&q=80',
                    'source' => 'Fonte: Secretaria de Economia do Estado',
                    'date_text' => now()->subDays(3)->format('d/m/Y'),
                ],
            ];

            foreach ($sampleNews as $item) {
                BulletinItem::create(array_merge($item, ['bulletin_id' => $bulletin->id]));
            }
        }
    }
}
