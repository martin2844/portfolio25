# SEO Audit Report — martinchammah.dev

**Audited:** 2026-06-29  
**Scope:** Full technical, on-page, content, structured-data, and performance audit of the live site and source code.  
**Pages analyzed:** 70 URLs from the XML sitemap plus filtered/noindex variants and 404 behavior.

---

## Executive Summary

The site has a solid foundation: clean PHP routing, fast TTFB, good security headers, valid JSON-LD on articles, and a complete XML sitemap. However, there are several SEO issues that need immediate attention, especially around duplicate content, indexation signals, and image optimization.

**Top 5 priorities:**
1. Fix `www` vs non-`www` duplicate content (both return 200).
2. Redirect trailing-slash variants or consolidate canonicals.
3. Compress/resize image-heavy posts (one post ships 15 MB of images).
4. Fix broken links caused by `<https://...>` markdown syntax.
5. Add `noindex` to the 404 page and avoid self-canonicalizing filtered pages.

---

## 1. Technical SEO

### 1.1 Domain canonicalization — CRITICAL
- `https://martinchammah.dev/` returns **200** with canonical `https://martinchammah.dev/`.
- `https://www.martinchammah.dev/` also returns **200** with canonical `https://www.martinchammah.dev/`.
- **Issue:** Google sees two duplicate sites. No 301 redirects either direction.
- **Fix:** Pick one version (recommend non-www) and 301 all `www` traffic to it. Update sitemap and all internal links to match.

### 1.2 HTTP to HTTPS redirect — HIGH
- `http://martinchammah.dev/` returns **307 Temporary Redirect** to HTTPS.
- **Issue:** 307 is temporary; SEO value is not fully passed. Use **301 Permanent Redirect**.

### 1.3 Trailing slashes — HIGH
- `/blog` and `/blog/` both return **200** with different self-referencing canonicals.
- Same for `/notes`, `/portfolio`, `/about`, `/cv`, and every blog/note post.
- **Issue:** Duplicate content and split ranking signals.
- **Fix:** Pick one URL style and 301 the other to it. The router already strips trailing slashes in `index.php`, but nginx serves both. Add a canonical redirect in nginx or route logic.

### 1.4 404 page — HIGH
- Non-existent pages return **404** status.
- However, the 404 template emits `robots: index, follow` and a self-referencing canonical to the 404 URL.
- **Issue:** 404 URLs can get indexed if linked externally.
- **Fix:** Set `noindex, follow` on the 404 page and remove the canonical (or point it to the home page).

### 1.5 Filtered / search pages — MEDIUM
- `/blog?tag=nextjs`, `/blog?q=test`, and date filters correctly use `noindex, follow`.
- But they also include a self-referencing canonical.
- **Issue:** Google recommends not mixing `noindex` with a self-referencing canonical.
- **Fix:** Either remove the canonical on filtered pages or change it to the clean `/blog` URL.

### 1.6 Robots.txt — GOOD
```
User-agent: *
Allow: /
Disallow: /public/projects/
Disallow: /includes/
Disallow: /templates/
Disallow: /pages/
Disallow: /data/
Sitemap: https://martinchammah.dev/sitemap.xml
```
- Sensitive directories are blocked.
- **Note:** `/public/projects/` is disallowed. Since portfolio project images live there, they won't appear in image search. This is likely intentional but worth confirming.

### 1.7 XML Sitemap — GOOD
- 70 URLs submitted.
- Includes 6 static pages, 51 posts, 13 notes.
- `<lastmod>` is present on posts/notes.
- **Minor issue:** Sitemap uses non-www URLs while `www` is also indexable. Consolidate domain choice first.

### 1.8 URL structure — GOOD
- Clean, keyword-rich slugs.
- No query parameters in canonical article URLs.

### 1.9 Security headers — GOOD
All pages include:
- `X-Frame-Options: DENY`
- `X-Content-Type-Options: nosniff`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy`
- `Content-Security-Policy`

---

## 2. On-Page SEO

### 2.1 Title tags — MEDIUM
- **Home:** "Home | Martin Chammah | Software Engineer" (41 chars) — good.
- **Many articles exceed 60 characters.** Examples:
  - "Top 3 alternatives to Claude Code (2026) + cheap + 3x usage | Martin Chammah | Software Engineer" — 96 chars
  - "I made an app… and now people all over the world use it (but no one pays) | Martin Chammah | Software Engineer" — 110 chars
  - "You don't need Vercel! Self-Host Coolify + Nextjs (or any other thing) | Martin Chammah | Software Engineer" — 134 chars
- **Some titles are too short (< 30 chars):**
  - "Lucky Times" effectively 48 chars (with suffix)
  - "Palindrome" 47 chars
  - "Two Sum" 44 chars
- **Fix:** Shorten long titles to ~50-60 chars. Expand very short titles with context (e.g., "Two Sum: JavaScript LeetCode Solution").

### 2.2 Meta descriptions — MEDIUM
- **Many descriptions are exactly 203 characters**, which suggests truncation from source frontmatter rather than handcrafted summaries.
- **Some descriptions are too short:**
  - Notes `/real-value` — 29 chars
  - Notes `/rename-files` — 19 chars
  - `/blog/lucky-times` — 47 chars
  - `/blog/palindrome` — 54 chars
- **Fix:** Write unique descriptions between 70-160 characters for every page. Do not rely on automatic truncation.

### 2.3 Heading hierarchy — MEDIUM
- **Most pages have exactly one H1.** Good.
- **Skipped heading levels:**
  - `/about`: H1 → H3 (Skills, Interests) — missing H2.
  - `/cv`: H2 → H4 (job titles / skill categories) — missing H3.
  - `/blog/china-2025-changed-my-life`: H1 → H3 (subtitle) → H2 sections.
  - Several posts jump from H1 directly to H3/H4.
- **Fix:** Maintain logical H1→H2→H3 progression. The markdown parser converts the first H1 to nothing and remaining H1s to H2; review source markdown to use H2 as the first content heading.

### 2.4 Posts without H2 headings — MEDIUM
The following posts have no H2 structure (often just images or very short):
- `/blog/you-have-to-go-to-japan`
- `/blog/migrate-wordpress-to-coolify-dockerized`
- `/blog/lucky-times`
- `/blog/roman-numerals-js`
- `/blog/palindrome`
- `/blog/two-sum`
- `/blog/wordpress-deploy-to-cloud-compute`
- Several notes
- **Fix:** Add descriptive H2s to give structure and target long-tail queries.

### 2.5 Image alt text — GOOD
- No images are missing `alt` attributes site-wide.
- **Minor:** some alt texts are generic (e.g., project name only). Consider descriptive alt text for key content images.

### 2.6 Internal links — CRITICAL
- **7 broken internal links** found, all caused by `<https://...>` markdown syntax being parsed incorrectly:
  - `/blog/%3Chttps://youtu.be/kzAVaNgXuIk%3E`
  - `/blog/%3Chttps://youtu.be/DAaXdNrcTV0%3E`
  - `/blog/%3Chttps://youtu.be/PCOdoHA0CIg%3E`
  - `/blog/%3Chttps://www.youtube.com/watch?v=DAaXdNrcTV0%3E`
  - `/blog/%3Chttps://www.youtube.com/watch?v=Hz_Jr2I_n8w&t=12s&ab_channel=CodigoMate%3E`
  - `/blog/%3Chttps://www.youtube.com/watch?v=Hz_Jr2I_n8w&ab_channel=CodigoMate%3E`
  - `/blog/%3Chttps://www.youtube.com/watch?v=dzEohD1nUOw%3E`
- **Fix:** Update the markdown parser (`includes/functions.php`) to strip angle brackets around autolinks, or edit the source `.md` files to use standard `[text](url)` syntax without `<>`.

### 2.7 Thin content — MEDIUM
Pages with fewer than 300 words of body text:
- `/portfolio` — 174 words
- `/about` — 275 words
- `/cv` — 281 words
- `/blog/palindrome` — 191 words
- `/notes/real-value` — 100 words
- `/notes/rename-files` — 111 words
- `/notes/introduction-to-json-and-apis` — 195 words
- `/notes/your-first-php-server` — 249 words
- **Fix:** Expand these pages where ranking is desired. For notes that are intentionally short snippets, consider whether they should be indexed at all.

### 2.8 Duplicate titles — GOOD
- No duplicate titles found across indexed pages.

---

## 3. Structured Data & Social

### 3.1 JSON-LD — GOOD
- Home page has `Person` schema.
- Blog posts have `BlogPosting` + `BreadcrumbList`.
- Notes have `Article` + `BreadcrumbList`.
- All JSON-LD parses correctly.
- **Suggestion:** Add `image` property to `BlogPosting` / `Article` schemas when a post has a cover image. This helps rich results.

### 3.2 Open Graph / Twitter Cards — GOOD
- All pages have `og:title`, `og:description`, `og:image`, `og:type`, `og:url`, `twitter:card`.
- `og:image` is `1200x630` — correct dimensions.
- **Issue:** every page uses the same default `og-image.jpg`. Per-page social images would improve CTR.
- **Fix:** Use post cover images or generate per-page OG images.

### 3.3 Favicon — GOOD
- SVG + ICO variants present.
- ICO is 32x32.
- **Suggestion:** consider adding a 180x180 Apple touch icon for bookmarks/home-screen.

---

## 4. Performance & Core Web Vitals

### 4.1 Server response — GOOD
- TTFB ~50-260 ms.
- HTML is gzip-compressed.
- Cache headers: static assets cached for 1 year, PHP pages cached for 5 minutes.

### 4.2 Page sizes — MOSTLY GOOD
- Home: ~8 KB HTML (4.8 KB gzipped)
- Blog index: ~39 KB HTML (18 KB gzipped)
- Typical post: ~8-20 KB HTML

### 4.3 Image weight — CRITICAL
- `/blog/china-2025-changed-my-life` loads **41 images totaling ~15 MB**.
- `/blog/you-have-to-go-to-japan` loads **19 images totaling ~2.65 MB**.
- `/blog/from-payo-to-pesos-safely-and-quickly-hello-belo` loads **10 images totaling ~2.08 MB**.
- Individual PNGs exceed 1.5 MB (e.g., `taxi-didi-re.png` 2.0 MB, `tianmen-wechat-mini-app-re.png` 1.6 MB).
- **Impact:** LCP, INP, and overall load time will fail Core Web Vitals thresholds on mobile.
- **Fix:**
  - Convert large PNGs to WebP/AVIF.
  - Resize images to max 1200 px width (most are already 1000 px, but PNG bloat is the issue).
  - Add `width`/`height` attributes and `loading="lazy"` to content images (portfolio already does this).
  - Consider a lightweight image CDN or on-the-fly resize endpoint.

### 4.4 Font loading — GOOD
- Fonts are preloaded with `crossorigin`.
- Display font (`ATSeasonSansVF.woff2`) is 135 KB; body fonts ~70 KB each.
- **Suggestion:** subset the variable display font if only Latin characters are used.

### 4.5 CSS — GOOD
- Single stylesheet, ~23 KB, gzipped, cached.
- No render-blocking third-party scripts.

---

## 5. Indexation & Crawlability

- Sitemap is discoverable via robots.txt and contains 70 URLs.
- No `noindex` on main pages or articles.
- Tag/search/date filtered pages are correctly `noindex`.
- `Disallow: /data/` in robots.txt blocks raw markdown, good.
- **Issue:** no RSS/Atom feed linked. Add a feed for blog posts to improve discoverability and syndication.

---

## 6. Recommendations (Prioritized Action Plan)

### P0 — Fix immediately
1. **Consolidate `www` vs non-`www`.** 301 redirect `www` to root domain (or vice versa) in nginx.
2. **Consolidate trailing slashes.** 301 `/page/` to `/page` or vice versa; ensure canonicals match.
3. **Fix broken autolinks.** Update markdown parser or source files so `<https://...>` renders as external links, not relative 404s.
4. **Compress image-heavy posts.** Convert PNGs to WebP, lazy-load below-fold images, and add explicit `width`/`height`.

### P1 — High impact
5. **Fix 404 indexation.** Set `noindex, follow` and remove canonical on 404 template.
6. **Clean filtered-page canonicals.** Remove canonical or point to `/blog` on `noindex` filtered pages.
7. **Optimize title tags.** Keep between 30-60 characters; rewrite long/short titles.
8. **Optimize meta descriptions.** Write unique 70-160 char descriptions for every indexed page.
9. **Fix heading hierarchy.** Add missing H2s on `/about`, `/cv`, and posts that skip levels.

### P2 — Nice to have
10. **Add per-page OG images.** Use post cover images or generate dynamic OG cards.
11. **Add Apple touch icon and web app manifest.**
12. **Add RSS/Atom feed** and link it in `<head>`.
13. **HTTP→HTTPS 301** instead of 307.
14. **Schema enhancements:** add `image` to article schema and `dateModified` when posts are updated.

---

## Quick Health Score

| Category         | Score | Notes                                           |
|------------------|-------|-------------------------------------------------|
| Technical SEO    | 6/10  | Duplicate content via www + trailing slash.     |
| On-Page SEO      | 6/10  | Titles/descriptions need optimization.          |
| Content          | 7/10  | Good volume; some thin pages; headings need work.|
| Structured Data  | 8/10  | Valid JSON-LD; could add article images.        |
| Performance      | 5/10  | Fast server, but image bloat kills mobile CWV.  |
| Crawlability     | 8/10  | Clean sitemap and robots.txt.                   |
| **Overall**      | **6.5/10** | Solid base; fix duplicates and images first. |

---

*Report generated by automated crawl + manual source-code review of martinchammah.dev.*
