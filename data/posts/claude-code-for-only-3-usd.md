---
title: "Claude Code for only 3 USD"
publishDate: "2025-10-18"
slug: "claude-code-for-only-3-usd"
excerpt: "Lately I've been using Claude Code a lot to code faster, but the official subscription hits your pocket. So I started to test if there was a legal way to pay less… and yes: with the z.ai API you can u..."
readingTime: 3
tags: ["ai", "finance"]
---

Lately I've been using Claude Code a lot to code faster, but the official subscription hits your pocket. So I started to test if there was a legal way to pay less… and yes: with the z.ai API you can use Claude Code for about $3 a month. In this post I tell you the play and I leave you step by step.

## What will you find

* How to create a free account on z.ai
* Where to get your API Key
* How to plug it into Claude Code
* Quick cost comparison and some caveats

## Why z.ai

z.ai offers access to AI models (including Claude) via API. The trick: it enables you to “Developer Tools” for very little, ideal if you want to try Claude Code without committing to the full subscription. It is the same experience within the editor, but optimizing spending.

Official reference guide (in case you want to check details): <https://docs.z.ai/scenario-example/develop-tools/claude>

## Step by step: from 0 to Claude Code for 3 USD

1) Create an account on z.ai

* Register with your email.
* Verify the account and log in to the dashboard.

2) Get your API Key

* In the dashboard, go to “API Keys”.
* Generate a new key and copy it by hand (save it well, do not upload it to any repo).
* If you see an option for “Developer Tools” or similar, activate it.

3) Configure Claude Code with the API

* Open Claude Code and go to Settings/Providers (or Configuration > Providers).
* I chose the option to use your own API (custom/external provider).
* Paste your z.ai API Key.
* Select the Claude model you want (the one you have enabled in z.ai).
* Save and try with a simple query to validate that it responds.

4) Ready to code

* Use the same features: complete code, explain snippets, refactors, etc.
* If you see error messages (401/403), check:
  + That the key is correctly copied.
  + That you have “Developer Tools” active in z.ai.
  + That the model you chose is enabled in your account.

## Costs: what changes and what doesn't

* Official (subscription): usually around 20 USD/month.
* With z.ai: approx. $3/month to enable use with Claude Code.
* Same editor, same tools, but paying much less.

Tip: if you are going to use it heavily for large projects, measure consumption and limits. For most daily flows, the savings are already noticeable.

## Quick good practices

* Save the API Key in environment variables (e.g. .env) and exclude that file from the repo.
* If you work as a team, each one with their key.
* Keep an eye on the z.ai dashboard in case the limits or available models change.

## Watch the video with the entire setup

Here I show you the complete step by step, live and without laps:

## Closing

I love it when a simple way to pay less for the same tool appears. If you were wanting to try Claude Code without spending 20 dollars a month, this is the move. Tell me in the comments if it worked for you, which model you chose and if you want me to make another video comparing performance between options. Now, to rub shoulders with a mate at your side and your wallet a little happier.

---

> Original article in Spanish: [Claude Code por solo 3 USD](https://codigomate.com/claude-code-por-solo-3-usd/)