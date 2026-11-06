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
    </script>
</x-app-layout>
