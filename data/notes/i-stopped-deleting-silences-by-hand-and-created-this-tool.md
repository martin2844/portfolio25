---
title: "Auto-Cut Video Silences with This Tool"
publishDate: "2025-12-31"
slug: "i-stopped-deleting-silences-by-hand-and-created-this-tool"
excerpt: "I built a tool that removes silences from videos automatically instead of cutting them by hand. A quick look at how it works and why I needed it."
readingTime: 2
tags: ["electron", "js", "ffmpeg"]
---

Editing long videos was eating my life away. Between marking cuts, reducing silences, and zooming in on the timeline, I could waste hours on the most boring part of the process. So I got stubborn about the problem and put together a tool that does that job for me. I'm using it in my own videos and it completely changed my flow.

## The problem: the eternal scissors

When you speak in front of the camera there are pauses, doubts, breaths and moments where you search for words. Everything is fine, it's natural. But if you want a more agile pace, you have to cut back. Doing it by hand in a 40-60 minute project is a hassle. And after a while, you no longer think about the content but about surviving the editing.

## The solution: automatic cuts without killing the rhythm

I made an app that detects silence and creates cuts automatically. You give it your video, choose how “aggressive” you want it to be with the silences and export an MP4 without those bumps. You can also add a small “padding” before and after each cut so that the speech does not become robotic. Everything local, nothing in the cloud.

I published it as CleanCut so anyone can try it:

* <https://cleancut.video>

## Inside: JavaScript, Electron and ffmpeg

* Audio engine: I use ffmpeg to detect silences and mark “spoken” segments. It is very fast and reliable.
* Assembly: with this list of segments, ffmpeg assembles the final “clean” video.
* Desktop app: I made the interface with Electron + JS so that it runs on Mac/Windows/Linux and is easy to maintain.

There is no weird magic: ffmpeg is the tank that does the heavy lifting; The app is the pilot that tells you what to cut and how.

##How do I use it in my flow

* I record a long take, without worrying so much about silences.
* I run the file through CleanCut with a quiet preset (silence threshold + padding).
* I get a more agile master and only then do I add titles, B-roll and color in the editor I use that day.
* Result: less time cutting scissors, more time creating.

## What I learned putting this together

* Defaults matter: good padding makes it sound natural.
* Fewer clicks = more focus: automating 80% of the editing gives you creative energy back.
* ffmpeg is a beast: if you tame it, you will save hours and servers.

##Video

## Closing

It is not a magic solution for everything, but if it happens to you like me that editing eats up your time in silence, this tool can save you. If you try it, tell me what you think and what you would like to see improve. I continue to iterate, mate in hand, so that we edit less and create more.

---

> Original article in Spanish: [Dejé de borrar silencios a mano y armé esta herramienta](https://codigomate.com/deje-de-borrar-silencios-a-mano-y-arme-esta-herramienta-electron-js-ffmpeg/)