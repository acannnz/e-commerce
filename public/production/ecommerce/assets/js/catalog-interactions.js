document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Reveal Animations
    if (typeof gsap !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);

        const reveals = document.querySelectorAll('.reveal-up');
        reveals.forEach((el, index) => {
            gsap.fromTo(el, 
                { y: 50, opacity: 0 },
                {
                    scrollTrigger: {
                        trigger: el,
                        start: "top 90%",
                        toggleActions: "play none none none"
                    },
                    y: 0,
                    opacity: 1,
                    duration: 0.8,
                    delay: index * 0.05,
                    ease: "power3.out"
                }
            );
        });
    }

    // 2. Mobile Filter Drawer Logic
    const mobileFilterBtn = document.getElementById('mobile-filter-btn');
    const filterDrawer = document.getElementById('filter-drawer');
    const drawerOverlay = document.getElementById('drawer-overlay');
    const drawerContent = document.getElementById('drawer-content');
    const closeDrawer = document.getElementById('close-drawer');

    const openFilters = () => {
        filterDrawer.classList.remove('hidden');
        setTimeout(() => {
            drawerOverlay.classList.remove('opacity-0');
            drawerContent.classList.remove('translate-x-full');
        }, 10);
        document.body.style.overflow = 'hidden';
    };

    const closeFilters = () => {
        drawerOverlay.classList.add('opacity-0');
        drawerContent.classList.add('translate-x-full');
        setTimeout(() => {
            filterDrawer.classList.add('hidden');
            document.body.style.overflow = '';
        }, 500);
    };

    if (mobileFilterBtn) mobileFilterBtn.addEventListener('click', openFilters);
    if (closeDrawer) closeDrawer.addEventListener('click', closeFilters);
    if (drawerOverlay) drawerOverlay.addEventListener('click', closeFilters);

    // 3. Price Range Input Sync (Optional UI Enhancement)
    const minInput = document.querySelector('input[name="min_price"]');
    const maxInput = document.querySelector('input[name="max_price"]');

    if (minInput && maxInput) {
        // Enforce max > min on blur
        maxInput.addEventListener('blur', () => {
            if (minInput.value && maxInput.value && parseInt(maxInput.value) < parseInt(minInput.value)) {
                maxInput.value = minInput.value;
            }
        });
    }

    // 4. Smooth Image Loading Overlay
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        if (img.complete) {
            img.classList.add('opacity-100');
        } else {
            img.style.opacity = '0';
            img.addEventListener('load', () => {
                img.style.transition = 'opacity 0.5s ease-in-out';
                img.style.opacity = '1';
            });
        }
    });
});
