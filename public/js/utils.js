/**
 * js/utils.js
 * Kumpulan fungsi helper kecil untuk performa dan aksesibilitas.
 */

// 1. Debounce: Membatasi eksekusi fungsi yang sering dipanggil (seperti scroll/resize).
export const debounce = (func, wait = 20) => {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
};

// 2. Throttle: Memastikan fungsi hanya berjalan sekali setiap X milidetik.
export const throttle = (func, limit) => {
    let inThrottle;
    return function (...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => (inThrottle = false), limit);
        }
    };
};

// 3. Prefers Reduced Motion: Cek apakah user mematikan animasi di OS.
export const prefersReducedMotion = () => {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
};

// 4. Focus Trap: Mengunci fokus keyboard di dalam modal/menu.
export const trapFocus = (element) => {
    const focusableElements = element.querySelectorAll(
        'a[href], button, textarea, input, select, [tabindex]:not([tabindex="-1"])'
    );
    
    if (focusableElements.length === 0) return;

    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];

    element.addEventListener('keydown', function (e) {
        const isTabPressed = e.key === 'Tab' || e.keyCode === 9;

        if (!isTabPressed) return;

        if (e.shiftKey) {
            // Shift + Tab
            if (document.activeElement === firstElement) {
                lastElement.focus();
                e.preventDefault();
            }
        } else {
            // Tab
            if (document.activeElement === lastElement) {
                firstElement.focus();
                e.preventDefault();
            }
        }
    });
};

// 5. Inject CSS: Helper untuk memasukkan style dinamis tanpa edit file CSS.
export const injectStyles = (styles) => {
    const styleSheet = document.createElement("style");
    styleSheet.innerText = styles;
    document.head.appendChild(styleSheet);
};