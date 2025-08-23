<?php
require_once __DIR__ . '/../includes/functions.php';

$about = DataLoader::getAbout();
$currentPage = 'about';
$pageTitle = 'About';

if (!$about) {
    $content = '<div class="card"><h2>About page not found</h2></div>';
    require __DIR__ . '/../templates/layout.php';
    exit;
}

ob_start();
?>

<div class="card">
    <h2 style="color: #58a6ff; margin-bottom: 20px;"><?= $about['frontmatter']['name'] ?></h2>
    <div class="meta" style="margin-bottom: 20px;"><?= $about['frontmatter']['title'] ?> • <?= $about['frontmatter']['location'] ?></div>
    
    <div style="margin-bottom: 15px;"><?= $about['content'] ?></div>
    
    <div style="margin-top: 30px;">
        <h3 style="color: #58a6ff; margin-bottom: 15px;">Skills</h3>
        <div class="tags">
            <?php
            $skills = explode(',', $about['frontmatter']['skills'] ?? '');
            foreach ($skills as $skill): ?>
                <span class="tag"><?= trim($skill, '" "') ?></span>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div style="margin-top: 20px;">
        <h3 style="color: #58a6ff; margin-bottom: 15px;">Interests</h3>
        <div class="tags">
            <?php
            $interests = explode(',', $about['frontmatter']['interests'] ?? '');
            foreach ($interests as $interest): ?>
                <span class="tag"><?= trim($interest, '" "') ?></span>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div style="margin-top: 30px;">
        <a href="mailto:<?= $about['frontmatter']['email'] ?>" class="link">✉ Contact me</a>
        <a href="https://<?= $about['frontmatter']['linkedin'] ?>" class="link" target="_blank">💼 LinkedIn</a>
        <a href="https://<?= $about['frontmatter']['github'] ?>" class="link" target="_blank">💻 GitHub</a>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
?>