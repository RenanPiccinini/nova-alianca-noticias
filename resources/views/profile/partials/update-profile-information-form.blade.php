<section>
    <header>
        <h2 class="text-base font-black text-slate-900 uppercase tracking-wide">
            Informações do Perfil
        </h2>

        <p class="mt-1 text-xs text-slate-500">
            Atualize o nome e o endereço de e-mail da sua conta de acesso.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nome Completo</label>
            <input id="name" name="name" type="text" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Endereço de E-mail</label>
            <input id="email" name="email" type="email" class="block w-full rounded-xl border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 bg-amber-50 rounded-xl border border-amber-200">
                    <p class="text-xs text-amber-800">
                        Seu endereço de e-mail ainda não foi confirmado.

                        <button form="send-verification" class="underline text-xs text-amber-900 font-bold hover:text-amber-950 block mt-1">
                            Clique aqui para reenviar o e-mail de confirmação.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-xs text-emerald-700">
                            ✓ Um novo link de confirmação foi enviado para o seu e-mail.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl text-xs shadow-md transition transform hover:-translate-y-0.5">
                Salvar Alterações
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-bold text-emerald-600 flex items-center gap-1"
                >
                    ✓ Alterações salvas com sucesso!
                </p>
            @endif
        </div>
    </form>
</section>
