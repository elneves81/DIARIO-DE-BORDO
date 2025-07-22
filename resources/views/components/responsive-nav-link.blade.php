@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full pl-3 pr-4 py-2 border-l-4 border-primary text-left text-base font-semibold text-primary bg-primary bg-opacity-10 focus:outline-none focus:text-primary focus:bg-primary focus:bg-opacity-20 focus:border-primary transition duration-150 ease-in-out rounded-end shadow-sm'
    : 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-secondary hover:text-primary hover:bg-primary hover:bg-opacity-10 hover:border-primary focus:outline-none focus:text-primary focus:bg-primary focus:bg-opacity-10 focus:border-primary transition duration-150 ease-in-out rounded-end';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
