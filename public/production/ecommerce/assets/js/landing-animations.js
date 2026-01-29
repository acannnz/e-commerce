document.addEventListener('DOMContentLoaded', () => {
    // Register GSAP Plugins
    gsap.registerPlugin(ScrollTrigger);

    // 1. Initial Hero Animations (Load immediately)
    const heroTl = gsap.timeline();
    heroTl.fromTo("#hero .reveal-up", 
        { y: 80, autoAlpha: 0 },
        {
            y: 0,
            autoAlpha: 1,
            duration: 1.5,
            stagger: 0.3,
            ease: "expo.out"
        }
    );

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
                end: "+=1500", // Pin for 1500px of scrolling
                pin: true,
                scrub: 1,
                anticipatePin: 1
            }
        });

        showcaseTl.from(".showcase-content", { x: -100, autoAlpha: 0, duration: 1 })
                  .from(".showcase-image", { scale: 0.8, rotate: 0, autoAlpha: 0, duration: 1 }, "-=0.5")
                  .from(".showcase-card", { y: 50, autoAlpha: 0, duration: 1 }, "-=0.8")
                  .from(".showcase-stat", { y: 30, autoAlpha: 0, stagger: 0.2, duration: 0.8 }, "-=0.5");
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

    // 5. Scroll-based reveals 
    // Handle Grids (Why Choose Us, Featured Products) with coordinated staggers
    const gridReveals = document.querySelectorAll('section:not(#hero) .grid');
    gridReveals.forEach(grid => {
        const items = grid.querySelectorAll('.reveal-up');
        if (items.length > 0) {
            gsap.fromTo(items, 
                { y: 50, autoAlpha: 0 },
                {
                    scrollTrigger: {
                        trigger: grid,
                        start: "top 50%",
                        toggleActions: "play none none reverse"
                    },
                    y: 0,
                    autoAlpha: 1,
                    duration: 1,
                    stagger: 0.1,
                    ease: "power2.out"
                }
            );
        }
    });

    // Handle single reveal-up elements that ARE NOT inside a grid already handled
    const singleReveals = document.querySelectorAll('section:not(#hero) .reveal-up:not(.grid .reveal-up), section:not(#hero) > .reveal-up');
    singleReveals.forEach((el) => {
        const delay = el.getAttribute('data-delay') || 0;
        
        gsap.fromTo(el, 
            { y: 50, autoAlpha: 0 },
            {
                scrollTrigger: {
                    trigger: el,
                    start: "top 85%", // Trigger slightly later for better visibility
                    toggleActions: "play none none reverse"
                },
                y: 0,
                autoAlpha: 1,
                duration: 1.2,
                delay: parseFloat(delay),
                ease: "power3.out"
            }
        );
    });

    // 6. Interaction Extras
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

    // 7. Clean URL Navigation (Remove hashes from URL)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            // Only handle internal links that are not just "#"
            if (targetId.startsWith('#') && targetId.length > 1) {
                e.preventDefault();
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80, // Adjust for fixed navbar height
                        behavior: 'smooth'
                    });
                }
            }
        });
    });

    // 8. Refresh ScrollTrigger
    window.onload = () => {
        ScrollTrigger.refresh();
    };
});
