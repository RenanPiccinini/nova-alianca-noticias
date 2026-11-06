<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="text-blue-700">Folders & Boletins</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">Nova Aliança</span>
                </h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Gerencie, crie e exporte os folders informativos diários do Portal de Notícias Nova Aliança.
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
                <!-- Grid of Bulletins -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($bulletins as $item)
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col justify-between">
                            <!-- Card Header -->
                            <div class="p-5 border-b border-slate-100">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <span class="inline-flex items-center gap-1 text-[11px] font-black uppercase tracking-wider text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md">
                                            📅 {{ $item->formatted_date_pt }}
                                        </span>
                                        <h3 class="text-lg font-black text-slate-900 mt-2 leading-tight">
                                            {{ $item->title }} {{ $item->sub_title }}
                                        </h3>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            {{ $item->day_of_week_pt }} • {{ $item->edition_time ?? 'Edição Geral' }}
                                        </p>
                                    </div>
                                    <div class="w-12 h-12 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0">
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

                            <!-- Actions Footer -->
                            <div class="p-4 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between gap-2">
                                <a href="{{ route('bulletins.show', $item) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-xs font-bold transition shadow-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span>Ver / Baixar</span>
                                </a>

                                <div class="flex items-center gap-1">
                                    <!-- Edit -->
                                    <a href="{{ route('bulletins.edit', $item) }}" class="p-1.5 text-slate-600 hover:text-blue-700 hover:bg-white rounded-md border border-transparent hover:border-slate-200 transition" title="Editar Folder">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <!-- Duplicate -->
                                    <form action="{{ route('bulletins.duplicate', $item) }}" method="POST" onsubmit="return confirm('Deseja duplicar este folder para criar uma nova edição?')">
                                        @csrf
                                        <button type="submit" class="p-1.5 text-slate-600 hover:text-emerald-700 hover:bg-white rounded-md border border-transparent hover:border-slate-200 transition" title="Duplicar para Nova Edição">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                            </svg>
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form action="{{ route('bulletins.destroy', $item) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este folder?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-white rounded-md border border-transparent hover:border-slate-200 transition" title="Excluir">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
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
</x-app-layout>
