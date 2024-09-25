import vue from "@vitejs/plugin-vue";
import { resolve } from "path";
import { defineConfig } from "vite";

export default defineConfig({
    plugins: [
        vue(),
    ],

    define: {
        "process.env": process.env, // Vite ditched process.env, so we need to pass it in
    },

    build: {
        outDir: resolve(__dirname, "dist"),
        emptyOutDir: true,
        target: "ES2022",
        minify: true,
        manifest: "manifest.json",
        lib: {
            entry: resolve(__dirname, "resources/js/tool.ts"),
            name: "field",
            formats: ["umd"],
            fileName: () => "js/[name]-[hash].js",
        },
        rollupOptions: {
            input: resolve(__dirname, "resources/js/tool.ts"),
            external: ["vue", "Nova", "LaravelNova"],
            output: {
                globals: {
                    vue: "Vue",
                    nova: "Nova",
                    "laravel-nova": "LaravelNova",
                },
                assetFileNames: "css/[name]-[hash].css",
            },
        },
    },

    optimizeDeps: {
        include: ["vue", "@inertiajs/inertia", "@inertiajs/inertia-vue3", "axios"],
    },
});
