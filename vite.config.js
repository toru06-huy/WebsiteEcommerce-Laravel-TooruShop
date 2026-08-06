import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js',
                'resources/css/admin/admin.css',
                'resources/css/client/layout.css',
                'resources/css/client/shop.css',
                'resources/css/client/home.css',
                'resources/css/client/product.css',
                'resources/css/client/cart.css',
                'resources/css/client/user/profile.css',
                'resources/css/client/user/order.css',
                'resources/css/client/user/order_detail.css',
                'resources/css/client/user/voucher.css',
                'resources/css/client/user/wishlist.css',
                'resources/css/client/minigame.css',
                'resources/css/client/checkout/payment.css',
                'resources/css/client/checkout/shipping.css',
    
                'resources/css/client/auth/login.css',
                'resources/css/client/auth/register.css',
                'resources/css/client/auth/verify-email.css',
                'resources/css/manufacturer/restock.css'
            ],

            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
