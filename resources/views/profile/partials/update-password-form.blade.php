<section>
    <header>
        <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">
            Alterar Senha
        </h2>

        <p class="mt-1 text-xs text-slate-500">
            Certifique-se de que sua conta esteja usando uma senha segura para proteger o acesso.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Senha Atual</label>
            <input id="update_password_current_password" name="current_password" type="password" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nova Senha</label>
            <input id="update_password_password" name="password" type="password" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Confirmar Nova Senha</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl text-xs shadow-md transition transform hover:-translate-y-0.5">
                Atualizar Senha
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-bold text-emerald-600 flex items-center gap-1"
                >
                    ✓ Senha alterada com sucesso!
                </p>
            @endif
        </div>
    </form>
</section>
