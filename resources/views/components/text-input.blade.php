@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border border-gray-300 rounded shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-red-600 dark:focus:border-red-600']) !!}>
