<?php
require_once __DIR__ . '/../includes/functions.php';

$posts = DataLoader::getPosts();
$currentPage = 'blog';
$pageTitle = 'Blog';

ob_start();
?>

<div class="grid">
    <?php foreach ($posts as $post): ?>
        <a href="/blog/<?= $post['frontmatter']['slug'] ?>" class="card" style="text-decoration: none; color: inherit;">
            <h3><?= $post['frontmatter']['title'] ?></h3>
            <div class="meta"><?= formatDate($post['frontmatter']['publishDate']) ?> • <?= $post['frontmatter']['readingTime'] ?>min read</div>
            <p><?= $post['frontmatter']['excerpt'] ?></p>
            <div class="tags">
                <?php if (isset($post['frontmatter']['tags'])): ?>
                    <?php foreach ($post['frontmatter']['tags'] as $tag): ?>
                        <span class="tag"><?= trim($tag) ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
?>