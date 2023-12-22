<x-guest-layout>
    @section('title', 'Login')
    <div>
        <a href="/">
            <x-application-logo class="w-20 h-20 fill-current" />
        </a>
    </div>
    <h2 class="mx-2 my-3 md:my-6 text-center text-3xl font-extrabold text-white">{{ __('Вхід до облікового запису') }}</h2>
    <div class="w-full sm:max-w-md px-6 py-4 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <form class="space-y-6 md:m-4" method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Email Address -->
            <div>
                <x-input-label for="login" :value="__('Login')" />
                <x-text-input id="login" class="block mt-1 w-full" type="text" name="email" :value="old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex flex-wrap items-center justify-between">
                <!-- Remember Me -->
                <div class="flex items-center">
                    <x-text-input id="remember_me" class="h-4 w-4 text-red-500 border-gray-300"
                                  type="checkbox"
                                  name="remember"/>

                    <x-input-label class="ml-2 block text-sm text-gray-900" for="remember_me" :value="__('Запам\'ятати мене')" />
                </div>
                @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="underline font-medium text-red-500 hover:text-red-600 focus:ring-red-600">
                            {{ __('Забули пароль?') }}
                        </a>
                    </div>
                @endif
            </div>

            <x-primary-button class="w-full">
                {{ __('Увійти') }}
            </x-primary-button>
        </form>
    </div>
</x-guest-layout>
