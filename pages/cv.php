<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/components.php';

$cv = DataLoader::getCv();
$currentPage = 'cv';
$pageTitle = 'CV';
$pageDescription = 'Martin Chammah — curriculum vitae: experience, education, skills, and services.';

$personal = $cv['personalInfo'] ?? [];

ob_start();
?>

<section>
    <header class="section-header">
        <h2>CV — <?= htmlspecialchars($personal['name'] ?? 'Martin Chammah') ?></h2>
    </header>
    
    <div class="article-page">
        <section class="mb-0">
            <?= render_section_header(['title' => 'Experience']) ?>
            <div class="timeline">
                <?php foreach ($cv['experience'] ?? [] as $exp): ?>
                    <?= render_experience_item(['item' => $exp]) ?>
                <?php endforeach; ?>
            </div>
        </section>
        
        <section class="mt-4">
            <?= render_section_header(['title' => 'Education']) ?>
            <div class="timeline">
                <?php foreach ($cv['education'] ?? [] as $edu): ?>
                    <?= render_education_item(['item' => $edu]) ?>
                <?php endforeach; ?>
            </div>
        </section>
        
        <section class="mt-4">
            <?= render_section_header(['title' => 'Skills & Technologies']) ?>
            <div class="skills-grid">
                <?php foreach ($cv['skills'] ?? [] as $category => $skills): ?>
                    <?= render_skill_category(['title' => $category, 'skills' => $skills]) ?>
                <?php endforeach; ?>
            </div>
        </section>
        
        <section class="mt-4">
            <?= render_section_header(['title' => 'Services']) ?>
            <div class="card">
                <ul>
                    <?php foreach ($cv['services'] ?? [] as $service): ?>
                        <li><?= htmlspecialchars($service) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
        
        <div class="contact-bar mt-4">
            <?= render_button(['href' => 'mailto:' . ($personal['email'] ?? 'martinchio@proton.me'), 'label' => '✉ ' . ($personal['email'] ?? 'martinchio@proton.me')]) ?>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
