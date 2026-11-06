<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-black px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 uppercase tracking-wide">
                        {{ $bulletin->formatted_date_pt }}
                    </span>
                    <span class="text-xs font-bold text-slate-500">
                        {{ $bulletin->day_of_week_pt }} • {{ $bulletin->edition_time }}
                    </span>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-1">
                    {{ $bulletin->title }} {{ $bulletin->sub_title }}
                </h1>
            </div>

            <!-- Action Toolbar -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Share Photo directly to WhatsApp -->
                <button type="button" onclick="shareToWhatsAppDirect()" id="btn-share-whatsapp" class="px-4 py-2.5 bg-[#25D366] hover:bg-[#20ba59] text-white font-black rounded-xl text-sm shadow-md transition flex items-center gap-2 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/>
                    </svg>
                    <span id="btn-share-whatsapp-text">WhatsApp (Foto)</span>
                </button>

                <!-- Download High-Res PNG -->
                <button type="button" id="btn-download-image" onclick="exportFlyerImage()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-xl text-sm shadow-md transition flex items-center gap-2 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span id="btn-download-text">Baixar Imagem (PNG)</span>
                </button>

                <!-- Print / PDF -->
                <button type="button" onclick="window.print()" class="px-3.5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-sm shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Imprimir / PDF</span>
                </button>

                <!-- Copy Public Link -->
                <button type="button" onclick="copyPublicLink('{{ route('bulletins.public', $bulletin->slug) }}')" class="px-3.5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-sm shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                    </svg>
                    <span>Link Público</span>
                </button>

                <!-- Edit -->
                <a href="{{ route('bulletins.edit', $bulletin) }}" class="px-3.5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl text-sm shadow-xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Editar</span>
                </a>

                <!-- Back -->
                <a href="{{ route('bulletins.index') }}" class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition">
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Success notification -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Zoom & View controls -->
            <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between text-xs font-semibold text-slate-600">
                <div class="flex items-center gap-3">
                    <span>Visualização do Folder (Resolução Original: 1080 x 1080 px)</span>
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-500 font-mono">1:1 Quadrado Social</span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="setZoom(0.75)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 rounded">75%</button>
                    <button type="button" onclick="setZoom(0.85)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 rounded">85%</button>
                    <button type="button" onclick="setZoom(1)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 rounded">100%</button>
                    <button type="button" onclick="setZoom('auto')" class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded font-bold">Ajustar à Tela</button>
                </div>
            </div>

            <!-- FLYER CONTAINER WRAPPER WITH SCALING -->
            <div id="flyer-zoom-wrapper" class="w-full flex justify-center overflow-x-auto py-4">
                <div id="flyer-scaler" style="transform-origin: top center; transition: transform 0.2s ease;">
                    @include('bulletins.components.folder-render', ['bulletin' => $bulletin])
                </div>
            </div>

        </div>
    </div>

    <!-- EXPORT SCRIPT -->
    <script>
        let currentScale = 0.85;

        function setZoom(val) {
            const el = document.getElementById('flyer-scaler');
            if (val === 'auto') {
                const containerWidth = document.getElementById('flyer-zoom-wrapper').clientWidth - 40;
                val = Math.min(1, Math.max(0.4, containerWidth / 1080));
            }
            currentScale = val;
            el.style.transform = `scale(${val})`;
        }

        // Auto adjust on load
        window.addEventListener('DOMContentLoaded', () => {
            setZoom('auto');
        });
        window.addEventListener('resize', () => {
            setZoom('auto');
        });

        async function shareToWhatsAppDirect() {
            const flyer = document.getElementById('printable-flyer');
            const btn = document.getElementById('btn-share-whatsapp');
            const btnText = document.getElementById('btn-share-whatsapp-text');
            const scaler = document.getElementById('flyer-scaler');
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

            const oldTransform = scaler.style.transform;
            scaler.style.transform = 'none';

            btn.disabled = true;
            const originalText = btnText.innerText;
            btnText.innerText = 'Preparando foto...';

            try {
                const canvas = await html2canvas(flyer, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    windowWidth: 1200
                });

                const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
                const file = new File([blob], `folder-nova-alianca-${@json($bulletin->slug)}.png`, { type: 'image/png' });
                const dataUrl = canvas.toDataURL('image/png');

                currentShareFile = file;
                currentShareBlob = blob;
                currentShareSlug = @json($bulletin->slug);
                currentShareDataUrl = dataUrl;

                if (isMobile) {
                    let sharedDirectly = false;
                    if (navigator.canShare && navigator.canShare({ files: [file] })) {
                        try {
                            await navigator.share({
                                files: [file],
                                title: 'Folder Portal Nova Aliança',
                            });
                            sharedDirectly = true;
                            btnText.innerText = '✓ Foto Enviada!';
                        } catch (shareErr) {
                            if (shareErr.name === 'AbortError') {
                                sharedDirectly = true;
                            } else {
                                console.info('Direct share timed out, presenting 1-tap modal:', shareErr);
                            }
                        }
                    }

                    if (!sharedDirectly) {
                        const previewImg = document.getElementById('whatsapp-mobile-preview');
                        if (previewImg) previewImg.src = dataUrl;
                        document.getElementById('whatsapp-mobile-modal').classList.remove('hidden');
                        btnText.innerText = 'Pronto para Enviar!';
                    }
                } else {
                    // Desktop WhatsApp Web fallback
                    try {
                        await navigator.clipboard.write([
                            new ClipboardItem({ 'image/png': blob })
                        ]);
                    } catch (clipErr) {
                        console.warn(clipErr);
                    }

                    // Auto download photo
                    const link = document.createElement('a');
                    link.download = `folder-nova-alianca-${@json($bulletin->slug)}.png`;
                    link.href = dataUrl;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    window.open('https://web.whatsapp.com/', '_blank');
                    alert('Foto do folder copiada com sucesso!\n\nNo WhatsApp que abriu, basta colar (Ctrl + V) na conversa para enviar somente a foto!');
                    btnText.innerText = '✓ Foto Copiada!';
                }

                setTimeout(() => {
                    btnText.innerText = originalText;
                    btn.disabled = false;
                }, 3500);
            } catch (err) {
                alert('Erro ao preparar imagem: ' + err.message);
                btnText.innerText = originalText;
                btn.disabled = false;
            } finally {
                scaler.style.transform = oldTransform;
            }
        }

        async function exportFlyerImage() {
            const flyer = document.getElementById('printable-flyer');
            const btn = document.getElementById('btn-download-image');
            const btnText = document.getElementById('btn-download-text');
            const scaler = document.getElementById('flyer-scaler');

            // Save old transform
            const oldTransform = scaler.style.transform;
            scaler.style.transform = 'none';

            btn.disabled = true;
            btnText.innerText = 'Gerando Alta Resolução...';

            try {
                // html2canvas high-res render (2x retina)
                const canvas = await html2canvas(flyer, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    windowWidth: 1200
                });

                const link = document.createElement('a');
                link.download = `boletim-nova-alianca-${@json($bulletin->slug)}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();

                btnText.innerText = '✓ Download Concluído!';
                setTimeout(() => {
                    btnText.innerText = 'Baixar Imagem (PNG)';
                    btn.disabled = false;
                }, 3000);
            } catch (err) {
                alert('Erro ao exportar imagem: ' + err.message);
                btnText.innerText = 'Baixar Imagem (PNG)';
                btn.disabled = false;
            } finally {
                scaler.style.transform = oldTransform;
            }
        }

        function copyPublicLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                alert('Link público copiado com sucesso:\n' + url);
            }).catch(() => {
                prompt('Copie o link público abaixo:', url);
            });
        }

        let currentShareFile = null;
        let currentShareBlob = null;
        let currentShareSlug = '';
        let currentShareDataUrl = '';

        function closeWhatsAppMobileModal() {
            document.getElementById('whatsapp-mobile-modal').classList.add('hidden');
        }

        function downloadCurrentFlyer() {
            if (!currentShareDataUrl) return;
            const a = document.createElement('a');
            a.href = currentShareDataUrl;
            a.download = `folder-nova-alianca-${currentShareSlug || 'noticias'}.png`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const mobileBtn = document.getElementById('whatsapp-mobile-share-btn');
            if (mobileBtn) {
                mobileBtn.addEventListener('click', async () => {
                    if (!currentShareFile) return;

                    if (navigator.canShare && navigator.canShare({ files: [currentShareFile] })) {
                        try {
                            await navigator.share({
                                files: [currentShareFile],
                                title: 'Folder Portal Nova Aliança',
                            });
                            closeWhatsAppMobileModal();
                            return;
                        } catch (e) {
                            if (e.name === 'AbortError') return;
                            console.warn('Share error on tap, falling back to download:', e);
                        }
                    }

                    downloadCurrentFlyer();
                    window.location.href = 'whatsapp://send';
                    closeWhatsAppMobileModal();
                });
            }

            const downloadBtn = document.getElementById('whatsapp-mobile-download-btn');
            if (downloadBtn) {
                downloadBtn.addEventListener('click', () => {
                    downloadCurrentFlyer();
                    setTimeout(() => {
                        window.location.href = 'whatsapp://send';
                        closeWhatsAppMobileModal();
                    }, 400);
                });
            }
        });
    </script>

    <!-- Modal Feedback WhatsApp Mobile (1-Tap Share) -->
    <div id="whatsapp-mobile-modal" class="hidden fixed inset-0 z-[9999] bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#25D366]"></span>
                    <h3 class="text-base font-black text-slate-900">Foto do Folder Pronta!</h3>
                </div>
                <button type="button" onclick="closeWhatsAppMobileModal()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-sm flex items-center justify-center transition">✕</button>
            </div>

            <div class="w-full h-48 bg-slate-50 rounded-2xl overflow-hidden mb-3 border border-slate-200 shadow-inner flex items-center justify-center">
                <img id="whatsapp-mobile-preview" class="w-full h-full object-contain" alt="Folder Nova Aliança">
            </div>

            <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                Toque no botão verde abaixo para <strong>abrir o WhatsApp e enviar diretamente</strong> a foto:
            </p>

            <button type="button" 
                    id="whatsapp-mobile-share-btn" 
                    class="w-full py-3 px-4 bg-[#25D366] hover:bg-[#20ba59] active:scale-95 text-white font-black rounded-2xl text-sm shadow-md transition flex items-center justify-center gap-2 mb-2.5 cursor-pointer">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/>
                </svg>
                <span>Enviar Foto no WhatsApp</span>
            </button>

            <button type="button" 
                    id="whatsapp-mobile-download-btn" 
                    class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-bold rounded-2xl text-xs transition flex items-center justify-center gap-1.5 mb-2 cursor-pointer">
                <span>⬇️ Salvar Imagem na Galeria</span>
            </button>

            <button type="button" onclick="closeWhatsAppMobileModal()" class="text-xs text-slate-400 hover:text-slate-600 font-semibold py-1">
                Fechar
            </button>
        </div>
    </div>
    </script>
</x-app-layout>

