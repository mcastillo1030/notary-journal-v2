export const loadTheme = () => {
    if (
        localStorage.theme === 'dark' ||
        (!('theme' in localStorage) &&
            window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        document.documentElement.classList.add('nj-dark');
    } else {
        document.documentElement.classList.remove('nj-dark');
    }
};

export const setTheme = (newTheme) => {
    if (newTheme !== 'light' && newTheme !== 'dark' && newTheme !== 'system') {
        return;
    }

    if (newTheme === 'system') {
        localStorage.removeItem('theme');
    } else {
        localStorage.theme = newTheme;
    }

    loadTheme();
};
