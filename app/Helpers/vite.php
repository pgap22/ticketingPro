<?php
if (!function_exists('vite')) {
    function vite(string $path): string
    {
        $manifestPath = __DIR__ . '/../../public/build/.vite/manifest.json';
        
        if (!file_exists($manifestPath)) {
            return 'http://localhost:5173/' . $path; // fallback
        }
        $manifest = json_decode(file_get_contents($manifestPath), true);
        if (!isset($manifest[$path]['file'])) {
            return '/build/' . basename($path);
        }
        return '/build/' . $manifest[$path]['file'];
    }
}
