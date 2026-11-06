@props(['bulletin', 'isEdit' => false])

@php
    $initialItems = [];
    if ($bulletin->exists && $bulletin->items->count() > 0) {
        $initialItems = $bulletin->items->map(function ($item) {
            return [
                'id' => $item->id,
                'section' => $item->section,
                'position' => $item->position,
                'title' => $item->title,
                'description' => $item->description,
                'image_url' => $item->image_url,
                'source' => $item->source,
                'date_text' => $item->date_text,
                'source_url' => $item->source_url,
                'badge' => $item->badge,
            ];
        })->values()->toArray();
    } else {
        // Default 8 empty card placeholders (4 for section 1, 4 for section 2)
        for ($s = 1; $s <= 2; $s++) {
            for ($p = 1; $p <= 4; $p++) {
                $initialItems[] = [
                    'id' => null,
                    'section' => $s,
                    'position' => $p,
                    'title' => '',
                    'description' => '',
                    'image_url' => '',
                    'source' => 'Fonte: Redação',
                    'date_text' => now()->format('d/m/Y'),
                    'source_url' => '',
                    'badge' => '',
                ];
            }
        }
    }
@endphp

<div x-data="bulletinEditor({
    initialItems: {{ Js::from($initialItems) }},
    editionDate: '{{ old('edition_date', $bulletin->edition_date ? $bulletin->edition_date->format('Y-m-d') : now()->format('Y-m-d')) }}',
    csrfToken: '{{ csrf_token() }}',
    scrapeUrl: '{{ route('scraper.scrape') }}',
    uploadUrl: '{{ route('scraper.upload-image') }}'
})" class="space-y-8">

    <form action="{{ $isEdit ? route('bulletins.update', $bulletin) : route('bulletins.store') }}" method="POST" id="bulletin-form" class="space-y-8">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <!-- TOP BAR: FAST LINK SCRAPER TOOL -->
        <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-blue-950 rounded-2xl p-6 text-white shadow-lg border border-blue-800">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-600/40 border border-blue-400/30 flex items-center justify-center text-yellow-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-white flex items-center gap-2">
                            <span>Puxador Rápido de Notícias por Link</span>
                            <span class="bg-yellow-400 text-blue-950 text-[10px] font-black uppercase px-2 py-0.5 rounded-full">Automático</span>
                        </h2>
                        <p class="text-xs text-blue-200">
                            Cole a URL de qualquer notícia da internet (G1, UOL, CNN, Folha, etc.) para extrair título, foto, resumo e fonte automaticamente!
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" @click="showBatchModal = true" class="px-3 py-1.5 bg-yellow-400/20 hover:bg-yellow-400/30 border border-yellow-400/40 text-yellow-300 text-xs font-bold rounded-lg transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span>⚡ Importar Links em Lote</span>
                    </button>

                    <button type="button" @click="loadDemoNews()" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 border border-white/20 text-xs font-bold rounded-lg transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14H8a4 4 0 01-.82-7.915A4 4 0 0115.82 8.085 4 4 0 0112 14z" />
                        </svg>
                        <span>Exemplos</span>
                    </button>
                </div>
            </div>

            <!-- Fast Input Row -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                <div class="md:col-span-3">
                    <label class="block text-[11px] font-bold uppercase text-blue-200 mb-1">Onde inserir:</label>
                    <select x-model="quickSection" class="w-full bg-blue-950/70 border border-blue-400/40 rounded-xl px-3 py-2.5 text-sm text-white focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        <option value="1">Seção 1: Cidade & Metropolitana</option>
                        <option value="2">Seção 2: Goiás & Brasil</option>
                    </select>
                </div>

                <div class="md:col-span-7">
                    <label class="block text-[11px] font-bold uppercase text-blue-200 mb-1">Link da Notícia:</label>
                    <div class="relative">
                        <input type="url" x-model="quickUrl" @keydown.enter.prevent="scrapeQuickUrl()" placeholder="https://g1.globo.com/go/goias/noticia/..." class="w-full bg-white text-slate-800 placeholder-slate-400 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none border-0 shadow-inner">
                        <button type="button" x-show="quickUrl" @click="quickUrl = ''" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                            ✕
                        </button>
                    </div>
                </div>

                <div class="md:col-span-2 pt-4 md:pt-0">
                    <button type="button" @click="scrapeQuickUrl()" :disabled="scraping" class="w-full px-4 py-2.5 bg-yellow-400 hover:bg-yellow-300 text-blue-950 font-black rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2 disabled:opacity-50">
                        <template x-if="scraping">
                            <svg class="animate-spin h-4 w-4 text-blue-950" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </template>
                        <span x-text="scraping ? 'Extraindo...' : '✨ Puxar Notícia'"></span>
                    </button>
                </div>
            </div>

            <!-- Feedback Message -->
            <div x-show="scrapeMessage" x-transition class="mt-3 p-3 rounded-lg text-xs flex items-center justify-between" :class="scrapeSuccess ? 'bg-emerald-500/20 text-emerald-200 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-200 border border-rose-500/30'">
                <span x-text="scrapeMessage"></span>
                <button type="button" @click="scrapeMessage = ''" class="text-white/60 hover:text-white">✕</button>
            </div>
        </div>

        <!-- FOLDER SETTINGS ACCORDION / CARD -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                        ⚙️
                    </div>
                    <div>
                        <h2 class="text-base font-black text-slate-800 uppercase tracking-wide">Configurações do Cabeçalho & Folder</h2>
                        <p class="text-xs text-slate-500">Defina a data, títulos e contatos que saem no cabeçalho e rodapé do folder.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Edition Date -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Data da Edição *</label>
                    <input type="date" name="edition_date" x-model="editionDate" required class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="text-[11px] text-slate-500 mt-1">
                        Dia no folder: <span class="font-bold text-blue-700" x-text="formatDatePreview(editionDate)"></span>
                    </p>
                </div>

                <!-- Edition Time / Period -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Horário / Período</label>
                    <input type="text" name="edition_time" value="{{ old('edition_time', $bulletin->edition_time ?? now()->format('H\hi')) }}" placeholder="Ex: 07h15, Manhã, etc." class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Localização no Cabeçalho</label>
                    <input type="text" name="header_location" value="{{ old('header_location', $bulletin->header_location ?? 'GOIÂNIA & REGIÃO') }}" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Badge -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Selo Superior</label>
                    <input type="text" name="badge_text" value="{{ old('badge_text', $bulletin->badge_text ?? '24 HORAS NO AR') }}" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Título Principal</label>
                    <input type="text" name="title" value="{{ old('title', $bulletin->title ?? 'BOLETIM') }}" class="w-full rounded-lg border-slate-300 text-sm font-bold uppercase focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Subtitle -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Subtítulo (Destaque)</label>
                    <input type="text" name="sub_title" value="{{ old('sub_title', $bulletin->sub_title ?? 'NOVA ALIANÇA') }}" class="w-full rounded-lg border-slate-300 text-sm font-bold uppercase focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Tagline -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Slogan do Cabeçalho</label>
                    <input type="text" name="tagline" value="{{ old('tagline', $bulletin->tagline ?? 'INFORMAÇÃO • CIDADANIA • ACONTECEU VIROU NOTÍCIA') }}" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Sections Titles & Colors -->
            <div class="pt-4 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Section 1 -->
                <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                    <span class="text-xs font-black text-blue-900 uppercase">Faixa da Coluna 1</span>
                    <div class="grid grid-cols-3 gap-3 mt-2">
                        <div class="col-span-2">
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Título da Seção 1 *</label>
                            <input type="text" name="section_1_title" value="{{ old('section_1_title', $bulletin->section_1_title ?? 'CIDADE & METROPOLITANA') }}" required class="w-full rounded-lg border-slate-300 text-sm font-bold focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Cor</label>
                            <input type="color" name="section_1_color" value="{{ old('section_1_color', $bulletin->section_1_color ?? '#0a369d') }}" class="w-full h-10 rounded-lg cursor-pointer border-slate-300">
                        </div>
                    </div>
                </div>

                <!-- Section 2 -->
                <div class="bg-teal-50/50 p-4 rounded-xl border border-teal-100">
                    <span class="text-xs font-black text-teal-900 uppercase">Faixa da Coluna 2</span>
                    <div class="grid grid-cols-3 gap-3 mt-2">
                        <div class="col-span-2">
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Título da Seção 2 *</label>
                            <input type="text" name="section_2_title" value="{{ old('section_2_title', $bulletin->section_2_title ?? 'GOIÁS & BRASIL') }}" required class="w-full rounded-lg border-slate-300 text-sm font-bold focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Cor</label>
                            <input type="color" name="section_2_color" value="{{ old('section_2_color', $bulletin->section_2_color ?? '#0077b6') }}" class="w-full h-10 rounded-lg cursor-pointer border-slate-300">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Contacts -->
            <div class="pt-4 border-t border-slate-100">
                <span class="text-xs font-black text-slate-700 uppercase">Dados do Rodapé</span>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-2">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">WhatsApp</label>
                        <input type="text" name="footer_whatsapp" value="{{ old('footer_whatsapp', $bulletin->footer_whatsapp ?? '(62) 99999-9999') }}" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Instagram</label>
                        <input type="text" name="footer_instagram" value="{{ old('footer_instagram', $bulletin->footer_instagram ?? '@portalnovaalianca') }}" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Website</label>
                        <input type="text" name="footer_website" value="{{ old('footer_website', $bulletin->footer_website ?? 'www.portalnovaalianca.com.br') }}" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Slogan do Rodapé</label>
                        <input type="text" name="footer_slogan" value="{{ old('footer_slogan', $bulletin->footer_slogan ?? 'Aconteceu virou notícia. 24 horas no ar.') }}" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN NEWS CARDS SECTION (COL 1 & COL 2) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- SECTION 1 NEWS -->
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-2 border-b-2 border-blue-600">
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full bg-blue-600"></span>
                        <h3 class="text-base font-black text-slate-900 uppercase">Coluna 1: Cidade & Região</h3>
                    </div>
                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-800">
                        <span x-text="getItemsForSection(1).length"></span> notícias
                    </span>
                </div>

                <div class="space-y-4">
                    <template x-for="(item, index) in items" :key="index">
                        <template x-if="item.section == 1">
                            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm hover:border-blue-400 transition relative">
                                
                                <!-- Card Slot Badge -->
                                <div class="flex items-center justify-between pb-2 mb-3 border-b border-slate-100">
                                    <span class="text-xs font-black text-blue-900 flex items-center gap-1.5">
                                        <span class="w-5 h-5 rounded-full bg-blue-700 text-white flex items-center justify-center text-[10px]" x-text="item.position"></span>
                                        <span>Notícia Slot <span x-text="item.position"></span></span>
                                    </span>

                                    <!-- Quick slot scraper button -->
                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="clearItem(item)" class="text-xs text-slate-400 hover:text-rose-600 font-semibold transition" title="Limpar este card">
                                            Limpar
                                        </button>
                                    </div>
                                </div>

                                <!-- Slot quick link scrape -->
                                <div class="mb-3 flex items-center gap-2 bg-slate-50 p-2 rounded-lg border border-slate-200">
                                    <input type="url" x-model="item.tempUrl" placeholder="Colar link direto nesta matéria..." class="flex-1 text-xs rounded-md border-slate-300 py-1.5 focus:border-blue-500 focus:ring-0">
                                    <button type="button" @click="scrapeSlotUrl(item)" :disabled="item.scraping" class="px-2.5 py-1.5 bg-blue-700 hover:bg-blue-800 text-white rounded-md text-xs font-bold transition flex items-center gap-1 shrink-0 disabled:opacity-50">
                                        <span x-text="item.scraping ? '...' : 'Puxar'"></span>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                    <!-- Image Preview & Upload -->
                                    <div class="sm:col-span-4">
                                        <div class="w-full h-28 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 relative group flex items-center justify-center">
                                            <template x-if="item.image_url">
                                                <img :src="item.image_url" x-on:error="handleImgErr(item, $event)" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!item.image_url">
                                                <div class="text-center p-2 text-slate-400 text-[10px]">
                                                    <svg class="w-6 h-6 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    Sem foto
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Upload button or URL -->
                                        <div class="mt-2 flex flex-col gap-1">
                                            <label class="w-full px-2 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-800 rounded text-[11px] font-bold text-center cursor-pointer transition border border-blue-200 flex items-center justify-center gap-1">
                                                <span x-text="item.uploading ? 'Enviando...' : '📁 Enviar Foto'"></span>
                                                <input type="file" @change="uploadSlotImage($event, item)" accept="image/*" class="hidden">
                                            </label>
                                            <input type="text" x-model="item.image_url" placeholder="Ou cole URL da foto..." class="w-full text-[10px] rounded border-slate-200 py-1 px-1.5 text-slate-600 focus:border-blue-500">
                                            <input type="hidden" :name="'items[' + index + '][image_url]'" x-model="item.image_url">
                                        </div>
                                    </div>

                                    <!-- Fields -->
                                    <div class="sm:col-span-8 space-y-2">
                                        <!-- Hidden Inputs for Form Submission -->
                                        <input type="hidden" :name="'items[' + index + '][section]'" x-model="item.section">
                                        <input type="hidden" :name="'items[' + index + '][position]'" x-model="item.position">
                                        <input type="hidden" :name="'items[' + index + '][source_url]'" x-model="item.source_url">

                                        <!-- Title -->
                                        <div>
                                            <input type="text" :name="'items[' + index + '][title]'" x-model="item.title" placeholder="Manchete / Título da Notícia..." class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-blue-500 py-1.5">
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <textarea :name="'items[' + index + '][description]'" x-model="item.description" rows="2" placeholder="Resumo curto da notícia (2 a 3 linhas)..." class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 py-1.5"></textarea>
                                        </div>

                                        <!-- Date & Source -->
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <input type="text" :name="'items[' + index + '][date_text]'" x-model="item.date_text" placeholder="06/11/2026" class="w-full text-[11px] font-semibold rounded-lg border-slate-300 py-1">
                                            </div>
                                            <div>
                                                <input type="text" :name="'items[' + index + '][source]'" x-model="item.source" placeholder="Fonte: Prefeitura..." class="w-full text-[11px] rounded-lg border-slate-300 py-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>

            <!-- SECTION 2 NEWS -->
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-2 border-b-2 border-teal-600">
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full bg-teal-600"></span>
                        <h3 class="text-base font-black text-slate-900 uppercase">Coluna 2: Goiás & Brasil</h3>
                    </div>
                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-teal-100 text-teal-800">
                        <span x-text="getItemsForSection(2).length"></span> notícias
                    </span>
                </div>

                <div class="space-y-4">
                    <template x-for="(item, index) in items" :key="index">
                        <template x-if="item.section == 2">
                            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm hover:border-teal-400 transition relative">
                                
                                <!-- Card Slot Badge -->
                                <div class="flex items-center justify-between pb-2 mb-3 border-b border-slate-100">
                                    <span class="text-xs font-black text-teal-900 flex items-center gap-1.5">
                                        <span class="w-5 h-5 rounded-full bg-teal-700 text-white flex items-center justify-center text-[10px]" x-text="item.position"></span>
                                        <span>Notícia Slot <span x-text="item.position"></span></span>
                                    </span>

                                    <!-- Quick slot scraper button -->
                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="clearItem(item)" class="text-xs text-slate-400 hover:text-rose-600 font-semibold transition" title="Limpar este card">
                                            Limpar
                                        </button>
                                    </div>
                                </div>

                                <!-- Slot quick link scrape -->
                                <div class="mb-3 flex items-center gap-2 bg-slate-50 p-2 rounded-lg border border-slate-200">
                                    <input type="url" x-model="item.tempUrl" placeholder="Colar link direto nesta matéria..." class="flex-1 text-xs rounded-md border-slate-300 py-1.5 focus:border-teal-500 focus:ring-0">
                                    <button type="button" @click="scrapeSlotUrl(item)" :disabled="item.scraping" class="px-2.5 py-1.5 bg-teal-700 hover:bg-teal-800 text-white rounded-md text-xs font-bold transition flex items-center gap-1 shrink-0 disabled:opacity-50">
                                        <span x-text="item.scraping ? '...' : 'Puxar'"></span>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                    <!-- Image Preview & Upload -->
                                    <div class="sm:col-span-4">
                                        <div class="w-full h-28 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 relative group flex items-center justify-center">
                                            <template x-if="item.image_url">
                                                <img :src="item.image_url" x-on:error="handleImgErr(item, $event)" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!item.image_url">
                                                <div class="text-center p-2 text-slate-400 text-[10px]">
                                                    <svg class="w-6 h-6 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    Sem foto
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Upload button or URL -->
                                        <div class="mt-2 flex flex-col gap-1">
                                            <label class="w-full px-2 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-800 rounded text-[11px] font-bold text-center cursor-pointer transition border border-blue-200 flex items-center justify-center gap-1">
                                                <span x-text="item.uploading ? 'Enviando...' : '📁 Enviar Foto'"></span>
                                                <input type="file" @change="uploadSlotImage($event, item)" accept="image/*" class="hidden">
                                            </label>
                                            <input type="text" x-model="item.image_url" placeholder="Ou cole URL da foto..." class="w-full text-[10px] rounded border-slate-200 py-1 px-1.5 text-slate-600 focus:border-blue-500">
                                            <input type="hidden" :name="'items[' + index + '][image_url]'" x-model="item.image_url">
                                        </div>
                                    </div>

                                    <!-- Fields -->
                                    <div class="sm:col-span-8 space-y-2">
                                        <!-- Hidden Inputs for Form Submission -->
                                        <input type="hidden" :name="'items[' + index + '][section]'" x-model="item.section">
                                        <input type="hidden" :name="'items[' + index + '][position]'" x-model="item.position">
                                        <input type="hidden" :name="'items[' + index + '][source_url]'" x-model="item.source_url">

                                        <!-- Title -->
                                        <div>
                                            <input type="text" :name="'items[' + index + '][title]'" x-model="item.title" placeholder="Manchete / Título da Notícia..." class="w-full text-xs font-bold rounded-lg border-slate-300 focus:border-teal-500 py-1.5">
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <textarea :name="'items[' + index + '][description]'" x-model="item.description" rows="2" placeholder="Resumo curto da notícia (2 a 3 linhas)..." class="w-full text-xs rounded-lg border-slate-300 focus:border-teal-500 py-1.5"></textarea>
                                        </div>

                                        <!-- Date & Source -->
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <input type="text" :name="'items[' + index + '][date_text]'" x-model="item.date_text" placeholder="06/11/2026" class="w-full text-[11px] font-semibold rounded-lg border-slate-300 py-1">
                                            </div>
                                            <div>
                                                <input type="text" :name="'items[' + index + '][source]'" x-model="item.source" placeholder="Fonte: Notícias..." class="w-full text-[11px] rounded-lg border-slate-300 py-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </div>

        <!-- STICKY ACTION BAR -->
        <div class="sticky bottom-4 z-40 bg-white/95 backdrop-blur-md rounded-2xl p-4 border border-slate-300 shadow-xl flex items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                <span class="text-xs font-bold text-slate-700">Pronto para gerar seu folder profissional!</span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('bulletins.index') }}" class="px-4 py-2 text-slate-600 hover:text-slate-800 text-sm font-semibold transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-black rounded-xl shadow-md transition transform hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Salvar e Visualizar Folder</span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function bulletinEditor(config) {
    return {
        items: config.initialItems || [],
        editionDate: config.editionDate,
        quickSection: '1',
        quickUrl: '',
        scraping: false,
        scrapeMessage: '',
        scrapeSuccess: true,

        getItemsForSection(sectionNumber) {
            return this.items.filter(i => i.section == sectionNumber);
        },

        formatDatePreview(dateStr) {
            if (!dateStr) return '';
            const parts = dateStr.split('-');
            if (parts.length !== 3) return dateStr;
            const d = new Date(parts[0], parts[1] - 1, parts[2]);
            const days = ['DOMINGO', 'SEGUNDA-FEIRA', 'TERÇA-FEIRA', 'QUARTA-FEIRA', 'QUINTA-FEIRA', 'SEXTA-FEIRA', 'SÁBADO'];
            return days[d.getDay()] + ', ' + parts[2] + '/' + parts[1] + '/' + parts[0];
        },

        async scrapeQuickUrl() {
            if (!this.quickUrl) {
                alert('Por favor, cole um link de notícia válido.');
                return;
            }

            this.scraping = true;
            this.scrapeMessage = 'Extraindo dados da notícia...';
            this.scrapeSuccess = true;

            try {
                const response = await fetch(config.scrapeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': config.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ url: this.quickUrl })
                });

                const res = await response.json();

                if (res.success && res.data) {
                    // Find first empty slot in chosen section, or first slot
                    const sectionItems = this.items.filter(i => i.section == this.quickSection);
                    let target = sectionItems.find(i => !i.title || i.title.trim() === '');
                    
                    if (!target && sectionItems.length > 0) {
                        target = sectionItems[0];
                    }

                    if (target) {
                        target.title = res.data.title || '';
                        target.description = res.data.description || '';
                        target.image_url = res.data.image_url || '';
                        target.source = res.data.source || 'Fonte: Redação';
                        target.date_text = res.data.date_text || '';
                        target.source_url = res.data.url || '';
                    }

                    this.scrapeSuccess = true;
                    this.scrapeMessage = '✓ Notícia adicionada com sucesso na Seção ' + this.quickSection + '!';
                    this.quickUrl = '';
                } else {
                    this.scrapeSuccess = false;
                    this.scrapeMessage = res.message || 'Não foi possível extrair a notícia.';
                }
            } catch (err) {
                this.scrapeSuccess = false;
                this.scrapeMessage = 'Erro ao conectar ao servidor para extrair dados: ' + err.message;
            } finally {
                this.scraping = false;
            }
        },

        async scrapeSlotUrl(item) {
            if (!item.tempUrl) {
                alert('Informe a URL da notícia para este slot.');
                return;
            }

            item.scraping = true;
            try {
                const response = await fetch(config.scrapeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': config.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ url: item.tempUrl })
                });

                const res = await response.json();
                if (res.success && res.data) {
                    item.title = res.data.title || '';
                    item.description = res.data.description || '';
                    item.image_url = res.data.image_url || '';
                    item.source = res.data.source || 'Fonte: Redação';
                    item.date_text = res.data.date_text || '';
                    item.source_url = res.data.url || '';
                    item.tempUrl = '';
                } else {
                    alert(res.message || 'Não foi possível extrair os dados deste link.');
                }
            } catch (err) {
                alert('Erro na extração: ' + err.message);
            } finally {
                item.scraping = false;
            }
        },

        handleImgErr(item, e) {
            if (item.image_url && item.image_url.startsWith('http') && !item.image_url.includes('/img-proxy')) {
                item.image_url = '/img-proxy?url=' + encodeURIComponent(item.image_url);
            }
        },

        async uploadSlotImage(e, item) {
            const file = e.target.files[0];
            if (!file) return;

            item.uploading = true;
            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch(config.uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': config.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const res = await response.json();
                if (res.success && res.image_url) {
                    item.image_url = res.image_url;
                } else {
                    alert('Falha ao enviar imagem: ' + (res.message || 'Erro desconhecido'));
                }
            } catch (err) {
                alert('Erro ao enviar imagem: ' + err.message);
            } finally {
                item.uploading = false;
            }
        },

        clearItem(item) {
            item.title = '';
            item.description = '';
            item.image_url = '';
            item.source = 'Fonte: Redação';
            item.source_url = '';
            item.badge = '';
        },

        loadDemoNews() {
            const demoData = [
                // Section 1
                {
                    section: 1, position: 1,
                    title: 'Exposição de Orquídeas e Flores chega ao último dia',
                    description: 'A 24ª Exposição de Orquídeas e Rosas do Deserto acontece hoje com visitação gratuita e arrecadação de alimentos para entidades sociais.',
                    image_url: 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?w=600&auto=format&fit=crop&q=80',
                    source: 'Fonte: Secretaria Municipal de Cultura', date_text: 'Hoje'
                },
                {
                    section: 1, position: 2,
                    title: 'Recuperação asfáltica avança em ritmo acelerado nos setores',
                    description: 'Equipes municipais mantém o cronograma de serviços de pavimentação e sinalização viária neste fim de semana.',
                    image_url: 'https://images.unsplash.com/photo-1584467735871-8e85353a8413?w=600&auto=format&fit=crop&q=80',
                    source: 'Fonte: Diretoria de Obras e Infraestrutura', date_text: 'Hoje'
                },
                {
                    section: 1, position: 3,
                    title: 'Feira de adoção de animais promove acolhimento e vacinação',
                    description: 'A Prefeitura realiza feira de adoção responsável com dezenas de cães e gatos castrados e vacinados disponíveis para famílias.',
                    image_url: 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?w=600&auto=format&fit=crop&q=80',
                    source: 'Fonte: Centro de Proteção Animal', date_text: 'Hoje'
                },
                {
                    section: 1, position: 4,
                    title: 'Novo incentivo para microempreendedores é aprovado na Câmara',
                    description: 'A proposta aprovada prevê redução de tributos e simplificação cadastral para pequenos empresários e autônomos locais.',
                    image_url: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&auto=format&fit=crop&q=80',
                    source: 'Fonte: Câmara Municipal de Notícias', date_text: 'Ontem'
                },
                // Section 2
                {
                    section: 2, position: 1,
                    title: 'Campanha de Doação de Órgãos bate novo recorde de adesão',
                    description: 'Data de mobilização e conscientização reforça a importância das famílias autorizarem transplantes que salvam vidas todos os meses.',
                    image_url: 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=600&auto=format&fit=crop&q=80',
                    source: 'Fonte: Secretaria de Estado de Saúde', date_text: 'Hoje'
                },
                {
                    section: 2, position: 2,
                    title: 'Justiça Eleitoral reforça orientações sobre documentação e voto',
                    description: 'O Tribunal Eleitoral orienta eleitores a consultarem suas seções e baixarem o título eletrônico com antecedência antes do pleito.',
                    image_url: 'https://images.unsplash.com/photo-1540910419892-4a36d2c3266c?w=600&auto=format&fit=crop&q=80',
                    source: 'Fonte: Tribunal Superior Eleitoral', date_text: 'Hoje'
                },
                {
                    section: 2, position: 3,
                    title: 'Plantio da safra atinge áreas estratégicas com alta tecnologia',
                    description: 'O calendário fitossanitário entra no período de semeadura com ótimas perspectivas climáticas e suporte aos produtores rurais.',
                    image_url: 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=600&auto=format&fit=crop&q=80',
                    source: 'Fonte: Federação de Agricultura', date_text: 'Ontem'
                },
                {
                    section: 2, position: 4,
                    title: 'Programa Cidadão distribui premiações para cadastrados',
                    description: 'O sorteio mensal de notas fiscais contemplou milhares de participantes em diversas regiões com prêmios de até R$ 200 mil.',
                    image_url: 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=600&auto=format&fit=crop&q=80',
                    source: 'Fonte: Secretaria de Economia', date_text: 'Ontem'
                }
            ];

            this.items = demoData;
            alert('Exemplos carregados! Você pode salvar ou ajustar qualquer dado.');
        }
    }
}
</script>

