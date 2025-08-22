<?php
require_once __DIR__ . '/../includes/functions.php';

$slug = $_GET['slug'] ?? '';
$post = DataLoader::getPost($slug);

if (!$post) {
    header('HTTP/1.0 404 Not Found');
    $content = '<div class="card"><h2>Post not found</h2><p>The requested blog post could not be found.</p><a href="/blog" class="link">← Back to blog</a></div>';
    $currentPage = 'blog';
    $pageTitle = '404 - Post Not Found';
    require __DIR__ . '/../templates/layout.php';
    exit;
}

$currentPage = 'blog';
$pageTitle = $post['frontmatter']['title'];
$pageDescription = $post['frontmatter']['excerpt'];
$pageKeywords = 'Martin Chammah, ' . implode(', ', $post['frontmatter']['tags'] ?? []) . ', Blog, Programming, Software Engineering';
$canonicalUrl = 'https://martinchammah.dev/blog/' . $post['frontmatter']['slug'];
$ogType = 'article';
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post['frontmatter']['title'],
    'description' => $post['frontmatter']['excerpt'],
    'datePublished' => $post['frontmatter']['publishDate'],
    'author' => [
        '@type' => 'Person',
        'name' => 'Martin Chammah',
        'url' => 'https://martinchammah.dev'
    ],
    'publisher' => [
        '@type' => 'Person',
        'name' => 'Martin Chammah'
    ],
    'url' => 'https://martinchammah.dev/blog/' . $post['frontmatter']['slug'],
    'keywords' => $post['frontmatter']['tags'] ?? []
];

ob_start();
?>

<article class="blog-full" role="article">
    <nav aria-label="Breadcrumb">
        <a href="/blog" class="back-btn">← back to blog</a>
    </nav>
    <header>
        <h1><?= $post['frontmatter']['title'] ?></h1>
    <div class="meta"><?= formatDate($post['frontmatter']['publishDate']) ?> • <?= $post['frontmatter']['readingTime'] ?>min read</div>
    <div class="tags" style="margin: 15px 0;">
        <?php if (isset($post['frontmatter']['tags'])): ?>
            <?php foreach ($post['frontmatter']['tags'] as $tag): ?>
                <span class="tag"><?= trim($tag) ?></span>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    </header>
    <div class="content" style="max-height: none; overflow: visible;" role="main">
        <?= $post['content'] ?>
    </div>
</article>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
?>