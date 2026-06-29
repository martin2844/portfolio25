---
title: "Next.js Hacks 2 - Use Discord as a Logger"
publishDate: "2025-08-17"
slug: "nextjs-hacks-2-use-discord-as-a-logger"
excerpt: "Send logs straight to a Discord channel instead of paying for a logging service. A simple Next.js hack for small projects and side apps."
readingTime: 3
tags: ["nextjs"]
---

Lately I have been complaining about paid logging services: expensive, overkill for small projects and on top of that you don't always look at them. So I thought: what if I just send everything to a Discord channel and that's it? I have it on my cell phone, I can mention the team, search, filter by threads... and it's free. In this episode I show you how I turned a Discord channel into my “Battle Datadog”.

---

## Why Discord as a logger?

* Free and fast: a Webhook and on to something else.
* Mobile-first: errors reach you on your cell phone as notifications.
* Shareable: tag the team, open threads by incident, paste screenshots, all in the same place.
* Simple: no heavy SDKs, no agents, no dashboards that you are not going to open.

---

## How I solved it in Next.js

### 1) Create a Webhook on your Discord channel

* Go to Server Settings → Integrations → Webhooks → New webhook.
* Choose the channel (for example, #logs-app) and copy the URL.
* Save it as an environment variable: `DISCORD_WEBHOOK_URL`.

Tip: you can have one per environment: `DISCORD_WEBHOOK_URL_DEV`, `DISCORD_WEBHOOK_URL_PROD`.

---

### 2) A small service to send logs

I created a helper that sends messages to the Webhook and takes care of formats, levels and payload size.

Code: src/services/discordLogger.ts  
Gist: <https://gist.github.com/martin2844/9e7ddc28d92481b3e30322af2404cf12>

What it does:

* Maps `level` → color/emoji (info, warn, error).
* Cut long messages into chunks to avoid breaking the webhook.
* Accepts `meta` (object) and serializes it verbatim into code blocks.
* Respect basic rate limits.

Minimum usage example:

```
import { discordLog } from "@/services/discordLogger";

await discordLog("error", "Failed to create order", { orderId, userId, payload });
```

---

### 3) Instrumentation: intercept console.log/warn/error

Next.js allows an instrumentation file that runs when starting the runtime (ideal for patching global things). I took advantage of it to “duplicate” the logs: they continue to go to the console, but they are also sent to Discord.

File: src/instrumentation.ts  
Gist: <https://gist.github.com/martin2844/955248e15b796be3c8f6b051030db921>

Main idea:

* Save references to `console.log`, `console.warn` and `console.error`.
* Replace them with versions that:
  + They call the original (so as not to lose local logs).
  + Send the serialized message to Discord with the corresponding level.
* Filter common noises (for example, dev logs or very verbose libraries).
* Only activate if `DISCORD_WEBHOOK_URL` exists (so it doesn't bother you locally).

---

### 4) Enable the instrumentation hook in Next

The experimental flag must be turned on so that Next includes `instrumentation.ts`.

File: next.config.mjs  
Gist: <https://gist.github.com/martin2844/be7fb2557e9449562ec941a9d548b64b>

```
/** @type {import('next').NextConfig} */
const nextConfig = {
  experimental: {
    instrumentationHook: true,
  },
};

export default nextConfig;
```

---

### 5) Scripts and environment

Check the project scripts and env vars.

File: package.json  
Gist: <https://gist.github.com/martin2844/d22300eac9891dcaf06e43440f6d51ea>

Recommended variables:

* `DISCORD_WEBHOOK_URL` (or by environment)
* `APP_NAME` (to know which app it comes from)
* `APP_ENV` (dev/staging/prod) to group by environment in channel

---

## Good practices (so that it is not infernal spam)

* Filter levels by environment: in `dev`, almost everything to console; in `prod`, just `warn/error` to Discord.
* Do not send sensitive data. Sanitize tokens and PII.
* Group by threads: a thread by “incident” or by date helps a lot.
* Consider a “cooldown” for repeated errors (rate limiting).
* Separate channels by service: `#logs-web`, `#logs-workers`, `#logs-payments`.

---

## Use cases that have been working for me

* 500 and 404 errors with request metadata.
* Business events: new user, completed checkout, external webhooks.
* “Low-cost” alerts: if a cron fails or a job crashes, I see it by tapping on the cell phone.
* Deploy hooks: notification when a deploy is finished.

---

## Video

---

## Closing

This does not replace a SIEM or a post observability stack, but for small projects, MVPs or side projects… it is magic. Cheap, simple and keeps you close to what's happening in production.

If you try it, tell me how it went and what events you ended up sending. And if it joined you, share it with that friend who continues to pay more for logs. See you in the next hack, with another mate and less Datadog.

---

> Original article in Spanish: [Next.js Hacks 2: Usa Discord como Logger](https://codigomate.com/next-js-hacks-2-usa-discord-como-logger/)