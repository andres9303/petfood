@props(['active', 'icon'])

@php
$classes = ($active ?? false)
            ? 'block no-underline px-0 py-4 bg-alternative-500 text-contrast-alternative-500 font-normal'
            : 'block no-underline px-0 py-4 text-contrast-primary-800 font-normal hover:bg-alternative-500 hover:text-contrast-alternative-500';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <i class="{{ $icon }} mr-2"></i>
    {{ $slot }}
</a>
