<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/components.php';

$projects = DataLoader::getPortfolio();
$currentPage = 'portfolio';
$pageTitle = 'Portfolio';
$pageDescription = 'Selected projects by Martin Chammah — full stack web applications built with Next.js, TypeScript, SQL, Go, and more.';

ob_start();
?>

<section>
    <?= render_section_header(['title' => 'projects']) ?>
    <div class="grid grid-2">
        <?php foreach ($projects as $project): ?>
            <?= render_project_card(['item' => $project]) ?>
        <?php endforeach; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
