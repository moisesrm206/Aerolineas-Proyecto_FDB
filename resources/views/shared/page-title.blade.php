@php
    $label = $label ?? null;
    $title = $title ?? null;
    $subtitle = $subtitle ?? null;
@endphp

<div>
    @if($label)
        <p class="section-label">{{ $label }}</p>
    @endif

    @if($title)
        <h1 class="mt-4 text-4xl font-bold tracking-tight text-white sm:text-5xl">{{ $title }}</h1>
    @endif

    @if($subtitle)
        <p class="mt-4 max-w-2xl text-base leading-7 text-white/70 sm:text-lg">{{ $subtitle }}</p>
    @endif
</div>
