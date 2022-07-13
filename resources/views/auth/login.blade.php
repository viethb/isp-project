<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">

        </x-slot>
        {{-- angepasster Code --}}
        <div id="login-container" class="start-page">
            <x-jet-validation-errors/>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-field-container">
                    <x-jet-label for="email" value="{{ __('Email') }}" />
                    <x-jet-input id="email" type="email" name="email" :value="old('email')" required autofocus />
                </div>

                <div class="form-field-container">
                    <x-jet-label for="password" value="{{ __('Password') }}" />
                    <x-jet-input id="password" type="password" name="password" required autocomplete="current-password" />
                </div>

                <div>

                    <x-jet-button class="button-large">
                        {{ __('Log in') }}
                    </x-jet-button>
                </div>
            </form>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>
