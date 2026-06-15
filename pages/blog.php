<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/components.php';

$query = trim($_GET['q'] ?? '');
$tag = trim($_GET['tag'] ?? '');
$year = isset($_GET['year']) ? (int)$_GET['year'] : null;
$month = isset($_GET['month']) ? (int)$_GET['month'] : null;
$day = isset($_GET['day']) ? (int)$_GET['day'] : null;

$currentPage = 'blog';
$pageTitle = 'Blog';
$pageDescription = 'Blog posts by Martin Chammah on TypeScript, Next.js, SQL, Go, and software engineering.';
$noindex = false;

$allPosts = DataLoader::getPosts();
$filteredPosts = $allPosts;
$activeTagLabel = '';
$resultsLabel = '';

if ($query !== '') {
    $filteredPosts = DataLoader::search($query, 'posts');
    $resultsLabel = 'Search results for "' . htmlspecialchars($query) . '"';
    $pageTitle = 'Search: ' . $query;
    $pageDescription = 'Blog posts matching "' . $query . '".';
    $noindex = true;
} elseif ($tag !== '') {
    $filteredPosts = DataLoader::filterByTag($tag, 'posts');
    foreach (DataLoader::getAllTags('posts') as $t) {
        if ($t['slug'] === DataLoader::normalizeTag($tag)) {
            $activeTagLabel = $t['tag'];
            break;
        }
    }
    $resultsLabel = 'Posts tagged "' . htmlspecialchars($activeTagLabel ?: $tag) . '"';
    $pageTitle = 'Tag: ' . ($activeTagLabel ?: $tag);
    $pageDescription = 'Blog posts tagged "' . ($activeTagLabel ?: $tag) . '".';
    $noindex = true;
} elseif ($year !== null && $month !== null && $day !== null) {
    $filteredPosts = DataLoader::getItemsByDate($year, $month, $day, 'posts');
    $resultsLabel = 'Posts on ' . date('F j, Y', strtotime("{$year}-{$month}-{$day}"));
    $pageTitle = 'Posts on ' . date('F j, Y', strtotime("{$year}-{$month}-{$day}"));
    $pageDescription = $resultsLabel;
    $noindex = true;
} elseif ($year !== null && $month !== null) {
    $filteredPosts = DataLoader::getItemsByDate($year, $month, null, 'posts');
    $resultsLabel = 'Posts from ' . date('F Y', strtotime("{$year}-{$month}-01"));
    $pageTitle = 'Posts from ' . date('F Y', strtotime("{$year}-{$month}-01"));
    $pageDescription = $resultsLabel;
    $noindex = true;
}

$archive = DataLoader::getArchiveMonths('posts');
$allTags = DataLoader::getAllTags('posts');

ob_start();
?>

<section class="page-hero">
    <h1>Blog</h1>
    <p class="lead">Notes on TypeScript, Next.js, SQL, Go, and the craft of building fast, pragmatic web products.</p>
</section>

<section>
    <?= render_section_header(['title' => 'blog posts']) ?>
    
    <div class="blog-layout">
        <main class="blog-main">
            <?= render_active_filters([
                'baseUrl' => '/blog',
                'query' => $query,
                'tag' => $activeTagLabel,
                'year' => $year,
                'month' => $month,
                'day' => $day
            ]) ?>
            
            <?php if ($resultsLabel): ?>
                <div class="results-meta"><?= htmlspecialchars($resultsLabel) ?> &mdash; <?= count($filteredPosts) ?> post<?= count($filteredPosts) === 1 ? '' : 's' ?></div>
            <?php endif; ?>
            
            <?php if (empty($filteredPosts)): ?>
                <p class="text-muted">No posts found. Try a different search term or browse the tags below.</p>
            <?php else: ?>
                <div class="post-list grid-1">
                    <?php foreach ($filteredPosts as $post): ?>
                        <?= render_post_preview(['item' => $post, 'basePath' => '/blog']) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
        
        <aside class="blog-sidebar">
            <div>
                <h3>Search</h3>
                <?= render_search_form(['action' => '/blog', 'query' => $query]) ?>
            </div>
            
            <?php if (!empty($allTags)): ?>
                <div>
                    <h3>Tags</h3>
                    <?= render_tag_cloud(['tags' => $allTags, 'current' => $tag, 'baseUrl' => '/blog']) ?>
                </div>
            <?php endif; ?>
            
            <div>
                <h3>Archive</h3>
                <?= render_archive([
                    'archive' => $archive,
                    'year' => $year,
                    'month' => $month,
                    'baseUrl' => '/blog'
                ]) ?>
            </div>
        </aside>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
