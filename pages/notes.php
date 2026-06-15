<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/components.php';

$notes = DataLoader::getNotes();
$currentPage = 'notes';
$pageTitle = 'Notes';
$pageDescription = 'Short notes and quick thoughts by Martin Chammah.';

ob_start();
?>

<section>
    <?= render_section_header(['title' => 'notes']) ?>
    <div class="post-list grid-2">
        <?php foreach ($notes as $note): ?>
            <?= render_post_preview(['item' => $note, 'basePath' => '/notes']) ?>
        <?php endforeach; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
