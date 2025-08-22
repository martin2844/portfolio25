<?php
require_once __DIR__ . '/../includes/functions.php';

$currentPage = 'portfolio';
$pageTitle = 'Portfolio';

// Parse portfolio YAML
$portfolioItems = [];
$lines = file(__DIR__ . '/../data/pages/portfolio.yaml');
$currentItem = null;
foreach ($lines as $line) {
    $line = trim($line);
    if (strpos($line, '- name:') === 0) {
        if ($currentItem) $portfolioItems[] = $currentItem;
        $currentItem = ['name' => trim(substr($line, 7), ' "'), 'technologies' => []];
    } elseif ($currentItem && strpos($line, 'description:') !== false) {
        $currentItem['description'] = trim(substr($line, 12), ' "');
    } elseif ($currentItem && strpos($line, 'githubLink:') !== false) {
        $currentItem['githubLink'] = trim(substr($line, 11), ' "');
    } elseif ($currentItem && strpos($line, 'websiteLink:') !== false) {
        $currentItem['websiteLink'] = trim(substr($line, 12), ' "');
    } elseif ($currentItem && strpos($line, '- ') === 0 && !strpos($line, 'name:')) {
        $currentItem['technologies'][] = trim(substr($line, 2), ' "');
    }
}
if ($currentItem) $portfolioItems[] = $currentItem;

ob_start();
?>

<div class="grid">
    <?php foreach ($portfolioItems as $item): ?>
        <div class="card">
            <h3><?= $item['name'] ?></h3>
            <p><?= $item['description'] ?></p>
            <div class="tags">
                <?php foreach ($item['technologies'] as $tech): ?>
                    <span class="tag"><?= $tech ?></span>
                <?php endforeach; ?>
            </div>
            <div class="links">
                <?php if (!empty($item['githubLink'])): ?>
                    <a href="<?= $item['githubLink'] ?>" class="link" target="_blank">github</a>
                <?php endif; ?>
                <?php if (!empty($item['websiteLink'])): ?>
                    <a href="<?= $item['websiteLink'] ?>" class="link" target="_blank">website</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
?>