---
title: "From Next.js to PHP in 1 Day with AI"
publishDate: "2025-08-25"
slug: "from-nextjs-to-php-in-1-day-with-ai-140kb-4kb"
excerpt: "I rebuilt my personal site from Next.js to PHP in one day using AI. The result: a 140KB bundle shrunk to 4KB and a much simpler stack."
readingTime: 3
tags: ["nextjs"]
---

I had my personal site in Next.js. It was fine, modern, all nice... but for a super simple page it was too much motor for so little bike. I got bit by the optimization bug and set myself a challenge: migrate it to flat PHP in one day, with the help of AI (Claude Code). Result: from 140 KB to 4 KB and the load dropped from 1.5 s to 0.8 s. In the video I show you the step by step and the before / after in Lighthouse.

## What I did and why

### When a framework is too big for you

Next.js is a blast, but my site was a static landing page with a couple of pages. A lot of JavaScript for something that is basically HTML and text. This overload was felt in the bundle and in the client's hydration.

### The plan of attack

* Remove all JS that is not essential.
* Go back to minimal HTML + CSS and use PHP only for includes and simple routing.
* No build step, no dependencies, no magic.
* Keep it easy to self-host on a VPS.

### The hand of AI (Claude Code)

I used Claude Code as a co-pilot:

* I passed it Next.js components and it returned the equivalent HTML/CSS.
* Rewrote styles to plain CSS, without Tailwind or libraries.
* Helped me put together PHP includes and minimalist routing.

It's not that AI works miracles alone, but it speeds up the refactoring a lot when you know where you want to go.

### Optimizations that added the most

* Zero JavaScript on first load.
* System fonts (bye external sources).
* Minimal CSS, inline in the home.
* Inline SVGs instead of icon libraries.
* Images optimized and only when necessary.
* Aggressive caching and compression enabled on the server.

### The numbers

* Weight: from 140 KB to 4 KB.
* Charging time: ~1.5 s to ~0.8 s.
* Lighthouse: everything greener and more stable, without depending on the client's runtime.

### The skeleton in PHP (simple and effective)

```
<?php
// public/index.php
$routes = [
  '/' => 'pages/home.php',
  '/about' => 'pages/about.php',
];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = $routes[$uri] ?? 'pages/404.php';

include 'partials/head.php';
include $file;
include 'partials/footer.php';
```

With that I already have includes, basic routing and zero dependencies. If tomorrow I want to add a form or a bit of progressive interactivity, I add it as needed.

### When is something like this appropriate?

* You have an informative site or a minimal landing page.
* You want very low loading times.
* You value simplicity, control and low hosting costs.
* You do not need framework features (complex SSR, heavy dynamic routing, advanced data fetching).

Isn't it convenient? When your app does need all that (and more), or your team is already built on the React/Next ecosystem and provides real value.

##Video

## Closing

It is not an anti-frameworks crusade. It is an invitation to measure and choose the right tool. Sometimes the best optimization is to delete things. If your site can live without all the machinery, try this minimalist approach: simple, fast and cheap to maintain. And if you like it, we'll build it together. See you in the video.

---

> Original article in Spanish: [De Next.js a PHP en 1 día (con IA) 🚀 140KB → 4KB](https://codigomate.com/de-next-js-a-php-en-1-dia-con-ia-%f0%9f%9a%80-140kb-%e2%86%92-4kb/)