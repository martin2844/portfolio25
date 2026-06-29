---
title: "n8n Advanced Workflows: Scraper to Loops"
publishDate: "2025-11-21"
slug: "advanced-n8n-workflows-scraper-if-merge-loops"
excerpt: "Level up n8n with real automation patterns: e-commerce scraping, IF branches, merge nodes and loops for pagination without everything exploding."
readingTime: 3
tags: ["n8n"]
---

In this video I went deep into n8n in “level 2” mode: e-commerce scraping, conditionals with IFs, merges to join branches, loops for pagination and handling of multiple items without everything exploding. I recorded it because several times they wrote to me that simple flows already work for them, but when there are arrays, bifurcations and pagination, trouble arises. Well, here's my way of ordering it and not dying trying.

## What are you going to see

### 1) Simple ecommerce scraping

* I start with a base flow: HTTP Request to bring in the HTML, and then extract the data with a parsing node (CSS/XPath selectors).
* I normalize fields with Set/Transform to leave each product as a neat item: title, price, URL, image, etc.
* Tip: I defined a User-Agent and realistic headers and added small delays so as not to burn the site. And always respect terms and robots.txt.

### 2) Conditions with the IF node

* Use IF to filter results (e.g. min/max price or keywords).
* IF is not only used to filter: it is also used to decide different enrichment routes (if there is stock, I stick to an API; if not, I cut corners).

### 3) Split and Merge data (branches that come and go)

* I make parallel branches for:
  + Enrich data (e.g. clean prices, parse currency).
  + Validate duplicates or complete missing information.
* With Merge one results:
  + Merge by position or by common field (id/url).
  + Pass Through to bring “the backpack” from one branch while the other does the hard work.
* Rule of thumb: confirm that the number of items per branch matches the Merge strategy you chose. If not, they misalign and you end up mixing pears with apples.

### 4) Loops and pagination in n8n

* To browse pages:
  + I set up a counter or test until there are no more results.
  + Use Split In Batches or the Loop node to iterate in blocks.
  + IF decides if we continue or cut.
* Tips:
  + Accumulate results with Merge (Append) or a Function/Item Lists to concatenate arrays between rounds.
  + Enter Sleep/Wait so they don't rate-limit you.
  + Log in the current page number: if it fails, you know where to pick up.

### 5) Data handling: arrays, items and transformation

* n8n works item by item: if you pass a raw array, several nodes don't know what to do.
* Therefore:
  + “Split out” from arrays to items when you need to process one by one.
  + “Aggregate” back if you want to save/send everything together.
  + Use Set/Rename/Keep/Remove Keys to maintain a consistent scheme.

### 6) Tips to avoid failures in complex flows

* Validate selectors: if they change the site's DOM, your scraper dies. Have a plan B or an IF for “I didn't find such a field.”
* Limit attendance and use small delays.
* Handle errors with Continue On Fail only where it makes sense, and log failures so you don't lose them.
* Test in parts: run loose branches and check the shape of the items at each step.

## Download the video workflows

* Wallapop Scraper: [3a.json](/public/posts/3a.json)
* ArgenProp Scraper: [3b.json](/public/posts/3b.json)

You import the JSON into n8n and you can play with your own keywords, page limits and filters.

##Video

## Closing

The magic of n8n comes when you understand how items move between nodes, how to branch with IF and then put everything back together with Merge without breaking the order. Add loops for pagination and you're ready for more “production” flows. If it helps, clone the JSON, adapt it to your case and tell me what you scraped or what part stuck. See you next time, with more coffee and fewer repeated clicks.

---

> Original article in Spanish: [Workflows avanzados n8n: scraper, IF, merge, loops](https://codigomate.com/workflows-avanzados-n8n-scraper-if-merge-loops/)