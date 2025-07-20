@props([
    'route',       // nama route (string)
    'label',       // label menu
    'icon' => '',  // svg icon
])

@php
    $isActive = request()->routeIs($route);
@endphp

<li class="relative px-6 py-3">
    @if($isActive)
        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
    @endif

    <a
        href="{{ route($route) }}"
        class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 {{ $isActive ? 'dark:text-gray-100 text-gray-800' : '' }}"
    >
        {!! $icon !!}
        <span class="ml-4">{{ $label }}</span>
    </a>
</li>
