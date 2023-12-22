@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-red-800 text-xs italic dark:text-red-400 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
