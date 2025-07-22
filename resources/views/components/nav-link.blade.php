@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-3 pt-1 border-b-2 border-primary text-base font-semibold leading-5 text-primary focus:outline-none focus:border-primary transition duration-150 ease-in-out bg-primary bg-opacity-10 rounded-top shadow-sm'
    : 'inline-flex items-center px-3 pt-1 border-b-2 border-transparent text-base font-medium leading-5 text-secondary hover:text-primary hover:border-primary focus:outline-none focus:text-primary focus:border-primary transition duration-150 ease-in-out bg-transparent rounded-top';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
