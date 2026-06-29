<?php
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/rss+xml; charset=UTF-8');

$baseUrl = 'https://martinchammah.dev';
$posts = DataLoader::getPosts();
$lastBuild = !empty($posts[0]['frontmatter']['publishDate'])
    ? date(DATE_RSS, strtotime($posts[0]['frontmatter']['publishDate']))
    : date(DATE_RSS);

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
    <title>Martin Chammah</title>
    <link><?= e($baseUrl . '/blog') ?></link>
    <description>Blog posts by Martin Chammah on TypeScript, Next.js, SQL, Go, and software engineering.</description>
    <language>en</language>
    <lastBuildDate><?= e($lastBuild) ?></lastBuildDate>
    <atom:link href="<?= e($baseUrl . '/rss.xml') ?>" rel="self" type="application/rss+xml"/>
    <?php foreach ($posts as $post): ?>
    <?php
    $fm = $post['frontmatter'];
    $link = $baseUrl . '/blog/' . ($fm['slug'] ?? '');
    $pubDate = !empty($fm['publishDate']) ? date(DATE_RSS, strtotime($fm['publishDate'])) : '';
    $description = !empty($fm['excerpt']) ? $fm['excerpt'] : excerpt(strip_tags($post['content']), 300);
    ?>
    <item>
        <title><?= e($fm['title'] ?? '') ?></title>
        <link><?= e($link) ?></link>
        <guid isPermaLink="true"><?= e($link) ?></guid>
        <?php if ($pubDate): ?><pubDate><?= e($pubDate) ?></pubDate><?php endif; ?>
        <description><?= e($description) ?></description>
    </item>
    <?php endforeach; ?>
</channel>
</rss>
