<div class="px-4 py-16 mx-auto sm:px-6 lg:px-8 max-w-7xl">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white mb-8">Shopping Cart</h1>

    <?php if (empty($cart_items)): ?>
        <div class="p-16 text-center bg-white rounded-lg shadow dark:bg-gray-800">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <h2 class="text-xl font-medium text-gray-900 dark:text-white">Your cart is empty</h2>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Time to go shopping!</p>
            <a href="<?= base_url('ecommerce/catalog') ?>" class="mt-6 inline-block bg-blue-600 px-6 py-3 text-sm font-medium text-white rounded-md hover:bg-blue-700">Explore Products</a>
        </div>
    <?php else: ?>
        <form action="<?= base_url('ecommerce/orders/cart/update') ?>" method="POST">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
                <section class="lg:col-span-8">
                    <ul role="list" class="divide-y divide-gray-200 border-t border-b border-gray-200 dark:divide-gray-700 dark:border-gray-700">
                        <?php 
                        $total = 0;
                        foreach ($cart_items as $id => $item): 
                            $subtotal = $item['price'] * $item['qty'];
                            $total += $subtotal;
                        ?>
                        <li class="flex py-6 sm:py-10">
                            <div class="flex-shrink-0">
                                <img src="<?= $item['image'] ? base_url('public/uploads/products/'.$item['image']) : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=150' ?>" alt="<?= $item['name'] ?>" class="h-24 w-24 rounded-md object-cover object-center sm:h-48 sm:w-48">
                            </div>

                            <div class="ml-4 flex flex-1 flex-col justify-between sm:ml-6">
                                <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                    <div>
                                        <div class="flex justify-between">
                                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                <a href="<?= base_url('ecommerce/catalog/detail/'.$item['id']) ?>"><?= $item['name'] ?></a>
                                            </h3>
                                        </div>
                                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">Rp <?= number_format($item['price'], 0, ',', '.') ?></p>
                                    </div>

                                    <div class="mt-4 sm:mt-0 sm:pr-9">
                                        <label for="quantity-<?= $id ?>" class="sr-only">Quantity, <?= $item['name'] ?></label>
                                        <input type="number" name="qty[<?= $id ?>]" id="quantity-<?= $id ?>" value="<?= $item['qty'] ?>" class="max-w-[4rem] rounded-md border border-gray-300 py-1.5 text-left text-base font-medium leading-5 text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" min="0">

                                        <div class="absolute right-0 top-0">
                                            <a href="<?= base_url('ecommerce/orders/cart/remove/'.$id) ?>" class="-m-2 inline-flex p-2 text-gray-400 hover:text-red-500">
                                                <span class="sr-only">Remove</span>
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.244 1.48c.763-.125 1.536-.23 2.315-.314v7.643c0 1.056.811 1.911 1.84 1.992a43.59 43.59 0 001.442.028l.01-.005.013.006c.476.01.954.019 1.43.027.915.016 1.83.023 2.744.02a.75.75 0 000-1.5c-1.258.005-2.516-.008-3.774-.038a32.932 32.932 0 01-1.332-.016c-.46-.036-.511-.088-.511-.143V5.641c.783.102 1.562.213 2.336.333a.75.75 0 10.244-1.48c-.78-.127-1.573-.231-2.373-.311V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </section>

                <!-- Order summary -->
                <section class="mt-16 rounded-lg bg-gray-50 px-4 py-6 sm:p-6 lg:col-span-4 lg:mt-0 lg:p-8 dark:bg-gray-800">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Order summary</h2>

                    <dl class="mt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Subtotal</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">Rp <?= number_format($total, 0, ',', '.') ?></dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4 dark:border-gray-700">
                            <dt class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <span>Shipping estimate</span>
                                <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                            </dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">Rp 0</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4 dark:border-gray-700">
                            <dt class="text-base font-medium text-gray-900 dark:text-white">Order total</dt>
                            <dd class="text-base font-medium text-gray-900 dark:text-white">Rp <?= number_format($total, 0, ',', '.') ?></dd>
                        </div>
                    </dl>

                    <div class="mt-6 space-y-4">
                        <button type="submit" class="w-full rounded-md border border-transparent bg-gray-200 px-4 py-3 text-base font-medium text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            Update Cart
                        </button>
                        <a href="<?= base_url('ecommerce/orders/checkout') ?>" class="w-full flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Checkout
                        </a>
                    </div>
                </section>
            </div>
        </form>
    <?php endif; ?>
</div>
