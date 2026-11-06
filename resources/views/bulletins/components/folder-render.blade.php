@props(['bulletin', 'isPublic' => false])

<div id="printable-flyer" class="flyer-canvas relative bg-white text-slate-800 flex flex-col justify-between select-none shadow-2xl rounded-none border border-slate-200" style="width: 1080px; min-height: 1080px; box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;">
    
    <!-- TOP HEADER -->
    <header class="relative px-8 pt-6 pb-4 bg-gradient-to-b from-slate-50 via-white to-white border-b-2 border-slate-200">
        <!-- Subtle city skyline / background graphic effect -->
        <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-10 pointer-events-none overflow-hidden flex items-end justify-end pr-4">
            <svg class="h-28 text-slate-800 fill-current" viewBox="0 0 500 150">
                <rect x="10" y="40" width="30" height="110" />
                <rect x="45" y="20" width="40" height="130" />
                <rect x="90" y="50" width="35" height="100" />
                <rect x="130" y="10" width="45" height="140" />
                <rect x="180" y="60" width="25" height="90" />
                <rect x="210" y="30" width="35" height="120" />
                <rect x="250" y="70" width="30" height="80" />
                <rect x="285" y="15" width="45" height="135" />
                <rect x="335" y="45" width="30" height="105" />
                <rect x="370" y="25" width="40" height="125" />
                <rect x="415" y="5" width="50" height="145" />
            </svg>
        </div>

        <div class="flex items-center justify-between relative z-10">
            <!-- Left: Logo & Portal Identity -->
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="{{ asset('images/logo-nova-alianca-round.jpg') }}" alt="Logo Portal Nova Aliança" class="w-28 h-28 object-contain rounded-full drop-shadow-md border-2 border-blue-900 bg-white">
                </div>
                <div class="flex flex-col justify-center">
                    <div class="text-[44px] font-black uppercase tracking-tight leading-none text-[#071e3d]" style="font-family: 'Montserrat', sans-serif;">
                        {{ $bulletin->title ?? 'BOLETIM' }}
                    </div>
                    <div class="text-[34px] font-black uppercase tracking-normal leading-tight text-[#d90429] flex items-center gap-2" style="font-family: 'Montserrat', sans-serif;">
                        {{ $bulletin->sub_title ?? 'NOVA ALIANÇA' }}
                    </div>
                    <div class="text-[12px] font-extrabold uppercase tracking-wider text-slate-600 mt-0.5">
                        {{ $bulletin->tagline ?? 'INFORMAÇÃO • CIDADANIA • ACONTECEU VIROU NOTÍCIA' }}
                    </div>
                </div>
            </div>

            <!-- Right: Location Badge + Goiás/Portal Symbol -->
            <div class="flex items-center gap-5">
                <!-- Location & Badge -->
                <div class="flex flex-col items-end text-right">
                    <div class="inline-flex items-center gap-1.5 text-[#071e3d] font-black text-sm uppercase">
                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $bulletin->header_location ?? 'GOIÂNIA & REGIÃO' }}</span>
                    </div>

                    <!-- 24h Portal Badge -->
                    <div class="mt-1.5 bg-[#d90429] text-white px-3 py-1 rounded-full text-xs font-black tracking-wider uppercase shadow-sm flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                            <polyline points="12 6 12 12 16 14" stroke-width="2"/>
                        </svg>
                        <span>{{ $bulletin->badge_text ?? '24 HORAS NO AR' }}</span>
                    </div>
                </div>

                <!-- Right Corner Circular Portal Badge / State icon -->
                <div class="w-16 h-16 rounded-xl bg-[#071e3d] text-white flex flex-col items-center justify-center p-1 shadow-md border-2 border-red-600">
                    <span class="text-[10px] font-bold text-red-400 uppercase tracking-tighter">PORTAL</span>
                    <span class="text-[12px] font-black text-white leading-none">NOVA</span>
                    <span class="text-[10px] font-black text-yellow-400 leading-none">ALIANÇA</span>
                </div>
            </div>
        </div>

        <!-- Date banner box (Exact style like reference) -->
        <div class="mt-4 flex items-center justify-center">
            <div class="bg-[#071e3d] text-white px-6 py-2 rounded-xl flex items-center gap-3 shadow-md border border-blue-900">
                <div class="bg-blue-600 p-1.5 rounded-lg text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
                        <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line>
                        <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"></line>
                    </svg>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-base font-black tracking-wider uppercase text-yellow-300">
                        {{ $bulletin->formatted_date_pt }}
                    </span>
                    <span class="text-blue-300">|</span>
                    <span class="text-sm font-bold tracking-wide uppercase text-white">
                        {{ $bulletin->day_of_week_pt }}
                    </span>
                    @if($bulletin->edition_time)
                        <span class="text-blue-300">|</span>
                        <span class="text-sm font-semibold text-slate-200">
                            {{ $bulletin->edition_time }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN NEWS BODY (2 Main Columns) -->
    <main class="flex-1 px-7 py-4 grid grid-cols-2 gap-7">
        
        <!-- SECTION 1 -->
        <div class="flex flex-col">
            <!-- Section Title Header Ribbon -->
            <div class="text-white px-4 py-2 rounded-lg flex items-center gap-2.5 shadow-sm mb-3.5" style="background-color: {{ $bulletin->section_1_color ?? '#0a369d' }};">
                <div class="bg-white/20 p-1 rounded-full">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 class="text-base font-black uppercase tracking-wider text-white" style="font-family: 'Montserrat', sans-serif;">
                    {{ $bulletin->section_1_title ?? 'CIDADE & METROPOLITANA' }}
                </h2>
            </div>

            <!-- News Grid: 2 columns x 2 rows (4 items) -->
            <div class="grid grid-cols-2 gap-4 flex-1">
                @forelse($bulletin->sectionOneItems as $item)
                    <article class="flex flex-col bg-slate-50/70 p-2 rounded-lg border border-slate-200 hover:border-slate-300 transition h-full justify-between">
                        <div>
                            <!-- Image -->
                            <div class="w-full h-28 rounded-md overflow-hidden bg-slate-200 relative mb-2 shadow-xs">
                                @if($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover" crossorigin="anonymous" onerror="if(this.src.indexOf('/img-proxy')===-1 && this.src.startsWith('http')){ this.src='/img-proxy?url='+encodeURIComponent(this.src); }">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                @if($item->badge)
                                    <span class="absolute top-1 right-1 bg-red-600 text-white text-[9px] font-black px-1.5 py-0.5 rounded shadow">
                                        {{ $item->badge }}
                                    </span>
                                @endif
                            </div>

                            <!-- Title -->
                            <h3 class="text-[12.5px] font-black leading-tight text-slate-900 mb-1 line-clamp-2" style="font-family: 'Montserrat', sans-serif;">
                                {{ $item->title }}
                            </h3>

                            <!-- Description -->
                            <p class="text-[10.5px] text-slate-600 leading-snug line-clamp-3">
                                {{ $item->description }}
                            </p>
                        </div>

                        <!-- Footer of Card: Date Badge & Source -->
                        <div class="mt-2 pt-1 border-t border-slate-200/60">
                            <div class="inline-flex items-center gap-1 bg-[#1b4332] text-white text-[9.5px] font-bold px-1.5 py-0.5 rounded mb-0.5">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
                                    <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line>
                                </svg>
                                <span>{{ $item->date_text ?? $bulletin->edition_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="text-[9.5px] font-semibold text-slate-500 truncate" title="{{ $item->source }}">
                                {{ $item->source ?? 'Fonte: Redação' }}
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-2 flex items-center justify-center p-6 border-2 border-dashed border-slate-300 rounded-lg text-slate-400 text-xs">
                        Nenhuma notícia adicionada nesta seção.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- SECTION 2 -->
        <div class="flex flex-col">
            <!-- Section Title Header Ribbon -->
            <div class="text-white px-4 py-2 rounded-lg flex items-center gap-2.5 shadow-sm mb-3.5" style="background-color: {{ $bulletin->section_2_color ?? '#0077b6' }};">
                <div class="bg-white/20 p-1 rounded-full">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 class="text-base font-black uppercase tracking-wider text-white" style="font-family: 'Montserrat', sans-serif;">
                    {{ $bulletin->section_2_title ?? 'GOIÁS & BRASIL' }}
                </h2>
            </div>

            <!-- News Grid: 2 columns x 2 rows (4 items) -->
            <div class="grid grid-cols-2 gap-4 flex-1">
                @forelse($bulletin->sectionTwoItems as $item)
                    <article class="flex flex-col bg-slate-50/70 p-2 rounded-lg border border-slate-200 hover:border-slate-300 transition h-full justify-between">
                        <div>
                            <!-- Image -->
                            <div class="w-full h-28 rounded-md overflow-hidden bg-slate-200 relative mb-2 shadow-xs">
                                @if($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover" crossorigin="anonymous" onerror="if(this.src.indexOf('/img-proxy')===-1 && this.src.startsWith('http')){ this.src='/img-proxy?url='+encodeURIComponent(this.src); }">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                @if($item->badge)
                                    <span class="absolute top-1 right-1 bg-red-600 text-white text-[9px] font-black px-1.5 py-0.5 rounded shadow">
                                        {{ $item->badge }}
                                    </span>
                                @endif
                            </div>

                            <!-- Title -->
                            <h3 class="text-[12.5px] font-black leading-tight text-slate-900 mb-1 line-clamp-2" style="font-family: 'Montserrat', sans-serif;">
                                {{ $item->title }}
                            </h3>

                            <!-- Description -->
                            <p class="text-[10.5px] text-slate-600 leading-snug line-clamp-3">
                                {{ $item->description }}
                            </p>
                        </div>

                        <!-- Footer of Card: Date Badge & Source -->
                        <div class="mt-2 pt-1 border-t border-slate-200/60">
                            <div class="inline-flex items-center gap-1 bg-[#1b4332] text-white text-[9.5px] font-bold px-1.5 py-0.5 rounded mb-0.5">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"></line>
                                    <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"></line>
                                </svg>
                                <span>{{ $item->date_text ?? $bulletin->edition_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="text-[9.5px] font-semibold text-slate-500 truncate" title="{{ $item->source }}">
                                {{ $item->source ?? 'Fonte: Redação' }}
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-2 flex items-center justify-center p-6 border-2 border-dashed border-slate-300 rounded-lg text-slate-400 text-xs">
                        Nenhuma notícia adicionada nesta seção.
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- FOOTER: MODERN NOVA ALIANÇA PORTAL FOOTER -->
    <footer class="bg-gradient-to-r from-[#071e3d] via-[#0b2b5c] to-[#071e3d] text-white px-8 py-3.5 border-t-4 border-[#d90429] flex items-center justify-between">
        <!-- Left: Logo badge & portal name -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo-nova-alianca-round.jpg') }}" alt="Logo" class="w-12 h-12 rounded-full border border-white/60 bg-white">
            <div>
                <span class="block text-[10px] font-black uppercase text-red-400 tracking-wider">
                    {{ $bulletin->footer_title ?? 'PORTAL DE NOTÍCIAS NOVA ALIANÇA' }}
                </span>
                <span class="block text-sm font-black italic tracking-wide text-white">
                    "{{ $bulletin->footer_slogan ?? 'Aconteceu virou notícia. 24 horas no ar.' }}"
                </span>
            </div>
        </div>

        <!-- Center: Contacts / Social Channels -->
        <div class="flex items-center gap-5 bg-white/10 px-4 py-2 rounded-xl backdrop-blur-xs border border-white/15">
            <!-- WhatsApp -->
            @if($bulletin->footer_whatsapp)
                <div class="flex items-center gap-1.5">
                    <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center text-white">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-white tracking-wide">{{ $bulletin->footer_whatsapp }}</span>
                </div>
            @endif

            <!-- Instagram -->
            @if($bulletin->footer_instagram)
                <div class="flex items-center gap-1.5">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 flex items-center justify-center text-white">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-white tracking-wide">{{ $bulletin->footer_instagram }}</span>
                </div>
            @endif

            <!-- Website -->
            @if($bulletin->footer_website)
                <div class="flex items-center gap-1.5">
                    <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                            <line x1="2" y1="12" x2="22" y2="12" stroke-width="2"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" stroke-width="2"/>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-white tracking-wide">{{ $bulletin->footer_website }}</span>
                </div>
            @endif
        </div>

        <!-- Right: 24h Portal emblem / Note -->
        <div class="flex items-center gap-2 text-right">
            <div>
                <span class="block text-[9px] uppercase tracking-wider text-slate-300">Jornalismo Comunitário</span>
                <span class="block text-[11px] font-black text-yellow-400 uppercase">Participe da Notícia</span>
            </div>
            <div class="w-9 h-9 rounded-lg bg-red-600 flex items-center justify-center text-white font-black text-xs shadow">
                24h
            </div>
        </div>
    </footer>
</div>

