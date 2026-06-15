<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' | ' : '' ?>Martin Chammah | Software Engineer</title>
    <meta name="description" content="<?= e($pageDescription ?? 'Full stack software engineer skilled in TypeScript, Next.js, SQL, and Go. Barcelona, Spain.') ?>">
    <meta name="author" content="Martin Chammah">
    <meta name="robots" content="<?= !empty($noindex) ? 'noindex, follow' : 'index, follow' ?>">
    <meta name="language" content="English">
    <meta name="theme-color" content="#ffffff">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?= e($ogType ?? 'website') ?>">
    <meta property="og:url" content="<?= e($canonicalUrl ?? 'https://martinchammah.dev' . $_SERVER['REQUEST_URI']) ?>">
    <meta property="og:site_name" content="Martin Chammah">
    <meta property="og:title" content="<?= e($ogTitle ?? ($pageTitle ? $pageTitle . ' | Martin Chammah' : 'Martin Chammah | Software Engineer')) ?>">
    <meta property="og:description" content="<?= e($pageDescription ?? 'Full stack software engineer skilled in TypeScript, Next.js, SQL, and Go. Barcelona, Spain.') ?>">
    <meta property="og:image" content="<?= e($ogImage ?? 'https://martinchammah.dev/og-image.jpg') ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= e($canonicalUrl ?? 'https://martinchammah.dev' . $_SERVER['REQUEST_URI']) ?>">
    <meta property="twitter:title" content="<?= e($ogTitle ?? ($pageTitle ? $pageTitle . ' | Martin Chammah' : 'Martin Chammah | Software Engineer')) ?>">
    <meta property="twitter:description" content="<?= e($pageDescription ?? 'Full stack software engineer skilled in TypeScript, Next.js, SQL, and Go. Barcelona, Spain.') ?>">
    <meta property="twitter:image" content="<?= e($ogImage ?? 'https://martinchammah.dev/og-image.jpg') ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= e($canonicalUrl ?? 'https://martinchammah.dev' . $_SERVER['REQUEST_URI']) ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/public/icon.svg">
    <link rel="icon" type="image/x-icon" href="/public/icon.ico">
    <link rel="shortcut icon" href="/public/icon.ico">

    <!-- Schema.org JSON-LD -->
    <?php if (isset($jsonLd)): ?>
    <script type="application/ld+json"><?= json_encode($jsonLd, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
    <?php endif; ?>

    <!-- Preload fonts to prevent FOUT -->
    <link rel="preload" href="/public/fonts/ATSeasonSansVF.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/public/fonts/Geist.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/public/fonts/GeistMono.woff2" as="font" type="font/woff2" crossorigin="anonymous">

    <link rel="stylesheet" href="/public/fonts/fonts.css">
    <link rel="stylesheet" href="/public/styles.css">
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <div class="container">
        <header class="site-header" role="banner">
            <div class="site-brand">
                <a href="/" class="site-title" id="site-title" aria-label="Martin Chammah home">martin_chammah</a>
                <span class="site-subtitle">software_engineer.barcelona</span>
            </div>

            <nav class="main-nav" role="navigation" aria-label="Main navigation">
                <a href="/" class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>" <?= $currentPage === 'home' ? 'aria-current="page"' : '' ?>>home</a>
                <a href="/blog" class="nav-link <?= $currentPage === 'blog' ? 'active' : '' ?>" <?= $currentPage === 'blog' ? 'aria-current="page"' : '' ?>>blog</a>
                <a href="/portfolio" class="nav-link <?= $currentPage === 'portfolio' ? 'active' : '' ?>" <?= $currentPage === 'portfolio' ? 'aria-current="page"' : '' ?>>portfolio</a>
                <a href="/notes" class="nav-link <?= $currentPage === 'notes' ? 'active' : '' ?>" <?= $currentPage === 'notes' ? 'aria-current="page"' : '' ?>>notes</a>
                <a href="/about" class="nav-link <?= $currentPage === 'about' ? 'active' : '' ?>" <?= $currentPage === 'about' ? 'aria-current="page"' : '' ?>>about</a>
                <a href="/cv" class="nav-link <?= $currentPage === 'cv' ? 'active' : '' ?>" <?= $currentPage === 'cv' ? 'aria-current="page"' : '' ?>>cv</a>
            </nav>
        </header>
        
        <main role="main" class="section" id="main-content">
            <?= $content ?>
        </main>
    </div>
</body>
</html>
