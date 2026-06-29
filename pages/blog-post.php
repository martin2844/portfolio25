<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/components.php';

$slug = $_GET['slug'] ?? '';
$post = DataLoader::getPost($slug);

if (!$post) {
    header('HTTP/1.0 404 Not Found');
    $currentPage = 'blog';
    $pageTitle = '404 - Post Not Found';
    $pageDescription = 'The requested blog post could not be found.';
    $noindex = true;
    $omitCanonical = true;
    ob_start();
    ?>
    <div class="card message-card">
        <h2>Post not found</h2>
        <p>The requested blog post could not be found.</p>
        <?= render_button(['href' => '/blog', 'label' => '← Back to blog']) ?>
    </div>
    <?php
    $content = ob_get_clean();
    require __DIR__ . '/../templates/layout.php';
    exit;
}

$currentPage = 'blog';
$pageTitle = $post['frontmatter']['title'];
$pageDescription = $post['frontmatter']['excerpt'];
$pageKeywords = 'Martin Chammah, ' . implode(', ', $post['frontmatter']['tags'] ?? []) . ', Blog, Programming, Software Engineering';
$canonicalUrl = 'https://martinchammah.dev/blog/' . $post['frontmatter']['slug'];
$ogType = 'article';
$postUrl = 'https://martinchammah.dev/blog/' . $post['frontmatter']['slug'];
$coverImage = $post['frontmatter']['coverImage'] ?? null;
$ogImage = absoluteUrl($coverImage) ?? 'https://martinchammah.dev/og-image.jpg';
$schemaImage = absoluteUrl($coverImage) ?? absoluteUrl(firstContentImage($post['content'])) ?? null;
$dateModified = $post['frontmatter']['modifiedDate'] ?? $post['frontmatter']['publishDate'];

$blogPosting = [
    '@type' => 'BlogPosting',
    'headline' => $post['frontmatter']['title'],
    'description' => $post['frontmatter']['excerpt'],
    'datePublished' => $post['frontmatter']['publishDate'],
    'dateModified' => $dateModified,
    'author' => [
        '@type' => 'Person',
        'name' => 'Martin Chammah',
        'url' => 'https://martinchammah.dev'
    ],
    'publisher' => [
        '@type' => 'Person',
        'name' => 'Martin Chammah'
    ],
    'url' => $postUrl,
    'keywords' => $post['frontmatter']['tags'] ?? []
];
if ($schemaImage) {
    $blogPosting['image'] = $schemaImage;
}

$jsonLd = [
    '@context' => 'https://schema.org',
    '@graph' => [
        $blogPosting,
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://martinchammah.dev/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => 'https://martinchammah.dev/blog'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $post['frontmatter']['title'], 'item' => $postUrl]
            ]
        ]
    ]
];

ob_start();
?>

<article class="article-page" role="article">
    <?= render_back_button(['href' => '/blog', 'label' => 'back to blog']) ?>
    
    <header>
        <h1><?= htmlspecialchars($post['frontmatter']['title']) ?></h1>
        <div class="meta"><?= formatDate($post['frontmatter']['publishDate']) ?> • <?= $post['frontmatter']['readingTime'] ?>min read</div>
        <?= render_tag_list($post['frontmatter']['tags'] ?? [], '/blog') ?>
    </header>
    
    <div class="article-body">
        <?= $post['content'] ?>
    </div>
</article>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
