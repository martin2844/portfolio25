<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/components.php';

$posts = DataLoader::getPosts();
$about = DataLoader::getAbout();
$portfolio = DataLoader::getPortfolio();
$notes = DataLoader::getNotes();

$currentPage = 'home';
$pageTitle = 'Home';
$pageDescription = 'Martin Chammah - Full stack software engineer based in Barcelona. Skilled in TypeScript, Next.js, SQL, and Go. Latest blog posts and projects.';
$pageKeywords = 'Martin Chammah, Software Engineer, Full Stack Developer, TypeScript, Next.js, SQL, Go, Barcelona, Portfolio';
$canonicalUrl = 'https://martinchammah.dev/';
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'Person',
    'name' => 'Martin Chammah',
    'jobTitle' => 'Software Engineer',
    'url' => 'https://martinchammah.dev',
    'sameAs' => [
        'https://linkedin.com/in/chammah',
        'https://github.com/martin2844'
    ],
    'address' => [
        '@type' => 'PostalAddress',
        'addressLocality' => 'Barcelona',
        'addressCountry' => 'Spain'
    ]
];

$featuredProject = $portfolio[0] ?? null;

ob_start();
?>

<section class="hero">
    <h1>Full-stack engineer building fast, pragmatic web products.</h1>
    <p class="lead">TypeScript, Next.js, SQL, Go. Based in Barcelona. I like shipping useful things and bending simple tools in interesting ways.</p>
</section>

<section class="home-stats">
    <?= render_stat_card(['number' => count($posts), 'label' => 'blog posts']) ?>
    <?= render_stat_card(['number' => count($portfolio), 'label' => 'projects']) ?>
    <?= render_stat_card(['number' => count($notes), 'label' => 'notes']) ?>
    <?= render_stat_card(['number' => '5+', 'label' => 'years remote']) ?>
</section>

<?php if ($featuredProject): ?>
<section>
    <?= render_section_header(['title' => 'featured project', 'href' => '/portfolio', 'linkText' => 'view all →']) ?>
    <?= render_project_card(['item' => $featuredProject, 'priority' => true]) ?>
</section>
<?php endif; ?>

<section>
    <?= render_section_header(['title' => 'latest posts', 'href' => '/blog', 'linkText' => 'view all →']) ?>
    <div class="post-list grid-2">
        <?php foreach (array_slice($posts, 0, 4) as $post): ?>
            <?= render_post_preview(['item' => $post, 'basePath' => '/blog']) ?>
        <?php endforeach; ?>
    </div>
</section>

<?php if ($about): ?>
<section class="about-preview">
    <?= render_section_header(['title' => 'about me', 'href' => '/about', 'linkText' => 'read more →']) ?>
    <div class="card">
        <h3><?= htmlspecialchars($about['frontmatter']['name']) ?></h3>
        <div class="meta"><?= htmlspecialchars($about['frontmatter']['title']) ?> • <?= htmlspecialchars($about['frontmatter']['location']) ?></div>
        <p><?= excerpt(strip_tags($about['content']), 240) ?></p>
        <?= render_tag_list(array_slice($about['frontmatter']['skills'] ?? [], 0, 6)) ?>
    </div>
</section>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
