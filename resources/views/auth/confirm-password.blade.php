<x-guest-layout>
    <div class="mb-4 text-xs text-slate-600 leading-relaxed">
        Esta é uma área restrita e segura da aplicação. Por favor, confirme sua senha atual antes de continuar.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Sua Senha</label>
            <input id="password" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl text-sm shadow-md transition transform hover:-translate-y-0.5">
                Confirmar Senha
            </button>
        </div>
    </form>
</x-guest-layout>
