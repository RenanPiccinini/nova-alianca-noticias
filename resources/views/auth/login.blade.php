<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">E-mail</label>
            <input id="email" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600" type="email" name="email" value="{{ old('email', 'admin@novaalianca.com') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Senha</label>
            <input id="password" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600"
                            type="password"
                            name="password"
                            value="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-xs">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-xs focus:ring-blue-500" name="remember" checked>
                <span class="ms-2 text-slate-600">Lembrar de mim</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-blue-700 hover:text-blue-900 font-semibold" href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl text-sm shadow-md transition transform hover:-translate-y-0.5">
                Acessar Painel
            </button>
        </div>

        <!-- Demo Credentials Hint -->
        <div class="mt-4 p-3 bg-blue-50 rounded-xl border border-blue-100 text-center">
            <span class="block text-[11px] font-bold text-blue-900">Acesso de Demonstração Liberado</span>
            <span class="block text-[10px] text-blue-700 mt-0.5">admin@novaalianca.com / password</span>
        </div>

        <div class="text-center pt-2">
            <span class="text-xs text-slate-500">Não tem uma conta?</span>
            <a href="{{ route('register') }}" class="text-xs font-bold text-blue-700 hover:underline ms-1">Cadastre-se</a>
        </div>
    </form>
</x-guest-layout>
