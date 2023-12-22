<x-guest-layout>
    @section('title', 'Forgot Password')
    <div class="my-6">
        <a href="/">
            <x-application-logo class="w-20 h-20 fill-current" />
        </a>
    </div>
    <div class="w-full sm:max-w-md px-6 py-4 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <form class="space-y-6 md:m-4" method="POST" action="{{ route('password.email') }}">
            @csrf
            <p class="text-justify">
                {{ __('Забули свій пароль? Без проблем. Просто повідомте нам свою адресу електронної пошти, і ми надішлемо вам посилання для зміни пароля, за яким ви зможете встановити новий.') }}
            </p>
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')" autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex flex-wrap space-y-6 items-center mt-4">
                <x-primary-button class="w-full">
                    {{ __('Відновити') }}
                </x-primary-button>
                <a href="javascript:history.back()" class="inline-flex justify-center shadow-sm text-sm font-medium text-red-600 w-full border-2 border-red-600 rounded-md py-2 px-4 transition ease-in-out duration-150 hover:bg-red-600 hover:text-white">
                    {{ __('Назад') }}
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
