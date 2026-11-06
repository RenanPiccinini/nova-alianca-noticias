<x-guest-layout>
    <div class="mb-4 text-xs text-slate-600 leading-relaxed">
        Esqueceu sua senha? Sem problemas. Basta informar o seu endereço de e-mail cadastrado que nós enviaremos um link de recuperação para você redefinir sua senha com facilidade.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">E-mail Cadastrado</label>
            <input id="email" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600" type="email" name="email" value="{{ old('email') }}" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2 flex items-center justify-between">
            <a href="{{ route('login') }}" class="text-xs font-bold text-blue-700 hover:underline">
                ← Voltar ao Login
            </a>

            <button type="submit" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl text-xs shadow-md transition transform hover:-translate-y-0.5">
                Enviar Link de Recuperação
            </button>
        </div>
    </form>
</x-guest-layout>
