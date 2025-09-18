import { defineConfig } from 'vite';
import symfonyPlugin from 'vite-plugin-symfony';

export default defineConfig({
    plugins: [
        symfonyPlugin(),
    ],
    base: '/build/',
    build: {
        rollupOptions: {
            input: {
                app: './assets/app.js'
            }
        },
        outDir: './public/build'
    }
});
