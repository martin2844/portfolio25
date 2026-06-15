<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/components.php';

$slug = $_GET['slug'] ?? '';
$note = DataLoader::getNote($slug);

if (!$note) {
    header('HTTP/1.0 404 Not Found');
    $currentPage = 'notes';
    $pageTitle = '404 - Note Not Found';
    $pageDescription = 'The requested note could not be found.';
    ob_start();
    ?>
    <div class="card message-card">
        <h2>Note not found</h2>
        <p>The requested note could not be found.</p>
        <?= render_button(['href' => '/notes', 'label' => '← Back to notes']) ?>
    </div>
    <?php
    $content = ob_get_clean();
    require __DIR__ . '/../templates/layout.php';
    exit;
}

$currentPage = 'notes';
$pageTitle = $note['frontmatter']['title'];
$pageDescription = $note['frontmatter']['excerpt'];
$canonicalUrl = 'https://martinchammah.dev/notes/' . $note['frontmatter']['slug'];
$ogType = 'article';
$noteUrl = 'https://martinchammah.dev/notes/' . $note['frontmatter']['slug'];
$jsonLd = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Article',
            'headline' => $note['frontmatter']['title'],
            'description' => $note['frontmatter']['excerpt'],
            'datePublished' => $note['frontmatter']['publishDate'],
            'author' => [
                '@type' => 'Person',
                'name' => 'Martin Chammah',
                'url' => 'https://martinchammah.dev'
            ],
            'publisher' => [
                '@type' => 'Person',
                'name' => 'Martin Chammah'
            ],
            'url' => $noteUrl,
            'keywords' => $note['frontmatter']['tags'] ?? []
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://martinchammah.dev/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Notes', 'item' => 'https://martinchammah.dev/notes'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $note['frontmatter']['title'], 'item' => $noteUrl]
            ]
        ]
    ]
];

ob_start();
?>

<article class="article-page" role="article">
    <?= render_back_button(['href' => '/notes', 'label' => 'back to notes']) ?>
    
    <header>
        <h1><?= htmlspecialchars($note['frontmatter']['title']) ?></h1>
        <div class="meta"><?= formatDate($note['frontmatter']['publishDate']) ?></div>
        <?= render_tag_list($note['frontmatter']['tags'] ?? [], '/blog') ?>
    </header>
    
    <div class="article-body">
        <?= $note['content'] ?>
    </div>
</article>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
