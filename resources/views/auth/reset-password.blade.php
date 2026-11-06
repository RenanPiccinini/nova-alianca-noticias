<x-guest-layout>
    <div class="mb-4 text-xs text-slate-600 leading-relaxed">
        Defina uma nova senha de acesso segura para a sua conta.
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">E-mail</label>
            <input id="email" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nova Senha</label>
            <input id="password" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Confirmar Nova Senha</label>
            <input id="password_confirmation" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl text-sm shadow-md transition transform hover:-translate-y-0.5">
                Redefinir Senha
            </button>
        </div>
    </form>
</x-guest-layout>
