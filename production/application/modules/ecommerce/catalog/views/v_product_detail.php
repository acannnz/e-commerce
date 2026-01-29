<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="bg-white min-h-screen pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-12 text-sm font-medium text-gray-500" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li><a href="<?php echo base_url(); ?>" class="hover:text-primary-600 transition-colors">Home</a></li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg></li>
                <li><a href="<?php echo base_url('ecommerce/product_catalog'); ?>" class="hover:text-primary-600 transition-colors">Produk</a></li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg></li>
                <li class="text-gray-900 font-bold"><?php echo $product->name; ?></li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Product Images -->
            <div class="reveal-up">
                <div class="relative group">
                    <div class="aspect-square overflow-hidden rounded-[4rem] bg-gray-100 shadow-2xl border border-gray-100">
                        <?php 
                            $image_url = (strpos($product->image, 'http') === 0) ? $product->image : base_url('assets/images/products/' . $product->image);
                        ?>
                        <img src="<?php echo $image_url; ?>" alt="<?php echo $product->name; ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000">
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="flex flex-col reveal-up" data-delay="0.1">
                <div class="mb-10">
                    <span class="inline-block bg-primary-50 text-primary-600 text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-widest mb-6">Premium Choice</span>
                    <h1 class="text-5xl md:text-6xl font-black font-outfit text-gray-900 leading-tight mb-4 uppercase italic tracking-tighter"><?php echo $product->name; ?></h1>
                    
                    <div class="flex items-center space-x-6 mb-8">
                        <div class="flex items-center">
                            <div class="flex text-yellow-400">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <svg class="w-5 h-5 fill-current opacity-30" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <span class="ml-2 text-sm font-bold text-gray-500">4.8 (120+ reviews)</span>
                        </div>
                        <div class="h-4 w-px bg-gray-200"></div>
                        <span class="text-sm font-bold text-primary-600">Stock Available</span>
                    </div>

                    <p class="text-4xl font-black text-gray-900 italic font-outfit mb-10">Rp <?php echo number_format($product->price, 0, ',', '.'); ?></p>
                    
                    <div class="prose prose-sm text-gray-500 max-w-none mb-12 leading-relaxed font-medium">
                        <?php echo $product->description ? $product->description : 'Produk premium ini dibuat dengan standar kualitas tertinggi, memberikan kenyamanan dan gaya maksimal untuk Anda.'; ?>
                    </div>
                </div>

                <!-- Product Actions -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center bg-gray-50 rounded-2xl p-2 border border-gray-100">
                            <button class="w-10 h-10 flex items-center justify-center hover:bg-white rounded-xl transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 12H4"/></svg></button>
                            <span class="w-12 text-center font-black">1</span>
                            <button class="w-10 h-10 flex items-center justify-center hover:bg-white rounded-xl transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg></button>
                        </div>
                        <button class="flex-1 bg-gray-900 text-white py-5 rounded-[2rem] text-sm font-black uppercase tracking-widest hover:bg-primary-600 transition-all shadow-2xl flex items-center justify-center space-x-3">
                            <span>Tambah ke Keranjang</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <button class="bg-white border-2 border-gray-100 py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:border-gray-900 transition-all flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span>Wishlist</span>
                        </button>
                        <button class="bg-white border-2 border-gray-100 py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:border-gray-900 transition-all flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                            <span>Share</span>
                        </button>
                    </div>
                </div>

                <!-- Features List -->
                <div class="mt-16 pt-12 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Authentic Product</h4>
                            <p class="text-xs text-gray-500 font-medium">Jaminan keaslian 100%.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Fast Delivery</h4>
                            <p class="text-xs text-gray-500 font-medium">Pengiriman secepat kilat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related products or more info could go here -->
    </div>
</div>

<script src="<?php echo base_url('assets/js/catalog-interactions.js'); ?>"></script>
