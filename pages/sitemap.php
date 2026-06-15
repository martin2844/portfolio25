<?php
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/xml; charset=UTF-8');

$baseUrl = 'https://martinchammah.dev';
$pages = ['/', '/blog', '/notes', '/portfolio', '/about', '/cv'];
$posts = DataLoader::getPosts();
$notes = DataLoader::getNotes();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($pages as $page) {
    echo '  <url>' . "\n";
    echo '    <loc>' . e($baseUrl . $page) . '</loc>' . "\n";
    echo '    <changefreq>weekly</changefreq>' . "\n";
    echo '    <priority>' . ($page === '/' ? '1.0' : '0.8') . '</priority>' . "\n";
    echo '  </url>' . "\n";
}

foreach ($posts as $post) {
    $date = $post['frontmatter']['publishDate'] ?? '';
    echo '  <url>' . "\n";
    echo '    <loc>' . e($baseUrl . '/blog/' . ($post['frontmatter']['slug'] ?? '')) . '</loc>' . "\n";
    if ($date) {
        echo '    <lastmod>' . date('Y-m-d', strtotime($date)) . '</lastmod>' . "\n";
    }
    echo '    <changefreq>monthly</changefreq>' . "\n";
    echo '    <priority>0.7</priority>' . "\n";
    echo '  </url>' . "\n";
}

foreach ($notes as $note) {
    $date = $note['frontmatter']['publishDate'] ?? '';
    echo '  <url>' . "\n";
    echo '    <loc>' . e($baseUrl . '/notes/' . ($note['frontmatter']['slug'] ?? '')) . '</loc>' . "\n";
    if ($date) {
        echo '    <lastmod>' . date('Y-m-d', strtotime($date)) . '</lastmod>' . "\n";
    }
    echo '    <changefreq>monthly</changefreq>' . "\n";
    echo '    <priority>0.6</priority>' . "\n";
    echo '  </url>' . "\n";
}

echo '</urlset>' . "\n";
