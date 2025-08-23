<?php
// Simple but fast router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove trailing slash
$uri = rtrim($uri, '/');
if (empty($uri)) $uri = '/';

// Route handling
switch ($uri) {
    case '/':
        require __DIR__ . '/pages/home.php';
        break;
        
    case '/blog':
        require __DIR__ . '/pages/blog.php';
        break;
        
    case '/portfolio':
        require __DIR__ . '/pages/portfolio.php';
        break;
        
    case '/notes':
        require __DIR__ . '/pages/notes.php';
        break;
        
    case '/about':
        require __DIR__ . '/pages/about.php';
        break;
        
    case '/cv':
        require __DIR__ . '/pages/cv.php';
        break;
        
    default:
        // Check for blog posts
        if (preg_match('#^/blog/([a-z0-9-]+)$#', $uri, $matches)) {
            $_GET['slug'] = $matches[1];
            require __DIR__ . '/pages/blog-post.php';
            break;
        }
        
        // Check for notes
        if (preg_match('#^/notes/([a-z0-9-]+)$#', $uri, $matches)) {
            $_GET['slug'] = $matches[1];
            require __DIR__ . '/pages/note.php';
            break;
        }
        
        // 404
        header('HTTP/1.0 404 Not Found');
        $currentPage = '';
        $pageTitle = '404 - Page Not Found';
        $content = '<div class="card"><h2>Page not found</h2><p>The requested page could not be found.</p><a href="/" class="link">← Back to home</a></div>';
        require __DIR__ . '/templates/layout.php';
        break;
}
?>