<?php
require_once __DIR__ . '/../includes/functions.php';

$currentPage = 'cv';
$pageTitle = 'CV';

// Parse CV YAML manually for speed
$cv = [];
$lines = file(__DIR__ . '/../data/pages/cv.yaml');
$currentSection = null;
$currentItem = null;

foreach ($lines as $line) {
    $line = trim($line);
    if (empty($line) || $line[0] === '#') continue;
    
    if (strpos($line, ':') !== false && !strpos($line, '- ')) {
        $kv = explode(':', $line, 2);
        $key = trim($kv[0]);
        $value = trim($kv[1], ' "');
        
        if (in_array($key, ['personalInfo', 'experience', 'education', 'skills', 'services'])) {
            $currentSection = $key;
            $cv[$currentSection] = [];
        } else {
            if ($currentSection && $currentItem !== null) {
                $cv[$currentSection][$currentItem][$key] = $value;
            } elseif ($currentSection === 'personalInfo') {
                $cv[$currentSection][$key] = $value;
            }
        }
    } elseif (strpos($line, '- ') === 0) {
        if ($currentSection === 'services') {
            $cv[$currentSection][] = trim(substr($line, 2), ' "');
        } else {
            $currentItem = count($cv[$currentSection] ?? []);
            $cv[$currentSection][$currentItem] = [];
        }
    }
}

ob_start();
?>

<div>
    <h2 style="color: #58a6ff; margin-bottom: 30px;">CV - <?= $cv['personalInfo']['name'] ?? 'Martin Chammah' ?></h2>
    
    <div class="cv-section">
        <h3>Experience</h3>
        <?php if (isset($cv['experience'])): ?>
            <?php foreach ($cv['experience'] as $exp): ?>
                <div class="experience-item">
                    <h4 style="color: #7c3aed; margin-bottom: 5px;"><?= $exp['position'] ?? '' ?></h4>
                    <div class="meta"><?= $exp['company'] ?? '' ?> • <?= $exp['period'] ?? '' ?></div>
                    <p style="margin-top: 10px;"><?= $exp['description'] ?? '' ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="cv-section">
        <h3>Education</h3>
        <?php if (isset($cv['education'])): ?>
            <?php foreach ($cv['education'] as $edu): ?>
                <div class="education-item">
                    <h4 style="color: #7c3aed; margin-bottom: 5px;"><?= $edu['degree'] ?? '' ?></h4>
                    <div class="meta"><?= $edu['institution'] ?? '' ?> • <?= $edu['period'] ?? '' ?></div>
                    <div style="margin-top: 5px; color: #8b949e;">Status: <?= $edu['status'] ?? '' ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="cv-section">
        <h3>Skills & Technologies</h3>
        <div class="skills-grid">
            <div class="skill-category">
                <h4>Programming</h4>
                <div class="tags">
                    <span class="tag">JavaScript</span>
                    <span class="tag">TypeScript</span>
                    <span class="tag">Go</span>
                    <span class="tag">Ruby</span>
                    <span class="tag">SQL</span>
                </div>
            </div>
            <div class="skill-category">
                <h4>Frameworks</h4>
                <div class="tags">
                    <span class="tag">Next.js</span>
                    <span class="tag">React</span>
                    <span class="tag">Node.js</span>
                </div>
            </div>
            <div class="skill-category">
                <h4>Databases</h4>
                <div class="tags">
                    <span class="tag">SQLite</span>
                    <span class="tag">SQL</span>
                </div>
            </div>
            <div class="skill-category">
                <h4>Other</h4>
                <div class="tags">
                    <span class="tag">WordPress</span>
                    <span class="tag">SEO</span>
                    <span class="tag">API Development</span>
                    <span class="tag">Web Apps</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="cv-section">
        <h3>Services</h3>
        <div class="card">
            <ul style="list-style-type: disc; margin-left: 20px;">
                <?php if (isset($cv['services'])): ?>
                    <?php foreach ($cv['services'] as $service): ?>
                        <li><?= $service ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <div style="margin-top: 30px; text-align: center;">
        <a href="mailto:<?= $cv['personalInfo']['email'] ?? 'martinchio@proton.me' ?>" class="link">✉ <?= $cv['personalInfo']['email'] ?? 'martinchio@proton.me' ?></a>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../templates/layout.php';
?>