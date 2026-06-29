---
title: "GLM rose in price… and changed everything"
publishDate: "2026-04-11"
slug: "glm-rose-in-price-and-changed-everything"
excerpt: "GLM used to be my favorite cheap model hack until the price jumped. Here is what changed and whether it is still worth using."
readingTime: 3
tags: ["z.ai", "claude", "codex", "kimi"]
---

## Introduction

A while ago GLM was my favorite “hack”: decent quality, very low cost and a couple of technical details that made it perform more than it cost. But it took a jump in price and, well, it changed the game. I recorded this video to explain why I no longer recommend it as before and what options are working best for me depending on the case.

## What changed with GLM?

* Before it had a price/quality ratio that was good everywhere. Now, with the increase, it was much closer to the big ones... but with the same frictions as always (variable latency, strange billing, limits that move).
* The value of the “hack” was the gap: paying little for something that performed like a more expensive model. If that gap disappears, you're left with pure complexity without the benefit.
* If you already have it integrated and it works stable, ok, you can continue. But for new projects, today it doesn't seem like the smartest move.

## When does it still make sense?

* You have an integration going and changing involves a lot of refactoring.
* Your use case matches their strengths perfectly and you don't get hit by the new cost.
* You operate in markets where GLM has availability/regulation advantages.

If you're not in that group, there are cleaner alternatives with better TCO (total cost of ownership).

## Alternatives that I recommend depending on the case

### 1) General quality, without breaking the bank

* Claude (Sonnet line) for reasoning and consistency. It is usually a good sweet spot.
* “Mini” models of the big ones for utilitarian tasks (summaries, classification, reformulation), where price per token rules.

### 2) Code and pair programming

* “Powerful” open-source that you can self-host or consume in cheap clouds: Qwen2.5 Coder, DeepSeek Coder, Llama 3.1 Instruct (depending on size).
* If your stack allows it, combine a code for generation and a “generalist” one for explanations/reasoning. Cheap and performs.

### 3) Navigation and research

* Kimi is solid for long browsing sessions and reading long PDFs.
* To “search and synthesize”, research-oriented tools usually beat a raw LLM with browsing.

### 4) Ultra-low costs with fine control

* Self-hosting with a well-tuned 8B/14B + RAG. If your data is good, the magic is in the retrieval, not in throwing away premium tokens.

## My post-GLM setup (what is working for me)

* Multi-model router: use something like OpenRouter or a proxy like LiteLLM to exchange providers without touching your app.
* Clear fallbacks and timeouts: if model A is late, it falls to B. The user does not pay for instability.
* Real metrics: cost per 1k/1M tokens, p95 latency and “retry” rate. Without numbers, you choose blindly.
* Caching and context compression: save frequent responses and historical summary. Less tokens, less money.
* Separate tasks: classification/summary in “mini”; reasoning/planning into a better one. Not everything deserves an expensive model.

## The video

## Closing

You don't have to marry a model. GLM was a goal while it cost two mangoes; Now that it went up, it lost the differential. The important thing is to have an architecture that allows you to move pieces quickly and choose what is best today, not what was convenient six months ago. I'm going to continue testing, changing and telling about it here. Bring the mate and come build with me.

---

> Original article in Spanish: [GLM subió de precio… y cambió todo](https://codigomate.com/glm-subio-de-precio-y-cambio-todo-z-ai-claude-codex-kimi/)