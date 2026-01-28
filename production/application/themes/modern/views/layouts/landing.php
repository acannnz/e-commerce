<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Arcnad Store - Modern E-Commerce'; ?></title>
    
    <!-- Meta Tags -->
    <meta name="description" content="Arcnad Store - Experience modern shopping with high performance and premium design.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Optimized Static Build) -->
    <link href="<?php echo base_url('assets/css/tailwind.css?v=' . time()); ?>" rel="stylesheet">
    
    <!-- GSAP for Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
</head>
<body class="bg-white text-slate-900 font-sans">
    
    <!-- Transparent Navigation -->
    <nav id="main-nav" class="fixed top-0 left-0 right-0 z-[100] transition-all duration-300 px-6 py-6 font-outfit">
        <div class="max-w-7xl mx-auto flex items-center justify-between glass rounded-full px-8 py-3 shadow-sm">
            <a href="<?php echo base_url(); ?>" class="text-2xl font-black tracking-tight text-primary-600">
                ARCNAD<span class="text-slate-900">STORE</span>
            </a>
            
            <div class="hidden md:flex items-center space-x-10 text-sm font-semibold uppercase tracking-wider">
                <a href="<?php echo base_url(); ?>#hero" class="hover:text-primary-500 transition-colors">Home</a>
                <a href="<?php echo base_url('ecommerce/product_catalog'); ?>" class="hover:text-primary-500 transition-colors">Products</a>
                <a href="<?php echo base_url(); ?>#categories" class="hover:text-primary-500 transition-colors">Categories</a>
                <a href="<?php echo base_url(); ?>#about" class="hover:text-primary-500 transition-colors">About</a>
            </div>
            
            <div class="flex items-center space-x-4">
                <a href="<?php echo base_url('ecommerce/orders/cart'); ?>" class="relative p-2 hover:bg-slate-100 rounded-full transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </a>
                <a href="<?php echo base_url('login'); ?>" class="hidden md:block bg-slate-900 text-white px-6 py-2.5 rounded-full text-sm font-bold hover:bg-slate-800 transition-all shadow-lg hover:shadow-primary-200">
                    Sign In
                </a>
                <button class="md:hidden p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <?php echo $content; ?>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-20 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-1">
                <a href="#" class="text-2xl font-black tracking-tight text-primary-500 mb-6 block">
                    ARCNAD<span class="text-white">STORE</span>
                </a>
                <p class="text-sm leading-relaxed mb-6">
                    Redefining the digital shopping experience with premium quality and state-of-the-art design.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-8 h-8 flex items-center justify-center bg-slate-800 rounded-lg hover:bg-primary-600 transition-colors text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="#" class="w-8 h-8 flex items-center justify-center bg-slate-800 rounded-lg hover:bg-primary-600 transition-colors text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6 font-outfit uppercase tracking-widest text-xs">Shop</h4>
                <ul class="space-y-4 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">All Products</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Best Sellers</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">New Arrivals</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Trending</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6 font-outfit uppercase tracking-widest text-xs">Customer</h4>
                <ul class="space-y-4 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">Order Status</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Shipping Info</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Returns</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6 font-outfit uppercase tracking-widest text-xs">Newsletter</h4>
                <p class="text-sm mb-4">Be the first to know about new collection launches.</p>
                <form class="flex space-x-2">
                    <input type="email" placeholder="Email address" class="bg-slate-800 border-none rounded-lg px-4 py-2 text-sm w-full focus:ring-2 focus:ring-primary-500 text-white">
                    <button class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                    </button>
                </form>
            </div>
        </div>
        <div class="max-w-7xl mx-auto border-t border-slate-800 mt-20 pt-8 flex flex-col md:flex-row justify-between items-center text-xs uppercase tracking-widest">
            <p>© 2026 Arcnad Store. All rights reserved.</p>
            <div class="flex space-x-8 mt-4 md:mt-0">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="<?php echo base_url('assets/js/landing-animations.js'); ?>"></script>
    <script>
        // Scroll navigation effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('main-nav');
            if (window.scrollY > 50) {
                nav.classList.add('py-4');
                nav.classList.remove('py-6');
            } else {
                nav.classList.add('py-6');
                nav.classList.remove('py-4');
            }
        });
    </script>
</body>
</html>
