document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');

    if (!button || !menu) {
        return;
    }

    const showMenu = () => {
        menu.classList.remove('hidden');
        button.setAttribute('aria-expanded', 'true');
        menu.setAttribute('aria-hidden', 'false');
    };

    const hideMenu = () => {
        menu.classList.add('hidden');
        button.setAttribute('aria-expanded', 'false');
        menu.setAttribute('aria-hidden', 'true');
    };

    button.addEventListener('click', (event) => {
        event.stopPropagation();

        if (menu.classList.contains('hidden')) {
            showMenu();
            return;
        }

        hideMenu();
    });

    document.addEventListener('click', (event) => {
        if (!menu.classList.contains('hidden') && !menu.contains(event.target) && event.target !== button && !button.contains(event.target)) {
            hideMenu();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            hideMenu();
        }
    });
});
