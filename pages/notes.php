<?php
require_once __DIR__ . '/../includes/functions.php';

$notes = DataLoader::getNotes();
$currentPage = 'notes';
$pageTitle = 'Notes';

ob_start();
?>

<div class="grid">
    <?php foreach ($notes as $note): ?>
        <a href="/notes/<?= $note['frontmatter']['slug'] ?>" class="card" style="text-decoration: none; color: inherit;">
            <h3><?= $note['frontmatter']['title'] ?></h3>
            <div class="meta"><?= formatDate($note['frontmatter']['publishDate']) ?></div>
            <p><?= $note['frontmatter']['excerpt'] ?></p>
        </a>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
?>