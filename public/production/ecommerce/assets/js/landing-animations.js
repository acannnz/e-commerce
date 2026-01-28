document.addEventListener('DOMContentLoaded', () => {
    // Register GSAP Plugins
    gsap.registerPlugin(ScrollTrigger);

    // 1. Initial Hero Animations (Load immediately)
    // Target only elements inside #hero to prevent conflicts
    const heroTl = gsap.timeline();
    heroTl.from("#hero .reveal-up", {
        y: 80,
        opacity: 0,
        duration: 1.5,
        stagger: 0.3,
        ease: "expo.out",
        onComplete: () => {
            // Ensure they stay visible after animation
            gsap.set("#hero .reveal-up", { clearProps: "all" });
        }
    });

    // 2. 3D Parallax Scrolling Effect for Hero Object
    if (document.getElementById("main-3d-object")) {
        gsap.to("#main-3d-object", {
            scrollTrigger: {
                trigger: "#hero",
                start: "top top",
                end: "bottom top",
                scrub: true
            },
            rotate: 30,
            y: -100,
            scale: 0.8,
            ease: "none"
        });
    }

    // 3. Brand Showcase Pinning Animation
    if (document.getElementById("brand-showcase")) {
        const showcaseTl = gsap.timeline({
            scrollTrigger: {
                trigger: "#brand-showcase",
                start: "top top",
                end: "bottom top",
                pin: ".pin-panel", // Pin the inner panel
                scrub: 1,
            }
        });

        showcaseTl.from(".showcase-content", { x: -100, opacity: 0, duration: 1 })
                  .from(".showcase-image", { scale: 0.8, rotate: 0, opacity: 0, duration: 1 }, "-=0.5")
                  .from(".showcase-card", { y: 50, opacity: 0, duration: 1 }, "-=0.8")
                  .from(".showcase-stat", { y: 30, opacity: 0, stagger: 0.2, duration: 0.8 }, "-=0.5");
    }

    // 4. Horizontal Scroll Categories
    const horizontalScroll = document.querySelector('.horizontal-container');
    if (horizontalScroll) {
        const totalScroll = horizontalScroll.scrollWidth - window.innerWidth + (window.innerWidth * 0.1);
        
        gsap.to(horizontalScroll, {
            x: -totalScroll,
            ease: "none",
            scrollTrigger: {
                trigger: "#categories-scroll",
                start: "top 10%",
                end: () => `+=${horizontalScroll.scrollWidth}`,
                pin: true,
                scrub: 1,
                invalidateOnRefresh: true,
                onUpdate: self => {
                    gsap.to(".scroll-progress-bar", { width: `${self.progress * 100}%` });
                }
            }
        });
    }

    // 5. Scroll-based reveals for OTHER sections (Exclude hero)
    // We only target reveal-up elements that are NOT in #hero
    const scrollReveals = document.querySelectorAll('section:not(#hero) .reveal-up');
    scrollReveals.forEach((el) => {
        gsap.fromTo(el, 
            { y: 60, opacity: 0 },
            {
                scrollTrigger: {
                    trigger: el,
                    start: "top 90%",
                    toggleActions: "play none none reverse"
                },
                y: 0,
                opacity: 1,
                duration: 1.2,
                ease: "power3.out"
            }
        );
    });

    // 6. Interaction Extras (Hero 3D & Product Hover)
    const heroContainer = document.getElementById('hero-3d-container');
    const mainObject = document.getElementById('main-3d-object');

    if (heroContainer && mainObject) {
        heroContainer.addEventListener('mousemove', (e) => {
            const { clientX, clientY } = e;
            const { left, top, width, height } = heroContainer.getBoundingClientRect();
            const x = (clientX - left) / width - 0.5;
            const y = (clientY - top) / height - 0.5;
            gsap.to(mainObject, { rotateY: x * 40, rotateX: -y * 40, duration: 0.5, ease: "power2.out" });
        });
        heroContainer.addEventListener('mouseleave', () => {
            gsap.to(mainObject, { rotateY: 12, rotateX: 0, duration: 0.8, ease: "elastic.out(1, 0.5)" });
        });
    }

    const productCards = document.querySelectorAll('#featured .group');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            const img = card.querySelector('img');
            if (img) gsap.to(img, { scale: 1.1, duration: 0.6 });
        });
        card.addEventListener('mouseleave', () => {
            const img = card.querySelector('img');
            if (img) gsap.to(img, { scale: 1, duration: 0.6 });
        });
    });

    // 7. Refresh ScrollTrigger to ensure all positions are calculated correctly
    window.onload = () => {
        ScrollTrigger.refresh();
    };
});
