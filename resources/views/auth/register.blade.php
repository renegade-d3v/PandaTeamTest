<x-guest-layout>
    @section('title', 'Register')
    <div>
        <a href="/">
            <x-application-logo class="w-20 h-20 fill-current" />
        </a>
    </div>
    <h2 class="mx-2 my-3 md:my-6 text-center text-3xl font-extrabold text-white">{{ __('Створити обліковий запис') }}</h2>
    <div class="w-full sm:max-w-md px-6 py-4 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <form class="space-y-6 md:m-4" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Вже зареєстровані?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Зареєструватися') }}
            </x-primary-button>
        </div>
    </form>
    </div>
</x-guest-layout>
