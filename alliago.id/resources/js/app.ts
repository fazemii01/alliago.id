import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    // Mobile menu toggle
    const menuBtn = document.getElementById('menuBtn') as HTMLButtonElement | null;
    const mobileMenu = document.getElementById('mobileMenu') as HTMLDivElement | null;
    
    if (menuBtn && mobileMenu) {
        const syncMobileMenuState = (isOpen: boolean) => {
            mobileMenu.classList.toggle('is-open', isOpen);
            menuBtn.classList.toggle('is-open', isOpen);
            menuBtn.setAttribute('aria-expanded', String(isOpen));
            document.body.classList.toggle('overflow-hidden', isOpen && window.innerWidth < 768);
        };

        syncMobileMenuState(false);

        menuBtn.addEventListener('click', () => {
            const isOpen = !mobileMenu.classList.contains('is-open');
            syncMobileMenuState(isOpen);
        });

        mobileMenu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => syncMobileMenuState(false));
        });

        document.addEventListener('click', (event) => {
            const target = event.target as Node;
            if (mobileMenu.classList.contains('is-open') && !mobileMenu.contains(target) && !menuBtn.contains(target)) {
                syncMobileMenuState(false);
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                syncMobileMenuState(false);
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                syncMobileMenuState(false);
            }
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
