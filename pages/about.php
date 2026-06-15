<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/components.php';

$about = DataLoader::getAbout();
$currentPage = 'about';
$pageTitle = 'About';
$pageDescription = 'About Martin Chammah — full stack software engineer based in Barcelona, Spain.';

if (!$about) {
    ob_start();
    ?>
    <div class="card message-card">
        <h2>About page not found</h2>
        <p>Sorry, the about page content is missing.</p>
    </div>
    <?php
    $content = ob_get_clean();
    require __DIR__ . '/../templates/layout.php';
    exit;
}

$fm = $about['frontmatter'];
$skills = $fm['skills'] ?? [];
$interests = $fm['interests'] ?? [];

ob_start();
?>

<section class="about-page">
    <article class="article-page">
        <header>
            <h1>About</h1>
        </header>
        
        <div class="article-body">
            <?= $about['content'] ?>
        </div>
        
        <section class="card mt-4 reading-column">
            <h3>Skills</h3>
            <?= render_tag_list($skills) ?>
        </section>
        
        <section class="card mt-2 reading-column">
            <h3>Interests</h3>
            <?= render_tag_list($interests) ?>
        </section>
        
        <div class="contact-bar">
            <?= render_button(['href' => 'mailto:' . $fm['email'], 'label' => '✉ ' . $fm['email']]) ?>
            <?= render_button(['href' => 'https://' . $fm['linkedin'], 'label' => '💼 LinkedIn', 'external' => true]) ?>
            <?= render_button(['href' => 'https://' . $fm['github'], 'label' => '💻 GitHub', 'external' => true]) ?>
        </div>
    </article>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
