@php
    $title = $title ?? config('app.name', 'AeroControl');
    $subtitle = $subtitle ?? '';
    $label = $label ?? null;
    $bgClass = $bgClass ?? 'bg-blue-900/70';
@endphp

<section class="w-full {{ $bgClass }} border-b border-white/6">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        @if($label)
            <p class="section-label">{{ $label }}</p>
        @endif
        <div class="mt-2">
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">{{ $title }}</h1>
            @if($subtitle)
                <p class="mt-3 max-w-2xl text-base leading-7 text-white/70 sm:text-lg">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</section>
