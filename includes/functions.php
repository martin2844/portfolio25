<?php

class MarkdownParser {
    public static function parseFile($file) {
        if (!file_exists($file)) return null;
        
        $content = file_get_contents($file);
        $parts = explode('---', $content, 3);
        
        if (count($parts) < 3) return null;
        
        $frontmatter = [];
        $yaml_lines = explode("\n", trim($parts[1]));
        foreach ($yaml_lines as $line) {
            if (strpos($line, ':') !== false) {
                $kv = explode(':', $line, 2);
                $key = trim($kv[0]);
                $raw = trim($kv[1]);
                
                // Handle inline arrays: [a, b, c]
                if (strpos($raw, '[') === 0 && strrpos($raw, ']') === strlen($raw) - 1) {
                    $inner = trim($raw, '[]');
                    if ($inner === '') {
                        $frontmatter[$key] = [];
                    } else {
                        $frontmatter[$key] = array_map(function($item) {
                            return trim($item, ' "\'');
                        }, explode(',', $inner));
                    }
                } else {
                    $frontmatter[$key] = trim($raw, ' "\'');
                }
            }
        }
        
        $markdown = trim($parts[2]);
        $html = self::markdownToHtml($markdown);
        
        return [
            'frontmatter' => $frontmatter,
            'content' => $html
        ];
    }
    
    public static function markdownToHtml($markdown) {
        // Use Parsedown for standard Markdown (lists, blockquotes, HRs, tables, etc.)
        if (!class_exists('Parsedown')) {
            require_once __DIR__ . '/Parsedown.php';
        }
        $parsedown = new Parsedown();
        $parsedown->setMarkupEscaped(false);
        $parsedown->setBreaksEnabled(false);
        $html = $parsedown->text($markdown);

        // Remove the first H1 so it does not duplicate the page title
        $html = preg_replace('/<h1[^>]*>.*?<\/h1>/s', '', $html, 1);

        // Ensure <pre> carries the same language class as its <code> child for Prism styling
        $html = preg_replace_callback('/<pre><code(?:\s+class="([^"]*)")?>/', function($matches) {
            $class = isset($matches[1]) ? $matches[1] : '';
            return $class ? "<pre class=\"{$class}\"><code class=\"{$class}\">" : '<pre><code>';
        }, $html);

        // Post-process images: add real dimensions and lazy loading
        $html = preg_replace_callback('/<img([^>]*)>/i', function($matches) {
            $attrsStr = $matches[1];

            // Parse existing attributes
            $attrs = [];
            preg_match_all('/(\w+)="([^"]*)"/', $attrsStr, $attrMatches, PREG_SET_ORDER);
            foreach ($attrMatches as $am) {
                $attrs[$am[1]] = $am[2];
            }

            $src = $attrs['src'] ?? '';
            if ($src && strpos($src, '/') === 0) {
                $localPath = __DIR__ . '/..' . $src;
                if (file_exists($localPath)) {
                    $size = @getimagesize($localPath);
                    if ($size) {
                        $attrs['width'] = (string) $size[0];
                        $attrs['height'] = (string) $size[1];
                    }
                }
            }
            $attrs['loading'] = 'lazy';

            // Preserve original attribute order roughly while adding new ones
            $attrOut = '';
            foreach (['src', 'alt', 'title', 'width', 'height', 'loading'] as $key) {
                if (isset($attrs[$key])) {
                    $attrOut .= ' ' . $key . '="' . e($attrs[$key]) . '"';
                }
            }
            // Add any other original attributes we did not explicitly handle
            foreach ($attrs as $key => $value) {
                if (!in_array($key, ['src', 'alt', 'title', 'width', 'height', 'loading'])) {
                    $attrOut .= ' ' . $key . '="' . e($value) . '"';
                }
            }

            return '<img' . $attrOut . '>';
        }, $html);

        return $html;
    }
}

class YamlParser {
    /**
     * Parse a simple YAML file into arrays.
     * Supports: key: value, nested objects, dash arrays, inline arrays.
     */
    public static function parseFile($file) {
        if (!file_exists($file)) return null;
        return self::parse(file_get_contents($file));
    }
    
    public static function parse($content) {
        $lines = explode("\n", $content);
        $data = [];
        $stack = [&$data];
        $indentStack = [-1];
        $lastKeys = [null];
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '' || $trimmed[0] === '#') continue;
            
            $indent = strlen($line) - strlen(ltrim($line));
            
            // Pop stack to current indent level
            while (count($indentStack) > 1 && $indent <= end($indentStack)) {
                array_pop($stack);
                array_pop($indentStack);
                array_pop($lastKeys);
            }
            
            $current = &$stack[count($stack) - 1];
            
            if ($trimmed[0] === '-') {
                // Array item
                $rest = trim(substr($trimmed, 1));
                
                if (!is_array($current)) {
                    $current = [];
                }
                
                if (strpos($rest, ':') !== false) {
                    // Object in array: "- key: value"
                    $kv = explode(':', $rest, 2);
                    $key = trim($kv[0]);
                    $value = self::castValue(trim($kv[1]));
                    $current[] = [$key => $value];
                    $lastKeys[count($lastKeys) - 1] = count($current) - 1;
                    $stack[] = &$current[count($current) - 1];
                    $indentStack[] = $indent;
                    $lastKeys[] = $key;
                } else {
                    // Scalar array item
                    $current[] = self::castValue($rest);
                    $lastKeys[count($lastKeys) - 1] = count($current) - 1;
                }
            } elseif (strpos($trimmed, ':') !== false) {
                // Key-value
                $kv = explode(':', $trimmed, 2);
                $key = trim($kv[0]);
                $value = trim($kv[1]);
                
                if ($value === '') {
                    // Nested object starts here
                    if (!isset($current[$key]) || !is_array($current[$key])) {
                        $current[$key] = [];
                    }
                    $stack[] = &$current[$key];
                    $indentStack[] = $indent;
                    $lastKeys[] = $key;
                } else {
                    $current[$key] = self::castValue($value);
                    $lastKeys[count($lastKeys) - 1] = $key;
                }
            }
        }
        
        return $data;
    }
    
    private static function castValue($value) {
        $value = trim($value, ' "\'');
        
        // Inline array: [a, b, c]
        if (strpos($value, '[') === 0 && strrpos($value, ']') === strlen($value) - 1) {
            $inner = trim($value, '[]');
            if ($inner === '') return [];
            return array_map(function($item) {
                return self::castValue(trim($item));
            }, explode(',', $inner));
        }
        
        // Booleans
        $lower = strtolower($value);
        if ($lower === 'true') return true;
        if ($lower === 'false') return false;
        if ($lower === 'null' || $lower === '~') return null;
        
        // Numbers
        if (is_numeric($value)) {
            return strpos($value, '.') !== false ? (float)$value : (int)$value;
        }
        
        return $value;
    }
}

class DataLoader {
    public static function getPosts($limit = null) {
        $posts = self::loadItems(__DIR__ . '/../data/posts/');
        return $limit ? array_slice($posts, 0, $limit) : $posts;
    }
    
    public static function getPost($slug) {
        $slug = basename(preg_replace('/[^a-z0-9_-]/i', '', (string)$slug));
        if ($slug === '') return null;
        $file = __DIR__ . "/../data/posts/{$slug}.md";
        return MarkdownParser::parseFile($file);
    }
    
    public static function getNotes($limit = null) {
        $notes = self::loadItems(__DIR__ . '/../data/notes/');
        return $limit ? array_slice($notes, 0, $limit) : $notes;
    }
    
    public static function getNote($slug) {
        $slug = basename(preg_replace('/[^a-z0-9_-]/i', '', (string)$slug));
        if ($slug === '') return null;
        $file = __DIR__ . "/../data/notes/{$slug}.md";
        return MarkdownParser::parseFile($file);
    }
    
    private static function loadItems($dir) {
        $items = [];
        
        if (is_dir($dir)) {
            foreach (glob($dir . '*.md') as $file) {
                $item = MarkdownParser::parseFile($file);
                if ($item) {
                    $items[] = $item;
                }
            }
        }
        
        usort($items, function($a, $b) {
            return strtotime($b['frontmatter']['publishDate']) - strtotime($a['frontmatter']['publishDate']);
        });
        
        return $items;
    }
    
    public static function getAllTags($type = 'all') {
        $tags = [];
        $items = [];
        
        if ($type === 'posts' || $type === 'all') {
            $items = array_merge($items, self::getPosts());
        }
        if ($type === 'notes' || $type === 'all') {
            $items = array_merge($items, self::getNotes());
        }
        
        foreach ($items as $item) {
            $itemTags = $item['frontmatter']['tags'] ?? [];
            foreach ((array)$itemTags as $tag) {
                $normalized = self::normalizeTag($tag);
                if (!isset($tags[$normalized])) {
                    $tags[$normalized] = ['tag' => $tag, 'count' => 0, 'slug' => $normalized];
                }
                $tags[$normalized]['count']++;
            }
        }
        
        uksort($tags, 'strcasecmp');
        return $tags;
    }
    
    public static function normalizeTag($tag) {
        return strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($tag)));
    }
    
    public static function filterByTag($tag, $type = 'all') {
        $normalized = self::normalizeTag($tag);
        $items = [];
        
        if ($type === 'posts' || $type === 'all') {
            $items = array_merge($items, self::getPosts());
        }
        if ($type === 'notes' || $type === 'all') {
            $items = array_merge($items, self::getNotes());
        }
        
        return array_values(array_filter($items, function($item) use ($normalized) {
            $tags = $item['frontmatter']['tags'] ?? [];
            foreach ((array)$tags as $tag) {
                if (self::normalizeTag($tag) === $normalized) {
                    return true;
                }
            }
            return false;
        }));
    }
    
    public static function search($query, $type = 'all') {
        $query = trim($query);
        if ($query === '') {
            return [];
        }
        
        $terms = array_filter(preg_split('/\s+/', $query));
        $items = [];
        
        if ($type === 'posts' || $type === 'all') {
            $items = array_merge($items, self::getPosts());
        }
        if ($type === 'notes' || $type === 'all') {
            $items = array_merge($items, self::getNotes());
        }
        
        return array_values(array_filter($items, function($item) use ($terms) {
            $haystack = strtolower(implode(' ', [
                $item['frontmatter']['title'] ?? '',
                $item['frontmatter']['excerpt'] ?? '',
                strip_tags($item['html'] ?? '')
            ]));
            foreach ($terms as $term) {
                if (strpos($haystack, strtolower($term)) === false) {
                    return false;
                }
            }
            return true;
        }));
    }
    
    public static function getItemsByDate($year, $month = null, $day = null, $type = 'all') {
        $items = [];
        
        if ($type === 'posts' || $type === 'all') {
            $items = array_merge($items, self::getPosts());
        }
        if ($type === 'notes' || $type === 'all') {
            $items = array_merge($items, self::getNotes());
        }
        
        return array_values(array_filter($items, function($item) use ($year, $month, $day) {
            $date = $item['frontmatter']['publishDate'] ?? '';
            if (!$date) return false;
            $parts = explode('-', date('Y-m-d', strtotime($date)));
            if ($parts[0] !== strval($year)) return false;
            if ($month !== null && $parts[1] !== str_pad($month, 2, '0', STR_PAD_LEFT)) return false;
            if ($day !== null && $parts[2] !== str_pad($day, 2, '0', STR_PAD_LEFT)) return false;
            return true;
        }));
    }
    
    public static function getCalendarData($year = null, $month = null) {
        $year = $year ?: date('Y');
        $month = $month ?: date('n');
        $items = self::getItemsByDate($year, $month, null);
        
        $days = [];
        foreach ($items as $item) {
            $day = intval(date('j', strtotime($item['frontmatter']['publishDate'])));
            $days[$day][] = $item;
        }
        
        return [
            'year' => $year,
            'month' => $month,
            'days' => $days
        ];
    }
    
    public static function getArchiveYears() {
        $years = [];
        foreach (array_merge(self::getPosts(), self::getNotes()) as $item) {
            $date = $item['frontmatter']['publishDate'] ?? '';
            if ($date) {
                $years[date('Y', strtotime($date))] = true;
            }
        }
        krsort($years);
        return array_keys($years);
    }
    
    public static function getArchiveMonths($type = 'posts') {
        $items = [];
        if ($type === 'posts' || $type === 'all') {
            $items = array_merge($items, self::getPosts());
        }
        if ($type === 'notes' || $type === 'all') {
            $items = array_merge($items, self::getNotes());
        }
        
        $archive = [];
        foreach ($items as $item) {
            $date = $item['frontmatter']['publishDate'] ?? '';
            if (!$date) continue;
            $ts = strtotime($date);
            $year = date('Y', $ts);
            $month = date('n', $ts);
            $monthKey = date('m', $ts);
            
            if (!isset($archive[$year])) {
                $archive[$year] = [];
            }
            if (!isset($archive[$year][$monthKey])) {
                $archive[$year][$monthKey] = [
                    'year' => (int)$year,
                    'month' => (int)$month,
                    'label' => date('F', $ts),
                    'count' => 0
                ];
            }
            $archive[$year][$monthKey]['count']++;
        }
        
        krsort($archive);
        foreach ($archive as &$months) {
            krsort($months);
        }
        
        return $archive;
    }
    
    public static function getYamlData($file) {
        return YamlParser::parseFile(__DIR__ . "/../data/pages/{$file}");
    }
    
    public static function getAbout() {
        $file = __DIR__ . '/../data/pages/about.md';
        return MarkdownParser::parseFile($file);
    }
    
    public static function getPortfolio() {
        $data = self::getYamlData('portfolio.yaml');
        return $data['projects'] ?? [];
    }
    
    public static function getCv() {
        return self::getYamlData('cv.yaml');
    }
}

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
    }
}

function formatDate($date) {
    return date('M j, Y', strtotime($date));
}

function excerpt($text, $length = 200) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

function absoluteUrl($url) {
    if (empty($url)) return null;
    if (preg_match('#^https?://#i', $url)) return $url;
    if (strpos($url, '//') === 0) return 'https:' . $url;
    if (strpos($url, '/') !== 0) $url = '/' . $url;
    return 'https://martinchammah.dev' . $url;
}

function firstContentImage($html) {
    if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $matches)) {
        return $matches[1];
    }
    return null;
}
