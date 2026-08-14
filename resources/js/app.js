import './bootstrap';

const root = document.documentElement;
const storageKey = 'color-theme';
const sidebarStorageKey = 'admin-sidebar';

const applyTheme = (theme) => {
    const resolvedTheme = theme === 'dark' ? 'dark' : 'light';

    root.classList.toggle('dark', resolvedTheme === 'dark');
    root.dataset.theme = resolvedTheme;
};

const resolvePreferredTheme = () => {
    const savedTheme = localStorage.getItem(storageKey);

    if (savedTheme === 'light' || savedTheme === 'dark') {
        return savedTheme;
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

const applySidebarState = (state) => {
    const resolvedState = state === 'collapsed' ? 'collapsed' : 'expanded';

    root.dataset.sidebarState = resolvedState;
};

const resolveSidebarState = () => {
    const savedState = localStorage.getItem(sidebarStorageKey);

    return savedState === 'collapsed' ? 'collapsed' : 'expanded';
};

applyTheme(resolvePreferredTheme());
applySidebarState(resolveSidebarState());

document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-theme-toggle]');

    if (trigger) {
        const nextTheme = root.classList.contains('dark') ? 'light' : 'dark';

        localStorage.setItem(storageKey, nextTheme);
        applyTheme(nextTheme);
    }
});
