---
title: "I built an AI app for architects in 4 hours (and it works!)"
publishDate: "2025-04-26"
slug: "i-built-an-ai-app-for-architects-in-4-hours-and-it-works"
excerpt: "I set a 4-hour timer, prepared a mate, and challenged myself: can I build a full-stack app that generates images with AI for architecture students, from end to end, in one afternoon? I loved the idea ..."
readingTime: 3
tags: ["nextjs", "sqlite"]
---

I set a 4-hour timer, prepared a mate, and challenged myself: can I build a full-stack app that generates images with AI for architecture students, from end to end, in one afternoon? I loved the idea because it mixes what I have been working on: Next.js, self-hosting and AI APIs… but with the pressure of the clock, which always brings your scope down to earth.

## Development

### The plan (cut to the chase)

I started with a short and realistic plan:

* A simple interface: prompt + style selector.
* Image generation with the OpenAI API (Image 4.0).
* Minimal persistence with SQLite to save results and states.
* A basic payment flow with Walá to enable generations.
* Deployment on a VPS, without strange dependencies.

No giant features: no advanced queues, no fancy admin panel. Less is more if you want to get there.

### Stack and why

* Next.js (App Router) to move quickly with routes, APIs and SSR where necessary.
* Tailwind to layout without fighting with CSS.
* SQLite because it is practical, lightweight and perfect for functional prototypes.
* OpenAI Image 4.0 to obtain quality results with limited prompts.
* Walá for simple payment and a webhook that frees the use of the app.

Also, I started with my starter so as not to waste time on the boilerplate:

* Repo: <https://github.com/martin2844/knext>

### Minimal and direct backend

I created an endpoint that:

1. Validate user input.
2. Makes the call to OpenAI to generate the image.
3. Save metadata and results in SQLite.
4. Returns the image ready to display on the front.

The key was to keep the circuit short: less “magic”, more control.

### Painless payments

I integrated Walá to enable paid generations. The flow:

* Checkout -> payment -> webhook -> mark the user as enabled.
* No complicated state machine loops; a flag and run.

### Deployment and testing

I uploaded it to a VPS and it was online in minutes. Nothing too heavy: build, environment variables, SQLite base and testing with real cases. You can check it at <https://proyect.ar>

### I arrived and it works?

Yes, post. In less than 4 hours a usable version was left that:

* Generate visual concepts quickly for architecture students.
* Allows iterating styles and prompts.
* Has a clear path to evolve to production.

### What helped me the most

* Reuse the same stack (brain in “flow” mode).
* Scope closed from minute 1.
* Do not underestimate payments: integrate them before “the last minute”.

If you want to see how I put together this stack very quickly, watch this other episode:

* [Watch on YouTube (spanish)](<https://youtu.be/PCOdoHA0CIg>)

##Video

## Closing

I love these challenges because they force you to focus on the essentials and deliver something real, not an eternal demo. If you are interested in building apps quickly, try my starter and tell me what you would do in 4 hours. What other express app would you like me to try? Leave it in the comments and we'll see you in the next mate.

---

> Original article in Spanish: [Construí una app de IA para arquitectos en 4 horas (¡y funciona!)](https://codigomate.com/construi-una-app-de-ia-para-arquitectos-en-4-horas-y-funciona/)