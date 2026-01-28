<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Our Latest Products</h2>
        <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-500 dark:text-blue-400">View All <span aria-hidden="true"> &rarr;</span></a>
    </div>

    <?php if (empty($products)): ?>
        <div class="p-12 text-center bg-white rounded-lg shadow dark:bg-gray-800">
            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No products found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding new items to your inventory.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            <?php foreach ($products as $product): ?>
                <div class="relative group">
                    <div class="w-full overflow-hidden bg-gray-200 rounded-md aspect-h-1 aspect-w-1 lg:aspect-none group-hover:opacity-75 lg:h-80">
                        <img src="<?= $product->image ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=300' ?>" 
                             alt="<?= $product->name ?>" 
                             class="object-cover object-center w-full h-full lg:h-full lg:w-full">
                    </div>
                    <div class="flex justify-between mt-4">
                        <div>
                            <h3 class="text-sm text-gray-700 dark:text-gray-300">
                                <a href="<?= base_url('ecommerce/catalog/detail/'.$product->id) ?>">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    <?= $product->name ?>
                                </a>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400"><?= $product->category_name ?? 'Collection' ?></p>
                        </div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Rp <?= number_format($product->price, 0, ',', '.') ?></p>
                    </div>
                    <button class="mt-4 w-full flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-8 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Add to cart
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
