<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nome Completo</label>
            <input id="name" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">E-mail</label>
            <input id="email" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Senha</label>
            <input id="password" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Confirmar Senha</label>
            <input id="password_confirmation" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl text-sm shadow-md transition transform hover:-translate-y-0.5">
                Criar Minha Conta
            </button>
        </div>

        <div class="text-center pt-2">
            <span class="text-xs text-slate-500">Já tem cadastro?</span>
            <a href="{{ route('login') }}" class="text-xs font-bold text-blue-700 hover:underline ms-1">Entrar</a>
        </div>
    </form>
</x-guest-layout>
