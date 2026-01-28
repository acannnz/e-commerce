<!-- Hero Section -->
<section id="hero" class="relative min-h-screen flex items-center pt-20 overflow-hidden hero-gradient">
    <!-- 3D Decorative Elements (CSS/SVG) -->
    <div class="absolute top-1/4 right-0 w-96 h-96 bg-primary-100 rounded-full blur-[120px] opacity-50 -z-10 animate-pulse"></div>
    <div class="absolute bottom-1/4 left-0 w-72 h-72 bg-blue-100 rounded-full blur-[100px] opacity-30 -z-10"></div>

    <div class="max-w-7xl mx-auto px-6 w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <!-- Text Content -->
        <div class="reveal-up">
            <div class="inline-flex items-center space-x-2 bg-primary-50 border border-primary-100 px-4 py-2 rounded-full mb-8">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
                </span>
                <span class="text-primary-700 text-xs font-bold uppercase tracking-widest">New Collection 2026</span>
            </div>
            
            <h1 class="text-6xl md:text-7xl lg:text-8xl font-black font-outfit leading-[0.9] mb-8 tracking-tighter">
                ELEVATE <br> 
                <span class="text-primary-600">YOUR STYLE</span>
            </h1>
            
            <p class="text-xl text-slate-600 mb-10 max-w-lg leading-relaxed font-medium">
                Discover the future of digital commerce with Arcnad Store. Premium quality meets avant-garde design.
            </p>
            
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-6">
                <a href="<?php echo base_url('ecommerce/product_catalog'); ?>" class="bg-slate-900 text-white px-10 py-5 rounded-full text-lg font-bold hover:bg-slate-800 transition-all shadow-2xl hover:shadow-primary-300 flex items-center justify-center group">
                    Belanja Sekarang
                    <svg class="ml-2 group-hover:translate-x-1 transition-transform" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
                <a href="#featured" class="bg-white border-2 border-slate-900 text-slate-900 px-10 py-5 rounded-full text-lg font-bold hover:bg-slate-100 transition-all flex items-center justify-center">
                    Lihat Produk
                </a>
            </div>
            
            <div class="mt-16 flex items-center space-x-8 text-sm font-bold text-slate-400 uppercase tracking-widest">
                <div class="flex flex-col">
                    <span class="text-slate-900 text-2xl font-black italic">15K+</span>
                    <span>Customers</span>
                </div>
                <div class="w-px h-10 bg-slate-200"></div>
                <div class="flex flex-col">
                    <span class="text-slate-900 text-2xl font-black italic">4.9/5</span>
                    <span>Rating</span>
                </div>
            </div>
        </div>

        <!-- Hero 3D Mockup Container -->
        <div class="relative lg:block">
            <div id="hero-3d-container" class="relative z-10 w-full aspect-square flex items-center justify-center">
                <!-- Main Floating Object (Abstract 3D Shape or Product) -->
                <div id="main-3d-object" class="relative w-4/5 h-4/5 bg-gradient-to-br from-primary-500 to-blue-600 rounded-[3rem] shadow-[0_50px_100px_-20px_rgba(14,165,233,0.5)] flex items-center justify-center overflow-hidden transform rotate-12 transition-transform duration-500 group cursor-pointer hover:scale-105">
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
                    <!-- Placeholder product image or icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="150" height="150" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="drop-shadow-2xl animate-bounce">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                    
                    <!-- Floating floating text inside -->
                    <div class="absolute bottom-10 left-10 right-10 flex justify-between items-end">
                        <div class="text-white">
                            <p class="text-xs font-bold uppercase tracking-widest opacity-80">Premium Pack</p>
                            <h3 class="text-2xl font-black font-outfit">ARCNAD EDITION</h3>
                        </div>
                        <span class="bg-white/20 backdrop-blur-md px-4 py-2 rounded-full text-white text-xs font-bold">LIMITED</span>
                    </div>
                </div>

                <!-- Decorative floating elements -->
                <div class="parallax-el absolute -top-10 left-0 w-32 h-32 glass rounded-[2rem] shadow-xl flex items-center justify-center z-20Rotate">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="1.5"><path d="M12 2v20M2 12h20"/></svg>
                </div>
                <div class="parallax-el absolute -bottom-10 right-0 w-40 h-40 bg-slate-900 rounded-[2.5rem] shadow-2xl flex items-center justify-center z-20">
                    <div class="text-center text-white p-4">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-primary-400 mb-1">Price</p>
                        <p class="text-xl font-black font-outfit">$299.00</p>
                    </div>
                </div>
                <!-- Orbit circles -->
                <div class="absolute inset-0 border-[1px] border-slate-200 rounded-full -m-10 animate-[spin_20s_linear_infinite]"></div>
                <div class="absolute inset-0 border-[1px] border-slate-100 rounded-full -m-20 animate-[spin_30s_linear_infinite_reverse]"></div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section id="about" class="py-32 px-6 bg-slate-50 relative overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-end mb-20 reveal-up">
            <div class="max-w-2xl">
                <h2 class="text-5xl font-black font-outfit mb-6 italic tracking-tight uppercase">Why <span class="text-primary-600">Arcnad?</span></h2>
                <p class="text-lg text-slate-500 font-medium">We deliver excellence in every detail, from the first click to the final unboxing.</p>
            </div>
            <div class="hidden md:block">
                <div class="w-24 h-24 border-2 border-slate-200 rounded-full flex items-center justify-center animate-spin-slow">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 13l5 5 5-5M7 6l5 5 5-5"/></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="reveal-up p-10 bg-white rounded-[3rem] shadow-sm hover:shadow-xl transition-all group border border-slate-100">
                <div class="w-16 h-16 bg-primary-50 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-primary-600 transition-colors">
                    <svg class="text-primary-600 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polyline points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 font-outfit">Instant Delivery</h3>
                <p class="text-slate-500 leading-relaxed font-medium">Faster than light. We ensure your package arrives before you even expect it.</p>
            </div>
            <div class="reveal-up p-10 bg-white rounded-[3rem] shadow-sm hover:shadow-xl transition-all group border border-slate-100" style="transition-delay: 0.1s">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-primary-600 transition-colors">
                    <svg class="text-primary-600 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 font-outfit">Unmatched Quality</h3>
                <p class="text-slate-500 leading-relaxed font-medium">Curated products from premium materials. We don't settle for less than perfect.</p>
            </div>
            <div class="reveal-up p-10 bg-white rounded-[3rem] shadow-sm hover:shadow-xl transition-all group border border-slate-100" style="transition-delay: 0.2s">
                <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-primary-600 transition-colors">
                    <svg class="text-primary-600 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 font-outfit">Expert Support</h3>
                <p class="text-slate-500 leading-relaxed font-medium">Human experts ready to assist you 24/7. Your satisfaction is our religion.</p>
            </div>
        </div>
    </div>
</section>

<!-- Brand Experience Showcase (Pinning Section) -->
<section id="brand-showcase" class="relative py-0 bg-slate-900 overflow-hidden">
    <div class="pin-panel h-screen flex items-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=2000" alt="Showcase Background" class="w-full h-full object-cover opacity-30 grayscale">
        </div>
        <div class="max-w-7xl mx-auto px-6 w-full relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-20">
            <div class="showcase-content">
                <span class="text-primary-500 font-bold uppercase tracking-[0.3em] text-xs mb-6 block">The Arcnad Philosophy</span>
                <h2 class="text-7xl md:text-8xl font-black text-white font-outfit leading-none mb-10 italic uppercase tracking-tighter">
                    Crafting <br> <span class="text-primary-400">Pure</span> <br> Elegance
                </h2>
                <p class="text-xl text-slate-400 font-medium max-w-md leading-relaxed mb-12">
                    Every element we design is a testament to our commitment to perfection. Immerse yourself in a world where luxury meets function.
                </p>
                <div class="flex items-center space-x-12">
                   <div class="showcase-stat">
                        <p class="text-4xl font-black text-white font-outfit">100%</p>
                        <p class="text-xs uppercase tracking-widest text-slate-500 font-bold">Premium Silk</p>
                   </div>
                   <div class="showcase-stat">
                        <p class="text-4xl font-black text-white font-outfit">2026</p>
                        <p class="text-xs uppercase tracking-widest text-slate-500 font-bold">Design Era</p>
                   </div>
                </div>
            </div>
            <div class="relative flex items-center justify-center">
                <div class="showcase-image relative w-full aspect-square rounded-[4rem] overflow-hidden shadow-2xl skew-y-3">
                    <img src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?q=80&w=1000" alt="Premium Product" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                </div>
                <!-- Floating decorative card -->
                <div class="absolute -bottom-10 -left-10 glass p-8 rounded-[3rem] shadow-2xl z-20 max-w-[200px] showcase-card">
                    <p class="text-xs font-bold uppercase tracking-widest text-primary-600 mb-2">Editor's Choice</p>
                    <p class="text-sm font-medium text-slate-800 italic">"The peak of minimalist design and high-end performance."</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Spacer to allow scroll length for pinning -->
    <div class="h-[120vh]"></div>
</section>

<!-- Horizontal Scroll Categories -->
<section id="categories-scroll" class="py-32 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 mb-20 flex justify-between items-end">
        <div>
            <h2 class="text-5xl font-black font-outfit italic tracking-tighter uppercase">Curated <span class="text-primary-600">Categories</span></h2>
        </div>
        <div class="scroll-progress-container w-64 h-1 bg-slate-100 rounded-full relative overflow-hidden">
            <div class="scroll-progress-bar absolute top-0 left-0 h-full bg-primary-600 w-0"></div>
        </div>
    </div>
    
    <div class="horizontal-container flex space-x-12 px-6 lg:px-[10%] cursor-grab active:cursor-grabbing">
        <!-- Category Card 1 -->
        <div class="category-card min-w-[350px] md:min-w-[450px] aspect-[4/5] relative rounded-[3rem] overflow-hidden group">
            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=1000" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Footwear">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-10 left-10 text-white">
                <h3 class="text-4xl font-black font-outfit italic uppercase mb-2">Footwear</h3>
                <p class="text-sm font-bold uppercase tracking-widest text-primary-400">42 Items</p>
            </div>
        </div>
        <!-- Category Card 2 -->
        <div class="category-card min-w-[350px] md:min-w-[450px] aspect-[4/5] relative rounded-[3rem] overflow-hidden group">
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1000" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Accessories">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-10 left-10 text-white">
                <h3 class="text-4xl font-black font-outfit italic uppercase mb-2">Essentials</h3>
                <p class="text-sm font-bold uppercase tracking-widest text-primary-400">18 Items</p>
            </div>
        </div>
        <!-- Category Card 3 -->
        <div class="category-card min-w-[350px] md:min-w-[450px] aspect-[4/5] relative rounded-[3rem] overflow-hidden group">
            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=1000" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Electronics">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-10 left-10 text-white">
                <h3 class="text-4xl font-black font-outfit italic uppercase mb-2">Tech</h3>
                <p class="text-sm font-bold uppercase tracking-widest text-primary-400">25 Items</p>
            </div>
        </div>
        <!-- Category Card 4 -->
        <div class="category-card min-w-[350px] md:min-w-[450px] aspect-[4/5] relative rounded-[3rem] overflow-hidden group">
            <img src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?q=80&w=1000" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Premium">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-10 left-10 text-white">
                <h3 class="text-4xl font-black font-outfit italic uppercase mb-2">Limited</h3>
                <p class="text-sm font-bold uppercase tracking-widest text-primary-400">09 Items</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section id="featured" class="py-32 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 reveal-up">
            <div>
                <span class="text-primary-600 font-bold uppercase tracking-widest text-xs mb-4 block">Hand-picked Excellence</span>
                <h2 class="text-6xl font-black font-outfit tracking-tighter uppercase">Featured <span class="text-slate-400">Items</span></h2>
            </div>
            <a href="<?php echo base_url('ecommerce/product_catalog'); ?>" class="mt-8 md:mt-0 text-sm font-bold uppercase tracking-widest border-b-2 border-slate-900 pb-1 hover:text-primary-600 hover:border-primary-600 transition-all">Explore All Products</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php if (!empty($featured_products)): ?>
                <?php foreach ($featured_products as $index => $product): ?>
                    <div class="reveal-up group" style="transition-delay: <?php echo $index * 0.05; ?>s">
                        <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-100 aspect-[4/5] mb-6">
                            <?php if ($product->image): ?>
                                <?php 
                                    $image_url = (strpos($product->image, 'http') === 0) ? $product->image : base_url('assets/images/products/' . $product->image);
                                ?>
                                <img src="<?php echo $image_url; ?>" alt="<?php echo $product->name; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-slate-200 group-hover:scale-110 transition-transform duration-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Overlay Actions -->
                            <div class="absolute inset-x-0 bottom-0 p-6 flex justify-between items-center translate-y-full group-hover:translate-y-0 transition-transform duration-500">
                                <a href="<?php echo base_url('ecommerce/product_catalog/' . $product->id); ?>" class="bg-white text-slate-900 w-12 h-12 rounded-full flex items-center justify-center shadow-lg hover:bg-primary-600 hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <button class="bg-slate-900 text-white px-6 py-3 rounded-full text-xs font-bold hover:bg-primary-600 transition-colors">
                                    + Keranjang
                                </button>
                            </div>
                            
                            <?php if ($index % 3 == 0): ?>
                                <span class="absolute top-6 left-6 bg-primary-600 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-tighter">New</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-lg font-bold font-outfit mb-1 group-hover:text-primary-600 transition-colors uppercase italic tracking-tight"><?php echo $product->name; ?></h3>
                        <p class="text-slate-900 font-black text-xl italic font-outfit">Rp <?php echo number_format($product->price, 0, ',', '.'); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full py-20 text-center bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                    <p class="text-slate-400 font-medium">No products found in our laboratory yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Discount / CTA Section -->
<section class="py-20 px-6">
    <div class="max-w-7xl mx-auto bg-slate-900 rounded-[4rem] p-12 md:p-24 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-primary-600 -skew-x-12 translate-x-1/2 group-hover:translate-x-1/3 transition-transform duration-1000"></div>
        
        <div class="relative z-10 max-w-xl reveal-up">
            <h2 class="text-5xl md:text-7xl font-black font-outfit text-white mb-8 tracking-tighter leading-none italic uppercase">Don't Miss <br> The <span class="text-primary-400 underline decoration-4 underline-offset-8">Vibe.</span></h2>
            <p class="text-xl text-slate-300 mb-12 font-medium">Join our secret list and get up to 50% discount on your first order. It's now or never.</p>
            <a href="#" class="bg-white text-slate-900 px-12 py-5 rounded-full text-xl font-black uppercase italic tracking-widest hover:bg-primary-400 transition-colors shadow-2xl">Get Discount Code</a>
        </div>
        
        <!-- Animated icons in background -->
        <div class="absolute bottom-10 right-20 hidden lg:block opacity-20">
             <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="0.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        </div>
    </div>
</section>

<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 10s linear infinite;
    }
</style>
