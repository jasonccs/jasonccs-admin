import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { createSvgIconsPlugin }   from 'vite-plugin-svg-icons'; // 版本不同引入方式不同
import path from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        createSvgIconsPlugin({
            // 指定需要缓存的图标文件夹
            iconDirs: [path.resolve(process.cwd(), 'resources/assets/icons/svg')],
            // 指定symbolId格式
            symbolId: 'icon-[dir]-[name]'
        })
    ],
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `@import "resources/css/global.scss";` //公共样式地址
            }
        }
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
