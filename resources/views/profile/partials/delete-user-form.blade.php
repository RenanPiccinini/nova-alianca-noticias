<section class="space-y-6">
    <header>
        <h2 class="text-base font-black text-rose-700 uppercase tracking-wide">
            Excluir Conta
        </h2>

        <p class="mt-1 text-xs text-slate-500">
            Ao excluir sua conta, todos os recursos e dados vinculados serão permanentemente apagados. Por favor, tenha certeza antes de prosseguir.
        </p>
    </header>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs shadow-sm transition"
    >
        Excluir Minha Conta
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-base font-black text-slate-900">
                Tem certeza de que deseja excluir sua conta?
            </h2>

            <p class="mt-2 text-xs text-slate-600 leading-relaxed">
                Esta ação é irreversível e todos os dados serão permanentemente apagados. Por favor, digite sua senha para confirmar a exclusão definitiva.
            </p>

            <div class="mt-4">
                <label for="password" class="sr-only">Senha</label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full rounded-xl border-slate-300 text-sm focus:border-rose-500 focus:ring-rose-500"
                    placeholder="Digite sua senha para confirmar"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl transition">
                    Cancelar
                </button>

                <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-black rounded-xl text-xs shadow-md transition">
                    Confirmar e Excluir Conta
                </button>
            </div>
        </form>
    </x-modal>
</section>
