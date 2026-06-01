function debounce(fn, wait) {
    let timer;

    return function debounced(...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), wait);
    };
}

export function initTypeaheadPicker(config) {
    const input = document.querySelector(config.inputSelector);
    const resultsBox = document.querySelector(config.resultsSelector);

    if (!input || !resultsBox) {
        return null;
    }

    const items = Array.isArray(config.items) ? config.items : [];
    const minChars = Number.isFinite(config.minChars) ? config.minChars : 1;
    const debounceMs = Number.isFinite(config.debounceMs) ? config.debounceMs : 180;
    const filterItems = config.filterItems || ((list, query) => list.filter((item) => {
        const name = (item.nombre || item.title || '').toLowerCase();
        const alias = (item.licencia || item.subtitle || '').toLowerCase();
        return name.includes(query) || alias.includes(query);
    }));
    const renderItem = config.renderItem || ((item, index, activeIndex) => {
        const isActive = index === activeIndex;
        const activeClass = isActive ? 'bg-cyan-300/15 ring-1 ring-cyan-300/40' : 'hover:bg-white/5';
        return `<div class="p-2 rounded cursor-pointer transition ${activeClass}" data-index="${index}" role="option" aria-selected="${isActive ? 'true' : 'false'}">
                    <div class="text-sm ${isActive ? 'text-cyan-100' : ''}">${item.nombre || item.title || 'Sin nombre'}</div>
                    <div class="text-xs text-white/55">${item.licencia || item.subtitle || ''}</div>
                </div>`;
    });
    const onSelect = typeof config.onSelect === 'function' ? config.onSelect : () => {};

    let currentResults = [];
    let activeIndex = -1;

    function syncResults() {
        if (currentResults.length === 0) {
            resultsBox.innerHTML = '<div class="p-2 text-sm text-white/60">No se encontraron resultados.</div>';
            resultsBox.classList.remove('hidden');
            return;
        }

        resultsBox.innerHTML = currentResults.map((item, index) => renderItem(item, index, activeIndex)).join('');
        resultsBox.classList.remove('hidden');
    }

    function selectResult(index) {
        if (index < 0 || index >= currentResults.length) {
            return;
        }

        const selected = currentResults[index];
        if (!selected) {
            return;
        }

        onSelect(selected, {
            input,
            resultsBox,
            clear() {
                input.value = '';
                currentResults = [];
                activeIndex = -1;
                resultsBox.classList.add('hidden');
                resultsBox.innerHTML = '';
            },
            hide() {
                resultsBox.classList.add('hidden');
            },
        });
    }

    const runSearch = debounce(function runSearch() {
        const query = input.value.trim().toLowerCase();

        if (query.length < minChars) {
            currentResults = [];
            activeIndex = -1;
            resultsBox.classList.add('hidden');
            resultsBox.innerHTML = '';
            return;
        }

        currentResults = filterItems(items, query);
        activeIndex = currentResults.length > 0 ? 0 : -1;
        syncResults();
    }, debounceMs);

    input.addEventListener('input', runSearch);

    input.addEventListener('keydown', function onKeydown(event) {
        if (resultsBox.classList.contains('hidden') || currentResults.length === 0) {
            return;
        }

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            activeIndex = Math.min(activeIndex + 1, currentResults.length - 1);
            syncResults();
            return;
        }

        if (event.key === 'ArrowUp') {
            event.preventDefault();
            activeIndex = Math.max(activeIndex - 1, 0);
            syncResults();
            return;
        }

        if (event.key === 'Enter') {
            event.preventDefault();
            selectResult(activeIndex);
            return;
        }

        if (event.key === 'Escape') {
            resultsBox.classList.add('hidden');
        }
    });

    resultsBox.addEventListener('click', function onClick(event) {
        const item = event.target.closest('[data-index]');
        if (!item) {
            return;
        }

        const index = Number(item.getAttribute('data-index'));
        if (Number.isNaN(index)) {
            return;
        }

        selectResult(index);
    });

    return {
        updateItems(nextItems) {
            if (Array.isArray(nextItems)) {
                items.splice(0, items.length, ...nextItems);
            }
        },
        clear() {
            currentResults = [];
            activeIndex = -1;
            resultsBox.classList.add('hidden');
            resultsBox.innerHTML = '';
            input.value = '';
        },
        destroy() {
            input.removeEventListener('input', runSearch);
        },
    };
}

window.initTypeaheadPicker = initTypeaheadPicker;