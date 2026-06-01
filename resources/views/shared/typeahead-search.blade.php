@php
    $fieldLabel = $label ?? null;
    $fieldId = $searchId ?? ($name ?? null);
    $fieldName = $name ?? $fieldId;
    $fieldValue = $value ?? '';
    $fieldType = $type ?? 'text';
    $fieldPlaceholder = $placeholder ?? '';
    $fieldIcon = $icon ?? null;
    $fieldResultsId = $resultsId ?? null;
    $showResults = $showResults ?? true;
@endphp

<div>
    @if($fieldLabel)
        <label for="{{ $fieldId }}" class="mb-2 block text-sm font-medium text-white/80">{{ $fieldLabel }}</label>
    @endif

    <div class="relative">
        @if($fieldIcon)
            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-white/45">
                <ion-icon name="{{ $fieldIcon }}"></ion-icon>
            </span>
        @endif

        <input
            id="{{ $fieldId }}"
            name="{{ $fieldName }}"
            type="{{ $fieldType }}"
            value="{{ $fieldValue }}"
            placeholder="{{ $fieldPlaceholder }}"
            class="w-full rounded-2xl border border-white/10 bg-white/5 py-3 px-4 text-white placeholder:text-white/35 focus:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-300/30 {{ $fieldIcon ? 'pl-11' : '' }}"
        >
    </div>

    @if($showResults && $fieldResultsId)
        <div id="{{ $fieldResultsId }}" class="mt-2 max-h-44 overflow-auto hidden rounded bg-white/5 p-2 text-white/80"></div>
    @endif
</div>