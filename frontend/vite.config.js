import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import { resolve } from "path";
import tailwindcss from "tailwindcss";

export default defineConfig({
  plugins: [react()],
  css: {
    postcss: {
      plugins: [tailwindcss()],
    },
  },
  resolve: {
    alias: {
      components: resolve("src/components/"),
      css: resolve("src/css/"),
      data: resolve("src/data/"),
      helpers: resolve("src/helpers/"),
      images: resolve("src/images/"),
      pages: resolve("src/pages/"),
      utils: resolve("src/utils/"),
    },
  },
  server: {
    proxy: {
      "/api": {
        target: "http://localhost:8008",
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, ""),
      },
    },
    allowedHosts: true,
    port: 3000,
  },
}); 