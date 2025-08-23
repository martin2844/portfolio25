<?php
require_once __DIR__ . '/../includes/functions.php';

$currentPage = 'cv';
$pageTitle = 'CV';

// Custom YAML parser for complex CV structure
$cv = [];
$content = file_get_contents(__DIR__ . '/../data/pages/cv.yaml');
$lines = explode("\n", $content);
$currentSection = null;
$currentItem = null;
$indentLevel = 0;

for ($i = 0; $i < count($lines); $i++) {
    $line = $lines[$i];
    $trimmed = trim($line);
    
    if (empty($trimmed) || $trimmed[0] === '#') continue;
    
    // Count indentation
    $currentIndent = strlen($line) - strlen(ltrim($line));
    
    if (strpos($trimmed, ':') !== false) {
        $kv = explode(':', $trimmed, 2);
        $key = trim($kv[0]);
        $value = trim($kv[1], ' "\'');
        
        // Main sections
        if ($currentIndent === 0) {
            $currentSection = $key;
            $cv[$currentSection] = [];
            $currentItem = null;
        }
        // Properties of array items
        elseif ($currentIndent === 4 && $currentItem !== null) {
            $cv[$currentSection][$currentItem][$key] = $value;
        }
        // Handle "- key: value" format 
        elseif ($currentIndent === 2 && $trimmed[0] === '-') {
            if (in_array($currentSection, ['experience', 'education'])) {
                $currentItem = count($cv[$currentSection]);
                $cv[$currentSection][$currentItem] = [];
                
                // Extract key:value from "- key: value"
                $afterDash = trim(substr($trimmed, 1));
                if (strpos($afterDash, ':') !== false) {
                    $kv = explode(':', $afterDash, 2);
                    $key = trim($kv[0]);
                    $val = trim($kv[1], ' "\'');
                    $cv[$currentSection][$currentItem][$key] = $val;
                }
            }
        }
        // Skills categories or personalInfo
        elseif ($currentIndent === 2) {
            if ($currentSection === 'skills') {
                // Parse inline array for skills
                if (!empty($value) && strpos($value, '[') !== false) {
                    $arrayContent = trim($value, '[]');
                    $skills = array_map(function($item) {
                        return trim($item, ' "\'');
                    }, explode(',', $arrayContent));
                    $cv[$currentSection][$key] = $skills;
                }
            } elseif ($currentSection === 'personalInfo') {
                $cv[$currentSection][$key] = $value;
            }
        }
    }
    // Handle array items
    elseif ($trimmed[0] === '-') {
        $value = trim(substr($trimmed, 1), ' "\'');
        
        if ($currentIndent === 2) {
            if (in_array($currentSection, ['experience', 'education'])) {
                $currentItem = count($cv[$currentSection]);
                $cv[$currentSection][$currentItem] = [];
                
                // Check if this line also has a key:value after the dash
                if (strpos($value, ':') !== false) {
                    $kv = explode(':', $value, 2);
                    $key = trim($kv[0]);
                    $val = trim($kv[1], ' "\'');
                    $cv[$currentSection][$currentItem][$key] = $val;
                }
            } else {
                // Simple arrays like services
                $cv[$currentSection][] = $value;
            }
        }
    }
}

// Debug: uncomment to see parsed data
// echo '<pre>CV parsed data:'; var_dump($cv); echo '</pre>'; exit;

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
            <?php if (isset($cv['skills'])): ?>
                <?php foreach ($cv['skills'] as $category => $skills): ?>
                    <div class="skill-category">
                        <h4><?= ucfirst($category) ?></h4>
                        <div class="tags">
                            <?php if (is_array($skills)): ?>
                                <?php foreach ($skills as $skill): ?>
                                    <span class="tag"><?= htmlspecialchars($skill) ?></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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