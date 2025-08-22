<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' | ' : '' ?>Martin Chammah | Software Engineer</title>
    <meta name="description" content="<?= $pageDescription ?? 'Full stack software engineer skilled in TypeScript, Next.js, SQL, and Go. Barcelona, Spain.' ?>">
    <meta name="keywords" content="<?= $pageKeywords ?? 'Martin Chammah, Software Engineer, TypeScript, Next.js, SQL, Go, Full Stack Developer, Barcelona, Web Development' ?>">
    <meta name="author" content="Martin Chammah">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?= $ogType ?? 'website' ?>">
    <meta property="og:url" content="<?= $canonicalUrl ?? 'https://martinchammah.dev' . $_SERVER['REQUEST_URI'] ?>">
    <meta property="og:title" content="<?= $ogTitle ?? ($pageTitle ? $pageTitle . ' | Martin Chammah' : 'Martin Chammah | Software Engineer') ?>">
    <meta property="og:description" content="<?= $pageDescription ?? 'Full stack software engineer skilled in TypeScript, Next.js, SQL, and Go. Barcelona, Spain.' ?>">
    <meta property="og:image" content="<?= $ogImage ?? 'https://martinchammah.dev/og-image.jpg' ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $canonicalUrl ?? 'https://martinchammah.dev' . $_SERVER['REQUEST_URI'] ?>">
    <meta property="twitter:title" content="<?= $ogTitle ?? ($pageTitle ? $pageTitle . ' | Martin Chammah' : 'Martin Chammah | Software Engineer') ?>">
    <meta property="twitter:description" content="<?= $pageDescription ?? 'Full stack software engineer skilled in TypeScript, Next.js, SQL, and Go. Barcelona, Spain.' ?>">
    <meta property="twitter:image" content="<?= $ogImage ?? 'https://martinchammah.dev/og-image.jpg' ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= $canonicalUrl ?? 'https://martinchammah.dev' . $_SERVER['REQUEST_URI'] ?>">
    
    <!-- Schema.org JSON-LD -->
    <?php if (isset($jsonLd)): ?>
    <script type="application/ld+json"><?= json_encode($jsonLd) ?></script>
    <?php endif; ?>
    
    <style>
        /* Screen reader only class */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        
        /* Focus styles for accessibility */
        a:focus, button:focus, [tabindex]:focus {
            outline: 2px solid #58a6ff;
            outline-offset: 2px;
        }
        
        /* Skip link for screen readers */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: #58a6ff;
            color: #0d1117;
            padding: 8px;
            text-decoration: none;
            border-radius: 3px;
            z-index: 100;
        }
        
        .skip-link:focus {
            top: 6px;
        }
        
        <?php echo file_get_contents(__DIR__ . '/../includes/prism-mini.css'); ?>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Courier New', monospace;
            background: #0d1117;
            color: #c9d1d9;
            line-height: 1.6;
            font-size: 14px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            border-bottom: 1px solid #21262d;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        h1 {
            color: #58a6ff;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .subtitle {
            color: #7c3aed;
            font-size: 16px;
        }
        
        nav {
            margin: 20px 0;
        }
        
        .nav-btn, .nav-link {
            background: #21262d;
            border: 1px solid #30363d;
            color: #c9d1d9;
            padding: 8px 16px;
            margin: 0 5px 5px 0;
            text-decoration: none;
            display: inline-block;
            font-family: inherit;
            font-size: 12px;
            border-radius: 3px;
            transition: background 0.2s;
        }
        
        .nav-link:hover, .nav-link.active {
            background: #58a6ff;
            color: #0d1117;
        }
        
        .section {
            animation: fadeIn 0.3s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .grid {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }
        
        .card {
            background: #161b22;
            border: 1px solid #21262d;
            border-radius: 6px;
            padding: 20px;
            transition: border-color 0.2s;
        }
        
        .card:hover {
            border-color: #58a6ff;
        }
        
        .card h3 {
            color: #58a6ff;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .card h4 {
            color: #7c3aed;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .meta {
            color: #8b949e;
            font-size: 12px;
            margin-bottom: 10px;
        }
        
        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin: 10px 0;
        }
        
        .tag {
            background: #21262d;
            color: #58a6ff;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        
        .links {
            margin-top: 10px;
        }
        
        .link {
            color: #58a6ff;
            text-decoration: none;
            margin-right: 15px;
            font-size: 12px;
        }
        
        .link:hover {
            text-decoration: underline;
        }
        
        .home-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #161b22;
            border: 1px solid #21262d;
            padding: 20px;
            text-align: center;
            border-radius: 6px;
        }
        
        .stat-number {
            font-size: 24px;
            color: #58a6ff;
            font-weight: bold;
        }
        
        .stat-label {
            color: #8b949e;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .latest-posts, .about-preview {
            margin-top: 30px;
        }
        
        .post-preview {
            background: #161b22;
            border: 1px solid #21262d;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            text-decoration: none;
            color: inherit;
            display: block;
            transition: border-color 0.2s;
        }
        
        .post-preview:hover {
            border-color: #58a6ff;
        }
        
        .content {
            margin-top: 15px;
            color: #8b949e;
            font-size: 13px;
        }
        
        .blog-full {
            max-width: 100%;
            margin: 0;
            line-height: 1.7;
        }
        
        .blog-full h1, .blog-full h2, .blog-full h3 {
            color: #58a6ff;
            margin: 20px 0 10px 0;
        }
        
        .blog-full p {
            margin-bottom: 15px;
        }
        
        .blog-full pre {
            background: #0d1117;
            border: 1px solid #30363d;
            border-radius: 6px;
            padding: 15px;
            margin: 15px 0;
            overflow-x: auto;
            font-size: 13px;
            line-height: 1.45;
            font-family: 'SF Mono', Consolas, 'Liberation Mono', Menlo, Courier, monospace;
        }
        
        .blog-full pre code {
            background: transparent;
            padding: 0;
            border-radius: 0;
            color: #e6edf3;
        }
        
        .blog-full code {
            background: #262c36;
            color: #e6edf3;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 13px;
            font-family: 'SF Mono', Consolas, 'Liberation Mono', Menlo, Courier, monospace;
            white-space: nowrap;
            display: inline;
            word-break: break-all;
        }
        
        /* Code outside blog posts */
        code {
            background: #262c36;
            color: #e6edf3;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 13px;
            font-family: 'SF Mono', Consolas, 'Liberation Mono', Menlo, Courier, monospace;
            white-space: nowrap;
        }
        
        
        .blog-full img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            margin: 15px 0;
            border: 1px solid #21262d;
        }
        
        .back-btn {
            background: #21262d;
            border: 1px solid #30363d;
            color: #c9d1d9;
            padding: 8px 16px;
            margin-bottom: 20px;
            text-decoration: none;
            display: inline-block;
            font-family: inherit;
            font-size: 12px;
            border-radius: 3px;
        }
        
        .back-btn:hover {
            background: #30363d;
        }
        
        .experience-item, .education-item {
            background: #161b22;
            border: 1px solid #21262d;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 6px;
        }
        
        .cv-section {
            margin-bottom: 30px;
        }
        
        .cv-section h3 {
            color: #58a6ff;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .skill-category {
            background: #161b22;
            border: 1px solid #21262d;
            padding: 15px;
            border-radius: 6px;
        }
        
        .skill-category h4 {
            color: #7c3aed;
            margin-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .container { padding: 10px; }
            .grid { grid-template-columns: 1fr; }
            h1 { font-size: 20px; }
            .nav-btn, .nav-link { padding: 6px 12px; }
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <div class="container">
        <header role="banner">
            <h1 id="site-title">martin_chammah</h1>
            <div class="subtitle">software_engineer.barcelona</div>
        </header>
        
        <nav role="navigation" aria-label="Main navigation">
            <a href="/" class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>" <?= $currentPage === 'home' ? 'aria-current="page"' : '' ?>>home</a>
            <a href="/blog" class="nav-link <?= $currentPage === 'blog' ? 'active' : '' ?>" <?= $currentPage === 'blog' ? 'aria-current="page"' : '' ?>>blog</a>
            <a href="/portfolio" class="nav-link <?= $currentPage === 'portfolio' ? 'active' : '' ?>" <?= $currentPage === 'portfolio' ? 'aria-current="page"' : '' ?>>portfolio</a>
            <a href="/notes" class="nav-link <?= $currentPage === 'notes' ? 'active' : '' ?>" <?= $currentPage === 'notes' ? 'aria-current="page"' : '' ?>>notes</a>
            <a href="/about" class="nav-link <?= $currentPage === 'about' ? 'active' : '' ?>" <?= $currentPage === 'about' ? 'aria-current="page"' : '' ?>>about</a>
            <a href="/cv" class="nav-link <?= $currentPage === 'cv' ? 'active' : '' ?>" <?= $currentPage === 'cv' ? 'aria-current="page"' : '' ?>>cv</a>
        </nav>
        
        <main role="main" class="section" id="main-content">
            <?= $content ?>
        </main>
    </div>
</body>
</html>