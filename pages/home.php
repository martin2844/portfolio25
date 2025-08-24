<?php
require_once __DIR__ . '/../includes/functions.php';

$posts = DataLoader::getPosts();
$about = DataLoader::getAbout();
$portfolio = DataLoader::getYamlData('portfolio.yaml');
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

// Parse portfolio YAML properly
$portfolioItems = [];
if ($portfolio) {
    // Simple parsing for portfolio items
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
}

ob_start();
?>

<div class="home-stats">
    <div class="stat-card">
        <div class="stat-number"><?= count($posts) ?></div>
        <div class="stat-label">blog posts</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= count($portfolioItems) ?></div>
        <div class="stat-label">projects</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">5</div>
        <div class="stat-label">sections</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= count($notes) ?></div>
        <div class="stat-label">notes</div>
    </div>
</div>

<?php if ($about): ?>
<div class="about-preview">
    <h3 style="color: #58a6ff; margin-bottom: 15px;">about me</h3>
    <div class="card">
        <h4><?= $about['frontmatter']['name'] ?></h4>
        <div class="meta"><?= $about['frontmatter']['title'] ?> • <?= $about['frontmatter']['location'] ?></div>
        <div class="content"><?= excerpt(strip_tags($about['content']), 200) ?> <a href="/about">read more...</a></div>
        <div class="tags">
            <?php
            $skills = explode(',', $about['frontmatter']['skills'] ?? '');
            foreach (array_slice($skills, 0, 4) as $skill): ?>
                <span class="tag"><?= trim($skill, '" "') ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="latest-posts">
    <h3 style="color: #58a6ff; margin-bottom: 15px;">latest posts</h3>
    <?php foreach (array_slice($posts, 0, 3) as $post): ?>
        <a href="/blog/<?= $post['frontmatter']['slug'] ?>" class="post-preview">
            <h4><?= $post['frontmatter']['title'] ?></h4>
            <div class="meta"><?= formatDate($post['frontmatter']['publishDate']) ?> • <?= $post['frontmatter']['readingTime'] ?>min</div>
            <div class="content"><?= $post['frontmatter']['excerpt'] ?></div>
        </a>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
?>