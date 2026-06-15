---
title: "I made an app… and now people all over the world use it (but no one pays)"
publishDate: "2026-03-26"
slug: "i-made-an-app-and-now-people-all-over-the-world-use-it-but-n"
excerpt: "A while ago I was bitten by the bug to put together a tool for a very specific pain: removing silences from videos without having to open a heavy editor. I started with a small script in ffmpeg, I pre..."
readingTime: 4
tags: ["javascript", "ai", "mac", "finance"]
---

A while ago I was bitten by the bug to put together a tool for a very specific pain: removing silences from videos without having to open a heavy editor. I started with a small script in ffmpeg, I prepared it, wrapped it in an app with Electron and published it almost without thinking. Result: users everywhere, downloads every day… and 0 revenue. In this post I tell you what I did, what went well, what didn't, and what I'm doing to make this a product and not just “a nice feature.”

## Why Electron and not native

I chose Electron for something very simple: speed to exit. I already know JS/TS, and I needed:

* Cross-platform without burning months on each OS.
* Package ffmpeg and run long processes without drama.
* Iterate the UI quickly and be able to launch updates without fighting with native toolchains.

Costs of that decision:

* The binary is heavy and feels “big” for an app that does one thing.
* Antivirus/signatures/notarization: the circus of distributing on Windows and macOS exists and must be banked on.
* RAM consumption higher than something native. Many don't care, others do.

Still, to validate if there are people on the other side, Electron let me out in weeks, not months.

## From script to something people actually use

The jump from “magic command” to “product” was more than just pushing a button:

* Clear flow: drag video, choose mute sensitivity, preview estimated duration, render.
* Feedback and errors: understandable logs, progress bar, management of timeouts and rare files.
* Signed installers and automatic updates for Win/macOS/Linux (the less sexy side, but key).
* Minimally decent landing and simple docs so they don't depend on me to understand it.

That layer of “goofy detail” is what turns a script into something people recommend.

## Where is the traffic coming from? (including ChatGPT)

Two things surprised me:

* Long-tail SEO: having a clear page that names the problem (“remove silence from video”, “ffmpeg silenceremove”) brings people in every day.
* ChatGPT and other LLMs: When someone asks how to remove silence from a video, some answers end up mentioning the app as a simple option. It wasn't magic: I published useful guides, used terms that people search for, and kept the landing page focused on the problem.

I added to that:

* Short posts in forums and X counting the progress.
* A decent README and examples for those who prefer the CLI.

Nothing explosive, but constant. Like mate: slow and sure.

## Monetization: What I tried and why it didn't work (yet)

So far, attempts and setbacks:

* “Donate” button: serves to validate love, not business.
* “Pro” features behind a one-time payment: the friction of paying for a desktop utility is real if the use is occasional.
* Price: hesitating between something like 9/19/29 USD. If the problem is not high frequency, the perceived value is reduced.
* Free alternatives: ffmpeg does it, editors like DaVinci/Adobe have shortcuts and extensions. You compete against the “I'll solve it with what I have.”

Provisional conclusion: they use it, they recommend it, but the “urge to pay” does not appear without offering more “pro” or repeatable value.

## Where can it go?

The lines I'm exploring:

* Batch/automation and pro presets: saving real hours for creators who process a lot of content.
* Integrations or plugins for editors (DaVinci/Adobe): be where they already work.
* One-time license with clear annual updates, without eternal subscription.
* Better onboarding and education: if I show you that in 10 minutes you will save 1 hour, the price hurts less.

Is it a product or a feature? I'm still fighting it. But the data helps me choose.

## Watch the episode

Here I tell the entire journey, with numbers, doubts and next steps. First chapter of this build in public, without smoke.

## Closing

Launching quickly helps: it puts you in front of real users and brings you down to earth. Today CleanCut has people using it all over the world, and that alone gave me a crash course in product, distribution and pricing. Now it's time to refine the “for whom” and “why pay”.

If you are interested in trying the app or giving feedback that hurts but helps, stop by cleancut.video. And if you want to see how I'm doing with code and coffee, subscribe to the channel for more episodes of this process.

---

> Original article in Spanish: [Hice una app… y ahora la usa gente en todo el mundo (pero nadie paga)](https://codigomate.com/hice-una-app-y-ahora-la-usa-gente-en-todo-el-mundo-pero-nadie-paga/)