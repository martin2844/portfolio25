<?php

function component($name, $props = []) {
    $fn = "render_{$name}";
    if (!function_exists($fn)) return '';
    return $fn($props);
}

function render_tag_list($tags, $linkBase = null) {
    if (empty($tags)) return '';
    $html = '<div class="tags">';
    foreach ($tags as $tag) {
        $label = is_array($tag) ? ($tag['tag'] ?? $tag[0] ?? '') : trim($tag, '" \'');
        if ($linkBase) {
            $slug = is_array($tag) ? ($tag['slug'] ?? DataLoader::normalizeTag($label)) : DataLoader::normalizeTag($label);
            $html .= '<a href="' . htmlspecialchars($linkBase . '?tag=' . urlencode($slug)) . '" class="tag">' . htmlspecialchars($label) . '</a>';
        } else {
            $html .= '<span class="tag">' . htmlspecialchars($label) . '</span>';
        }
    }
    $html .= '</div>';
    return $html;
}

function render_archive($props) {
    $archive = $props['archive'] ?? [];
    $baseUrl = $props['baseUrl'] ?? '/blog';
    $currentYear = $props['year'] ?? null;
    $currentMonth = $props['month'] ?? null;
    
    if (empty($archive)) return '';
    
    $html = '<div class="archive-widget">';
    foreach ($archive as $year => $months) {
        $html .= '<div class="archive-year">';
        $html .= '<div class="archive-year-title">' . htmlspecialchars($year) . '</div>';
        $html .= '<ul class="archive-months">';
        foreach ($months as $month) {
            $active = ($currentYear == $month['year'] && $currentMonth == $month['month']) ? ' active' : '';
            $url = $baseUrl . '?year=' . $month['year'] . '&month=' . $month['month'];
            $html .= '<li class="archive-month' . $active . '">';
            $html .= '<a href="' . htmlspecialchars($url) . '">' . htmlspecialchars($month['label']) . ' <span class="archive-count">(' . $month['count'] . ')</span></a>';
            $html .= '</li>';
        }
        $html .= '</ul></div>';
    }
    $html .= '</div>';
    return $html;
}

function render_search_form($props) {
    $action = $props['action'] ?? '/blog';
    $query = $props['query'] ?? '';
    $placeholder = $props['placeholder'] ?? 'Search posts...';
    return '<form class="search-form" method="get" action="' . htmlspecialchars($action) . '">' .
        '<input type="search" name="q" value="' . htmlspecialchars($query) . '" placeholder="' . htmlspecialchars($placeholder) . '" aria-label="Search">' .
        '<button type="submit" class="btn">Search</button>' .
        '</form>';
}

function render_tag_cloud($props) {
    $tags = $props['tags'] ?? [];
    $current = $props['current'] ?? '';
    $baseUrl = $props['baseUrl'] ?? '/blog';
    if (empty($tags)) return '';
    
    $maxCount = max(array_map(function($t) { return $t['count'] ?? 1; }, $tags)) ?: 1;
    $html = '<div class="tag-cloud">';
    foreach ($tags as $tag) {
        $slug = $tag['slug'] ?? DataLoader::normalizeTag($tag['tag']);
        $label = $tag['tag'];
        $count = $tag['count'] ?? 0;
        $active = DataLoader::normalizeTag($current) === $slug ? ' active' : '';
        $sizeClass = 'size-' . max(1, min(5, ceil(($count / $maxCount) * 5)));
        $html .= '<a href="' . htmlspecialchars($baseUrl . '?tag=' . urlencode($slug)) . '" class="tag-cloud-item ' . $sizeClass . $active . '">' .
            htmlspecialchars($label) . ' <span class="tag-cloud-count">' . $count . '</span></a>';
    }
    $html .= '</div>';
    return $html;
}

function render_active_filters($props) {
    $items = [];
    $baseUrl = $props['baseUrl'] ?? '/blog';
    $query = $props['query'] ?? '';
    $tag = $props['tag'] ?? '';
    $year = $props['year'] ?? '';
    $month = $props['month'] ?? '';
    $day = $props['day'] ?? '';
    
    if ($query) {
        $items[] = ['label' => 'Search: ' . $query, 'url' => $baseUrl];
    }
    if ($tag) {
        $items[] = ['label' => 'Tag: ' . $tag, 'url' => $baseUrl];
    }
    if ($year && $month && $day) {
        $items[] = ['label' => 'Date: ' . date('M j, Y', strtotime("{$year}-{$month}-{$day}")), 'url' => $baseUrl];
    } elseif ($year && $month) {
        $items[] = ['label' => 'Month: ' . date('F Y', strtotime("{$year}-{$month}-01")), 'url' => $baseUrl];
    }
    
    if (empty($items)) return '';
    $html = '<div class="active-filters">';
    foreach ($items as $item) {
        $html .= '<a href="' . htmlspecialchars($item['url']) . '" class="active-filter">' . htmlspecialchars($item['label']) . ' <span class="remove">×</span></a>';
    }
    $html .= '</div>';
    return $html;
}

function render_calendar($props) {
    $year = (int)($props['year'] ?? date('Y'));
    $month = (int)($props['month'] ?? date('n'));
    $days = $props['days'] ?? [];
    $baseUrl = $props['baseUrl'] ?? '/blog';
    
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = date('t', $firstDay);
    $startWeekday = (int)date('w', $firstDay);
    $monthLabel = date('F Y', $firstDay);
    
    $prevMonth = $month - 1;
    $prevYear = $year;
    if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }
    $nextMonth = $month + 1;
    $nextYear = $year;
    if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }
    
    $html = '<div class="calendar-widget">';
    $html .= '<div class="calendar-header">';
    $html .= '<a href="' . htmlspecialchars($baseUrl . '?year=' . $prevYear . '&month=' . $prevMonth) . '" class="calendar-nav" aria-label="Previous month">←</a>';
    $html .= '<span class="calendar-title">' . $monthLabel . '</span>';
    $html .= '<a href="' . htmlspecialchars($baseUrl . '?year=' . $nextYear . '&month=' . $nextMonth) . '" class="calendar-nav" aria-label="Next month">→</a>';
    $html .= '</div>';
    
    $html .= '<table class="calendar">';
    $html .= '<thead><tr><th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th></tr></thead>';
    $html .= '<tbody><tr>';
    
    for ($i = 0; $i < $startWeekday; $i++) {
        $html .= '<td class="empty"></td>';
    }
    
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $cellDay = ($startWeekday + $day - 1) % 7;
        if ($cellDay === 0 && $day > 1) {
            $html .= '</tr><tr>';
        }
        
        $hasPosts = !empty($days[$day]);
        $class = $hasPosts ? 'has-posts' : '';
        if ($hasPosts) {
            $title = implode('; ', array_map(function($item) {
                return $item['frontmatter']['title'] ?? '';
            }, $days[$day]));
            $html .= '<td class="' . $class . '"><a href="' . htmlspecialchars($baseUrl . '?year=' . $year . '&month=' . $month . '&day=' . $day) . '" title="' . htmlspecialchars($title) . '">' . $day . '</a></td>';
        } else {
            $html .= '<td class="' . $class . '"><span>' . $day . '</span></td>';
        }
    }
    
    $remaining = (7 - (($startWeekday + $daysInMonth) % 7)) % 7;
    for ($i = 0; $i < $remaining; $i++) {
        $html .= '<td class="empty"></td>';
    }
    
    $html .= '</tr></tbody></table></div>';
    return $html;
}

function render_card($props) {
    $title = $props['title'] ?? '';
    $meta = $props['meta'] ?? '';
    $body = $props['body'] ?? '';
    $tags = $props['tags'] ?? [];
    $href = $props['href'] ?? '';
    $target = !empty($props['external']) ? ' target="_blank" rel="noopener noreferrer"' : '';
    
    $tagHtml = render_tag_list($tags);
    $content = '';
    if ($title) $content .= '<h3>' . htmlspecialchars($title) . '</h3>';
    if ($meta) $content .= '<div class="meta">' . $meta . '</div>';
    if ($body) $content .= '<p>' . $body . '</p>';
    if ($tagHtml) $content .= $tagHtml;
    
    if ($href) {
        return '<a href="' . htmlspecialchars($href) . '" class="card card-link"' . $target . '>' . $content . '</a>';
    }
    return '<div class="card">' . $content . '</div>';
}

function render_post_preview($props) {
    $item = $props['item'];
    $basePath = $props['basePath'] ?? '/blog';
    $fm = $item['frontmatter'];
    $title = $fm['title'] ?? '';
    $date = !empty($fm['publishDate']) ? formatDate($fm['publishDate']) : '';
    $reading = !empty($fm['readingTime']) ? $fm['readingTime'] . 'min read' : '';
    $meta = $date . ($date && $reading ? ' • ' : '') . $reading;
    $tags = $fm['tags'] ?? [];
    
    return render_card([
        'title' => $title,
        'meta' => $meta,
        'body' => $fm['excerpt'] ?? '',
        'tags' => $tags,
        'href' => $basePath . '/' . ($fm['slug'] ?? '')
    ]);
}

function render_stat_card($props) {
    $number = $props['number'] ?? '0';
    $label = $props['label'] ?? '';
    return '<div class="stat-card"><div class="stat-number">' . $number . '</div><div class="stat-label">' . htmlspecialchars($label) . '</div></div>';
}

function render_section_header($props) {
    $title = $props['title'] ?? '';
    $href = $props['href'] ?? '';
    $linkText = $props['linkText'] ?? 'view all →';
    $link = $href ? '<a href="' . htmlspecialchars($href) . '" class="section-link">' . htmlspecialchars($linkText) . '</a>' : '';
    return '<div class="section-header"><h2>' . htmlspecialchars($title) . '</h2>' . $link . '</div>';
}

function render_back_button($props) {
    $href = $props['href'] ?? '/';
    $label = $props['label'] ?? 'back';
    return '<a href="' . htmlspecialchars($href) . '" class="back-btn">← ' . htmlspecialchars($label) . '</a>';
}

function render_button($props) {
    $href = $props['href'] ?? '#';
    $label = $props['label'] ?? '';
    $external = !empty($props['external']);
    $target = $external ? ' target="_blank" rel="noopener noreferrer"' : '';
    return '<a href="' . htmlspecialchars($href) . '" class="btn"' . $target . '>' . htmlspecialchars($label) . '</a>';
}

function render_project_card($props) {
    $item = $props['item'];
    $name = $item['name'] ?? '';
    $desc = $item['description'] ?? '';
    $tech = $item['technologies'] ?? [];
    $image = $item['image'] ?? '';
    $links = [];
    if (!empty($item['githubLink'])) $links[] = ['href' => $item['githubLink'], 'label' => 'github', 'external' => true];
    if (!empty($item['websiteLink'])) $links[] = ['href' => $item['websiteLink'], 'label' => 'website', 'external' => true];
    
$priority = !empty($props['priority']);
    $loadingAttr = $priority ? 'fetchpriority="high"' : 'loading="lazy"';
    
    $html = '';
    if ($image) {
        $html .= '<div class="project-image"><img src="' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($name) . '" width="1200" height="630" ' . $loadingAttr . '></div>';
    }
    $html .= '<h3>' . htmlspecialchars($name) . '</h3>';
    $html .= '<p>' . htmlspecialchars($desc) . '</p>';
    $html .= render_tag_list($tech);
    if ($links) {
        $html .= '<div class="links">';
        foreach ($links as $link) {
            $html .= render_button($link);
        }
        $html .= '</div>';
    }
    
    return '<article class="card project-card">' . $html . '</article>';
}

function render_experience_item($props) {
    $exp = $props['item'];
    $html = '<h4>' . htmlspecialchars($exp['position'] ?? '') . '</h4>';
    $html .= '<div class="meta">' . htmlspecialchars($exp['company'] ?? '') . ' • ' . htmlspecialchars($exp['period'] ?? '') . '</div>';
    if (!empty($exp['description'])) {
        $html .= '<p>' . htmlspecialchars($exp['description']) . '</p>';
    }
    return '<div class="timeline-item">' . $html . '</div>';
}

function render_education_item($props) {
    $edu = $props['item'];
    $html = '<h4>' . htmlspecialchars($edu['degree'] ?? '') . '</h4>';
    $html .= '<div class="meta">' . htmlspecialchars($edu['institution'] ?? '') . ' • ' . htmlspecialchars($edu['period'] ?? '') . '</div>';
    if (!empty($edu['status'])) {
        $html .= '<div class="status">' . htmlspecialchars($edu['status']) . '</div>';
    }
    return '<div class="timeline-item">' . $html . '</div>';
}

function render_skill_category($props) {
    $title = $props['title'] ?? '';
    $skills = $props['skills'] ?? [];
    $html = '<h4>' . htmlspecialchars(ucfirst($title)) . '</h4>';
    $html .= render_tag_list($skills);
    return '<div class="skill-category">' . $html . '</div>';
}
