---
title: "This free tool will EXPLODE your productivity - Fabric"
publishDate: "2024-09-06"
slug: "this-free-tool-will-explode-your-productivity-fabric"
excerpt: "Short and short: I was trying Fabric and it blew my mind. It is an open source tool that converts prompts and flows with AI into reusable “patterns” from the terminal. Zero smoke: you install it, give..."
readingTime: 3
tags: ["nextjs", "docker"]
---

Short and short: I was trying Fabric and it blew my mind. It is an open source tool that converts prompts and flows with AI into reusable “patterns” from the terminal. Zero smoke: you install it, give it your API key and in minutes you are summarizing videos, cleaning text, generating documentation or doing research without leaving your flow with Vim/Tmux.

## What is Fabric and why did it hook me?

Fabric is a CLI that lets you:

* Execute “patterns” (curated prompts) for common tasks: summarizing, improving writing, analyzing code, generating README, research, etc.
* Chain sources: web, YouTube, files, clipboard, whatever you have on hand.
* Version your prompts into files and share them with your team.
* Use it as if it were any other Unix tool: you pipe things in, filter, save results... all from the console.

What I liked the most is that it brings the “prompt engineering” thing down to earth and makes it productizable. It stops being “magic in the chat” and becomes part of your workflow.

Official Doc (so you can gossip about all the patterns and options):  
<https://github.com/danielmiessler/fabric>

## How do I get it running in 5 minutes?

* Got your OpenAI API key:  
  <https://liveconnect.chat/es/obtener-api-key-openai-chatgpt>
* Optional (but very useful): YouTube API key to extract/synthesize content:  
  <https://elestudiodeandres.com/como-obtener-la-api-key-de-youtube/>
* Export your environment variables:
  + macOS/Linux: `export OPENAI_API_KEY="your_key"`
  + Windows PowerShell: `$env:OPENAI_API_KEY="your_key"`
* Install Fabric following the official README (things change often, better go to the source):  
  <https://github.com/danielmiessler/fabric>

Honest tip: the tool is free, but it uses third-party models. That is, you pay tokens (cheap if you use small models and short prompts). Start calmly and measure.

## Clipboard shortcuts that save you (Windows with Git Bash)

If you use Git Bash on Windows, you can have `pbcopy/pbpaste` like on macOS. Paste this into your `~/.bashrc`:

```
alias pbpaste='powershell.exe -command "Get-Clipboard"'
alias pbcopy='powershell.exe -command "[Console]::In.ReadToEnd() | Set-Clipboard"'
```

It is beautiful to do things like:

* You copy text → `pbpaste | fabric ... | pbcopy`
* Paste the result wherever you want.

## What specifically do I use it for?

* Summarize long articles or videos into actionable bullets.
* Groom tickets: clean descriptions, extract subtasks and estimates.
* Write first versions of README/CHANGELOG from diffs or notes.
* Refactor or explain code blocks pasted from the clipboard.
* Prepare initial research on a topic with web sources and links to go deeper.

The funny thing is that each “pattern” is a file. If something works for you, you version it and always have it on hand. Reproducible, shareable and fast.

## The video

Here I show you live how I configure it and several real examples within my workflow:

## Closing

Fabric closed me down everywhere because it lets me have AI “superpowers,” but with the simplicity of the terminal. Without depending on heavy UIs or infinite tabs. If this approach appeals to you, play the video and tell me in the comments what pattern you would like us to create for the channel.

And if you want to go to another devops direction, I also leave you this resource to learn how to self-host with Next.js and Docker (100% practical):  
[Watch on YouTube (spanish)](<https://www.youtube.com/watch?v=DAaXdNrcTV0>)

See you in the next post. Keep building with code and coffee.

---

> Original article in Spanish: [Esta herramienta gratis va EXPLOTAR tu productividad - Fabric](https://codigomate.com/esta-herramienta-gratis-va-explotar-tu-productividad-fabric/)