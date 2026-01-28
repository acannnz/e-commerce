<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="bg-gray-50 min-h-screen pt-24 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Breadcrumbs -->
        <header class="mb-12">
            <nav class="flex mb-4 text-sm font-medium text-gray-500" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="<?php echo base_url(); ?>" class="hover:text-primary-600 transition-colors">Home</a></li>
                    <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg></li>
                    <li class="text-gray-900 font-bold">Produk</li>
                </ol>
            </nav>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-5xl font-black font-outfit text-gray-900 tracking-tight uppercase italic">
                        Semua <span class="text-primary-600">Produk</span>
                    </h1>
                    <p class="mt-2 text-gray-500 font-medium">Temukan koleksi terbaik kami untuk gaya hidup modern Anda.</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-bold text-gray-400 uppercase tracking-widest"><?php echo $total_rows; ?> Items Found</span>
                </div>
            </div>
        </header>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters (Desktop) -->
            <aside class="hidden lg:block w-72 flex-shrink-0">
                <div class="sticky top-28 space-y-8">
                    <!-- Sorting -->
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                        <h3 class="text-sm font-black uppercase tracking-widest text-gray-900 mb-4 italic">Urutkan</h3>
                        <form id="filter-form" action="<?php echo base_url('ecommerce/product_catalog'); ?>" method="GET">
                            <select name="sort" onchange="this.form.submit()" class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all cursor-pointer">
                                <option value="latest" <?php echo ($current_filters['sort'] == 'latest') ? 'selected' : ''; ?>>Terbaru</option>
                                <option value="price_low" <?php echo ($current_filters['sort'] == 'price_low') ? 'selected' : ''; ?>>Harga Terendah</option>
                                <option value="price_high" <?php echo ($current_filters['sort'] == 'price_high') ? 'selected' : ''; ?>>Harga Tertinggi</option>
                                <option value="oldest" <?php echo ($current_filters['sort'] == 'oldest') ? 'selected' : ''; ?>>Terlama</option>
                            </select>
                        </form>
                    </div>

                    <!-- Categories -->
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                        <h3 class="text-sm font-black uppercase tracking-widest text-gray-900 mb-6 italic">Kategori</h3>
                        <ul class="space-y-4">
                            <li>
                                <a href="<?php echo base_url('ecommerce/product_catalog'); ?>" class="flex items-center justify-between group">
                                    <span class="text-sm font-bold <?php echo empty($current_filters['category_id']) ? 'text-primary-600' : 'text-gray-500 group-hover:text-gray-900'; ?> transition-colors">Semua Kategori</span>
                                    <span class="w-2 h-2 rounded-full <?php echo empty($current_filters['category_id']) ? 'bg-primary-600' : 'bg-gray-200'; ?>"></span>
                                </a>
                            </li>
                            <?php foreach ($categories as $cat): ?>
                            <li>
                                <a href="<?php echo base_url('ecommerce/product_catalog?category='.$cat['id']); ?>" class="flex items-center justify-between group">
                                    <span class="text-sm font-bold <?php echo ($current_filters['category_id'] == $cat['id']) ? 'text-primary-600' : 'text-gray-500 group-hover:text-gray-900'; ?> transition-colors"><?php echo $cat['name']; ?></span>
                                    <span class="w-2 h-2 rounded-full <?php echo ($current_filters['category_id'] == $cat['id']) ? 'bg-primary-600' : 'bg-gray-200'; ?>"></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Price Filter -->
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                        <h3 class="text-sm font-black uppercase tracking-widest text-gray-900 mb-6 italic">Rentang Harga</h3>
                        <form action="<?php echo base_url('ecommerce/product_catalog'); ?>" method="GET" class="space-y-4">
                            <?php if(!empty($current_filters['category_id'])): ?>
                                <input type="hidden" name="category" value="<?php echo $current_filters['category_id']; ?>">
                            <?php endif; ?>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="min_price" value="<?php echo $current_filters['min_price']; ?>" placeholder="Min" class="w-full bg-gray-50 border-none rounded-xl px-3 py-2 text-xs font-bold focus:ring-1 focus:ring-primary-500">
                                <input type="number" name="max_price" value="<?php echo $current_filters['max_price']; ?>" placeholder="Max" class="w-full bg-gray-50 border-none rounded-xl px-3 py-2 text-xs font-bold focus:ring-1 focus:ring-primary-500">
                            </div>
                            <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-primary-600 transition-colors shadow-lg">Terapkan</button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1">
                <!-- Mobile Filter Toggle -->
                <div class="lg:hidden flex items-center justify-between mb-8">
                    <button id="mobile-filter-btn" class="flex items-center space-x-2 bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 font-bold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        <span>Filters</span>
                    </button>
                    <div class="flex items-center space-x-2">
                         <span class="text-xs font-bold text-gray-400 uppercase"><?php echo $total_rows; ?> Items</span>
                    </div>
                </div>

                <!-- Product Grid -->
                <?php if (!empty($products)): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                        <?php foreach ($products as $index => $product): ?>
                            <div class="reveal-up group" style="transition-delay: <?php echo ($index % 4) * 0.1; ?>s">
                                <div class="relative overflow-hidden rounded-[3rem] bg-white border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500">
                                    <!-- Image Container -->
                                    <div class="aspect-[4/5] overflow-hidden bg-gray-100 relative">
                                        <?php if ($product->image): ?>
                                            <?php 
                                                $image_url = (strpos($product->image, 'http') === 0) ? $product->image : base_url('assets/images/products/' . $product->image);
                                            ?>
                                            <img src="<?php echo $image_url; ?>" alt="<?php echo $product->name; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200 group-hover:scale-110 transition-transform duration-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Badge -->
                                        <?php if ($index % 3 == 0): ?>
                                            <span class="absolute top-6 right-6 bg-primary-600 text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-lg z-10">New</span>
                                        <?php endif; ?>
                                        
                                        <!-- Quick Action Overlay -->
                                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                                            <a href="<?php echo base_url('ecommerce/product_catalog/'.$product->id); ?>" class="bg-white text-gray-900 p-4 rounded-full shadow-xl hover:bg-primary-600 hover:text-white transition-all transform hover:scale-110">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-8">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-[10px] font-black uppercase tracking-widest text-primary-500">Premium Collection</span>
                                            <div class="flex text-yellow-400">
                                                <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                <span class="text-[10px] text-gray-400 ml-1 font-bold">4.8</span>
                                            </div>
                                        </div>
                                        <h3 class="text-xl font-black font-outfit text-gray-900 mb-1 group-hover:text-primary-600 transition-colors uppercase italic truncate"><?php echo $product->name; ?></h3>
                                        <p class="text-2xl font-black text-gray-900 italic font-outfit">Rp <?php echo number_format($product->price, 0, ',', '.'); ?></p>
                                        
                                        <button class="mt-6 w-full bg-gray-50 text-gray-900 border border-gray-100 py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-gray-900 hover:text-white transition-all shadow-sm hover:shadow-lg flex items-center justify-center space-x-2 group/btn">
                                            <span>Tambah ke Keranjang</span>
                                            <svg class="w-4 h-4 translate-x-0 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="mt-20 flex justify-center">
                            <nav class="inline-flex space-x-2 p-2 bg-white rounded-3xl shadow-sm border border-gray-100">
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <a href="<?php echo base_url('ecommerce/product_catalog?page='.$i.'&'.http_build_query($current_filters)); ?>" class="w-12 h-12 flex items-center justify-center rounded-2xl text-sm font-black transition-all <?php echo ($i == $current_page) ? 'bg-primary-600 text-white shadow-lg shadow-primary-200' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                            </nav>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Empty State -->
                    <div class="text-center py-32 bg-white rounded-[4rem] border-2 border-dashed border-gray-100 reveal-up">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h2 class="text-3xl font-black font-outfit text-gray-900 mb-4 uppercase italic">No Products Found</h2>
                        <p class="text-gray-500 font-medium mb-10 max-w-sm mx-auto">Kami tidak dapat menemukan produk yang sesuai dengan kriteria filter Anda. Coba reset filter atau cari kata kunci lain.</p>
                        <a href="<?php echo base_url('ecommerce/product_catalog'); ?>" class="inline-flex items-center px-10 py-5 bg-gray-900 text-white rounded-full text-sm font-black uppercase tracking-widest hover:bg-primary-600 transition-all shadow-2xl">Reset Filters</a>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</div>

<!-- Mobile Filter Drawer (Overlay) -->
<div id="filter-drawer" class="fixed inset-0 z-[110] hidden">
    <div id="drawer-overlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity opacity-0"></div>
    <div id="drawer-content" class="absolute right-0 top-0 bottom-0 w-80 bg-white shadow-2xl translate-x-full transition-transform duration-500 p-8 flex flex-col">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-2xl font-black font-outfit italic uppercase">Filters</h2>
            <button id="close-drawer" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto space-y-10 pb-10 pr-2">
             <!-- Repeat sidebar filters here for mobile drawer -->
             <div>
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-900 mb-6 italic border-b border-gray-100 pb-2">Sorting</h3>
                <form action="<?php echo base_url('ecommerce/catalog'); ?>" method="GET">
                    <select name="sort" onchange="this.form.submit()" class="w-full bg-gray-50 border-none rounded-2xl px-4 py-3 text-sm font-bold">
                        <option value="latest">Terbaru</option>
                        <option value="price_low">Harga Terendah</option>
                        <option value="price_high">Harga Tertinggi</option>
                    </select>
                </form>
             </div>

             <div>
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-900 mb-6 italic border-b border-gray-100 pb-2">Categories</h3>
                <ul class="space-y-4">
                    <?php foreach ($categories as $cat): ?>
                    <li>
                        <a href="<?php echo base_url('ecommerce/catalog?category='.$cat['id']); ?>" class="flex items-center justify-between group">
                            <span class="text-sm font-bold text-gray-500"><?php echo $cat['name']; ?></span>
                            <span class="w-2 h-2 rounded-full bg-gray-200"></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
             </div>
        </div>
        
        <button class="w-full bg-primary-600 text-white py-5 rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl">Show Results</button>
    </div>
</div>

<script src="<?php echo base_url('assets/js/catalog-interactions.js'); ?>"></script>
