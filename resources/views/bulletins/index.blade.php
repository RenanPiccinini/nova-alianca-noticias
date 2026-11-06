<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="text-blue-700">Folders & Boletins Prontos</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">Portal Nova Aliança</span>
                </h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Visualize, edite, exclua e compartilhe os folders diretamente no WhatsApp.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <form action="{{ route('bulletins.seed-demo') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 font-semibold rounded-lg text-sm shadow-xs transition">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span>Gerar Folder Exemplo</span>
                    </button>
                </form>

                <a href="{{ route('bulletins.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-lg text-sm shadow-md transition transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>+ Novo Folder</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Flash alerts -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-lg flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-lg flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Modal Feedback WhatsApp Desktop -->
            <div id="whatsapp-desktop-modal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 text-center shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95">
                    <div class="w-16 h-16 mx-auto rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4">
                        <svg class="w-9 h-9 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 mb-2">Foto Pronta para Envio!</h3>
                    <p class="text-sm text-slate-600 mb-4 leading-relaxed">
                        A foto oficial do folder foi <strong>copiada para sua área de transferência</strong> e baixada no seu computador.
                    </p>
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-xs text-emerald-800 text-left mb-5 space-y-1">
                        <div class="font-bold flex items-center gap-1.5">
                            <span>📲 No WhatsApp que abriu:</span>
                        </div>
                        <p class="text-emerald-700">
                            1. Abra a conversa ou grupo desejado.<br>
                            2. Pressione <strong>Ctrl + V</strong> (Colar) para enviar <strong>somente a foto</strong> do folder pronto!
                        </p>
                    </div>
                    <button type="button" onclick="closeWhatsAppModal()" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-sm transition">
                        Entendi, fechar aviso
                    </button>
                </div>
            </div>

            <!-- Quick Start Banner if empty -->
            @if($bulletins->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-xs">
                    <div class="w-20 h-20 mx-auto rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">Nenhum folder criado ainda</h2>
                    <p class="text-slate-500 max-w-md mx-auto text-sm mt-1 mb-6">
                        Crie sua primeira edição do folder diário do Portal Nova Aliança. Você pode informar as notícias manualmente ou apenas colar o link de matérias para puxar a foto e texto automaticamente!
                    </p>
                    <div class="flex items-center justify-center gap-3">
                        <form action="{{ route('bulletins.seed-demo') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm font-bold shadow-xs transition">
                                ⚡ Ver Folder de Exemplo
                            </button>
                        </form>
                        <a href="{{ route('bulletins.create') }}" class="px-5 py-2.5 rounded-lg bg-blue-700 hover:bg-blue-800 text-white text-sm font-bold shadow-md transition">
                            + Criar Novo Folder
                        </a>
                    </div>
                </div>
            @else
                <!-- Grid of Bulletins with Clear Action Buttons -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($bulletins as $item)
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col justify-between">
                            
                            <!-- Card Body -->
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <span class="inline-flex items-center gap-1 text-[11px] font-black uppercase tracking-wider text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md">
                                            📅 {{ $item->formatted_date_pt }}
                                        </span>
                                        <h3 class="text-lg font-black text-slate-900 mt-2 leading-tight">
                                            {{ $item->title }} {{ $item->sub_title }}
                                        </h3>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            {{ $item->day_of_week_pt }} • {{ $item->edition_time ?? 'Edição do Dia' }}
                                        </p>
                                    </div>
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 shadow-xs">
                                        <img src="{{ asset('images/logo-nova-alianca-round.jpg') }}" alt="Logo" class="w-10 h-10 rounded-full object-contain">
                                    </div>
                                </div>

                                <!-- Sections & Items Summary -->
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                                    <div class="flex items-center gap-1.5 font-semibold">
                                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $item->section_1_color }}"></span>
                                        <span>{{ Str::limit($item->section_1_title, 14) }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 font-semibold">
                                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $item->section_2_color }}"></span>
                                        <span>{{ Str::limit($item->section_2_title, 14) }}</span>
                                    </div>
                                    <span class="font-bold bg-slate-100 px-2 py-0.5 rounded text-slate-700">
                                        {{ $item->items->count() }} notícias
                                    </span>
                                </div>
                            </div>

                            <!-- BUTTONS BAR: VISUALIZAR, EDITAR, EXCLUIR, COMPARTILHAR NO WHATSAPP COM A FOTO -->
                            <div class="p-4 bg-slate-50/80 border-t border-slate-100 space-y-2.5">
                                
                                <!-- Primary Action: Compartilhar no WhatsApp com a foto do folder -->
                                <button type="button" 
                                        onclick="shareFolderToWhatsApp({{ $item->id }}, '{{ route('bulletins.render', $item) }}', '{{ $item->slug }}')"
                                        id="btn-whatsapp-{{ $item->id }}"
                                        class="w-full py-2.5 px-3 bg-[#25D366] hover:bg-[#20ba59] text-white font-black rounded-xl text-xs shadow-sm transition flex items-center justify-center gap-2 transform active:scale-98">
                                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/>
                                    </svg>
                                    <span id="label-whatsapp-{{ $item->id }}">Compartilhar Foto no WhatsApp</span>
                                </button>

                                <!-- Secondary Actions: Visualizar, Editar, Excluir -->
                                <div class="grid grid-cols-3 gap-2">
                                    <!-- Visualizar Folder -->
                                    <a href="{{ route('bulletins.show', $item) }}" class="py-2 px-2 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-xs font-bold transition flex items-center justify-center gap-1 shadow-2xs text-center" title="Visualizar Folder">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span class="truncate">Visualizar</span>
                                    </a>

                                    <!-- Editar -->
                                    <a href="{{ route('bulletins.edit', $item) }}" class="py-2 px-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-lg text-xs font-bold transition flex items-center justify-center gap-1 text-center" title="Editar Notícias e Folder">
                                        <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span class="truncate">Editar</span>
                                    </a>

                                    <!-- Excluir -->
                                    <form action="{{ route('bulletins.destroy', $item) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir permanentemente este folder?')" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full py-2 px-2 bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 rounded-lg text-xs font-bold transition flex items-center justify-center gap-1 text-center" title="Excluir Folder">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <span class="truncate">Excluir</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $bulletins->links() }}
                </div>
            @endif

        </div>
    </div>

    <!-- Hidden Offscreen Container for Generating WhatsApp Image on the Fly -->
    <div id="hidden-render-sandbox" style="position: fixed; left: -9999px; top: 0; width: 1080px; z-index: -100; pointer-events: none; opacity: 1;"></div>

    <script>
        function closeWhatsAppModal() {
            document.getElementById('whatsapp-desktop-modal').classList.add('hidden');
        }

        async function shareFolderToWhatsApp(id, renderUrl, slug) {
            const btn = document.getElementById(`btn-whatsapp-${id}`);
            const label = document.getElementById(`label-whatsapp-${id}`);
            const sandbox = document.getElementById('hidden-render-sandbox');

            btn.disabled = true;
            label.innerText = 'Gerando foto do folder...';

            try {
                // 1. Fetch the rendered HTML of the folder
                const response = await fetch(renderUrl, {
                    headers: { 'Accept': 'text/html' }
                });
                const html = await response.text();

                // 2. Put into sandbox
                sandbox.innerHTML = html;
                const flyerElement = sandbox.querySelector('#printable-flyer');

                if (!flyerElement) {
                    throw new Error('Elemento do folder não encontrado.');
                }

                // 3. Wait a moment for images to load if needed
                await new Promise(r => setTimeout(r, 400));

                // 4. Render to Canvas via html2canvas (scale 2 = crystal clear high resolution)
                const canvas = await html2canvas(flyerElement, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    windowWidth: 1200
                });

                // 5. Convert canvas to Blob
                const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
                const file = new File([blob], `folder-nova-alianca-${slug}.png`, { type: 'image/png' });

                // 6. Check Web Share API with file support (Mobile Chrome/Safari, Android, iOS)
                if (navigator.canShare && navigator.canShare({ files: [file] })) {
                    await navigator.share({
                        files: [file],
                        title: 'Folder Portal Nova Aliança',
                    });
                    label.innerText = '✓ Foto Compartilhada!';
                } else {
                    // Desktop WhatsApp Web Flow:
                    // A. Copy image directly to Clipboard so user can just Ctrl+V into WhatsApp chat!
                    try {
                        await navigator.clipboard.write([
                            new ClipboardItem({ 'image/png': blob })
                        ]);
                    } catch (clipErr) {
                        console.warn('Clipboard write error', clipErr);
                    }

                    // B. Also trigger download of the file for safety
                    const downloadLink = document.createElement('a');
                    downloadLink.href = canvas.toDataURL('image/png');
                    downloadLink.download = `folder-nova-alianca-${slug}.png`;
                    downloadLink.click();

                    // C. Open WhatsApp Web
                    window.open('https://web.whatsapp.com/', '_blank');

                    // D. Show clean guided modal
                    document.getElementById('whatsapp-desktop-modal').classList.remove('hidden');
                    label.innerText = 'Foto Copiada!';
                }

                setTimeout(() => {
                    label.innerText = 'Compartilhar Foto no WhatsApp';
                    btn.disabled = false;
                }, 3500);

            } catch (err) {
                console.error(err);
                alert('Erro ao preparar foto para WhatsApp: ' + err.message);
                label.innerText = 'Compartilhar Foto no WhatsApp';
                btn.disabled = false;
            } finally {
                sandbox.innerHTML = '';
            }
        }
    </script>
</x-app-layout>
