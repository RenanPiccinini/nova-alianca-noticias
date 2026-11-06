<x-guest-layout>
    <div class="mb-4 text-xs text-slate-600 leading-relaxed">
        Obrigado por se cadastrar! Antes de começar, por favor confirme seu endereço de e-mail clicando no link que acabamos de enviar. Se você não recebeu a mensagem, podemos enviar outro e-mail para você.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-bold text-xs text-emerald-700 bg-emerald-50 p-3 rounded-xl border border-emerald-200">
            ✓ Um novo link de confirmação foi enviado para o endereço de e-mail informado no cadastro.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <button type="submit" class="px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl text-xs shadow-md transition">
                    Reenviar E-mail de Confirmação
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-xs text-slate-600 hover:text-slate-900 font-semibold">
                Sair da Conta
            </button>
        </form>
    </div>
</x-guest-layout>
