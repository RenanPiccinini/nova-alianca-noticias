<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span>Novo Folder Informativo</span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800">Portal Nova Aliança</span>
                </h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Informe as notícias do dia ou simplesmente cole links para preencher fotos e descrições automaticamente.
                </p>
            </div>
            <a href="{{ route('bulletins.index') }}" class="px-4 py-2 border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 font-semibold rounded-lg text-sm shadow-xs transition">
                ← Voltar aos Folders
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('bulletins.partials.form', ['isEdit' => false])
        </div>
    </div>
</x-app-layout>
