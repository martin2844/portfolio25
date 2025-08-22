<?php
require_once __DIR__ . '/../includes/functions.php';

$slug = $_GET['slug'] ?? '';
$note = DataLoader::getNote($slug);

if (!$note) {
    header('HTTP/1.0 404 Not Found');
    $content = '<div class="card"><h2>Note not found</h2><p>The requested note could not be found.</p><a href="/notes" class="link">← Back to notes</a></div>';
    $currentPage = 'notes';
    $pageTitle = '404 - Note Not Found';
    require __DIR__ . '/../templates/layout.php';
    exit;
}

$currentPage = 'notes';
$pageTitle = $note['frontmatter']['title'];
$pageDescription = $note['frontmatter']['excerpt'];

ob_start();
?>

<div class="blog-full">
    <a href="/notes" class="back-btn">← back to notes</a>
    <h1><?= $note['frontmatter']['title'] ?></h1>
    <div class="meta"><?= formatDate($note['frontmatter']['publishDate']) ?></div>
    <div class="content" style="max-height: none; overflow: visible;">
        <?= $note['content'] ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
?>