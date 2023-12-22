<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-center py-2 px-4 border-2 border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-white hover:border-red-600 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
