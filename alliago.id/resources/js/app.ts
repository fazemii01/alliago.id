import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    // Mobile menu toggle
    const menuBtn = document.getElementById('menuBtn') as HTMLButtonElement | null;
    const mobileMenu = document.getElementById('mobileMenu') as HTMLDivElement | null;
    
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('open');
        });
    }

    // FAQ toggles
    document.querySelectorAll('.faq-toggle').forEach((button) => {
        button.addEventListener('click', (event) => {
            const target = event.currentTarget as HTMLButtonElement;
            const item = target.closest('.faq-item') as HTMLDivElement | null;
            if (item) {
                item.classList.toggle('open');
            }
        });
    });
});
