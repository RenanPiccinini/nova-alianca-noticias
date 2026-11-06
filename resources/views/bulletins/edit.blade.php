<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span>Editar Folder</span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800">{{ $bulletin->formatted_date_pt }}</span>
                </h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Modifique as notícias, cores ou adicione novas matérias por link ou upload.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('bulletins.show', $bulletin) }}" class="px-3.5 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold rounded-lg text-sm transition">
                    👁️ Ver Layout
                </a>
                <a href="{{ route('bulletins.index') }}" class="px-4 py-2 border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 font-semibold rounded-lg text-sm shadow-xs transition">
                    ← Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('bulletins.partials.form', ['isEdit' => true])
        </div>
    </div>
</x-app-layout>
