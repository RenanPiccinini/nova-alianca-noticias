<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $bulletin->title }} {{ $bulletin->sub_title }} - {{ $bulletin->formatted_date_pt }} | Portal Nova Aliança</title>

    <meta property="og:title" content="{{ $bulletin->title }} {{ $bulletin->sub_title }} - {{ $bulletin->formatted_date_pt }}">
    <meta property="og:description" content="Confira o boletim informativo do Portal Nova Aliança com os principais acontecimentos do dia.">
    <meta property="og:type" content="article">
    <meta property="og:image" content="{{ asset('images/logo-nova-alianca.jpg') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-between selection:bg-red-600 selection:text-white">

    <!-- Top Public Navbar -->
    <header class="bg-[#071e3d] border-b border-blue-900 px-4 py-3 sticky top-0 z-50 shadow-md">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-nova-alianca-round.jpg') }}" alt="Logo" class="w-10 h-10 rounded-full border border-blue-400">
                <div>
                    <span class="block text-[10px] font-black uppercase text-red-500 tracking-wider">Portal de Notícias</span>
                    <span class="block text-sm font-black uppercase text-white leading-tight">Nova Aliança</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" onclick="exportPublicFlyer()" id="btn-pub-download" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs shadow-sm transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span>Baixar Folder</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Flyer Center -->
    <main class="flex-1 py-8 px-4 flex flex-col items-center justify-center overflow-x-auto">
        <div id="public-zoom-wrapper" class="w-full flex justify-center">
            <div id="public-flyer-scaler" style="transform-origin: top center;">
                @include('bulletins.components.folder-render', ['bulletin' => $bulletin, 'isPublic' => true])
            </div>
        </div>
    </main>

    <!-- Public Footer -->
    <footer class="bg-[#051329] border-t border-blue-950/80 py-4 px-4 text-center text-xs text-slate-400">
        <p class="font-bold text-white">PORTAL DE NOTÍCIAS NOVA ALIANÇA &copy; {{ date('Y') }}</p>
        <p class="text-[11px] text-slate-400 mt-0.5">Aconteceu virou notícia. 24 horas no ar.</p>
    </footer>

    <script>
        function adjustPublicScale() {
            const containerWidth = window.innerWidth - 32;
            const targetWidth = 1080;
            const scale = Math.min(1, Math.max(0.32, containerWidth / targetWidth));
            const el = document.getElementById('public-flyer-scaler');
            if (el) {
                el.style.transform = `scale(${scale})`;
            }
        }

        window.addEventListener('DOMContentLoaded', adjustPublicScale);
        window.addEventListener('resize', adjustPublicScale);

        async function exportPublicFlyer() {
            const flyer = document.getElementById('printable-flyer');
            const btn = document.getElementById('btn-pub-download');
            const scaler = document.getElementById('public-flyer-scaler');

            const oldTransform = scaler.style.transform;
            scaler.style.transform = 'none';
            btn.innerText = 'Gerando...';

            try {
                const canvas = await html2canvas(flyer, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff'
                });

                const link = document.createElement('a');
                link.download = `boletim-nova-alianca-${@json($bulletin->slug)}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
                btn.innerText = '✓ Baixado!';
                setTimeout(() => { btn.innerText = 'Baixar Folder'; }, 3000);
            } catch (e) {
                alert('Erro ao baixar: ' + e.message);
                btn.innerText = 'Baixar Folder';
            } finally {
                scaler.style.transform = oldTransform;
            }
        }
    </script>
</body>
</html>
