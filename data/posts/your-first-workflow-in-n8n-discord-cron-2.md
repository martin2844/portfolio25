---
title: "First n8n Workflow: Discord + Cron"
publishDate: "2025-11-20"
slug: "your-first-workflow-in-n8n-discord-cron-2"
excerpt: "Build a practical n8n workflow from scratch using Discord notifications and a cron trigger. A beginner-friendly step-by-step tutorial."
readingTime: 3
tags: ["n8n"]
---

Lately they have been asking me for something very practical from n8n, so I sat down with a mate and recorded this step by step so that you can build your first workflow from scratch. The idea is simple but powerful: trigger something with a Cron and send messages to a Discord channel automatically. No black magic or very expensive platforms: everything direct, clear and self-hosted.

## What we put together in the video

* How to create a workflow from scratch in n8n
* What is a trigger and why the Cron node is key
* How to schedule recurring tasks
* Connect n8n with Discord and send automatic messages
* A quick look at webhooks for when you want to trigger the stream from outside

---

## The workflow map

My base recipe to start with n8n, without complications:

1) Trigger (Cron)  
2) Logic/query (optional: HTTP Request, Set, IF)  
3) Output (Discord)

With that you can already cover a lot of cases: reminders, availability checks, alerts and scheduled messages that save your day.

### 1) Trigger: Cron

The Cron is the one who sets the rhythm. You set it to run every X minutes, at a certain time or on specific days. It is the “little clock” that triggers everything. In the video I use it to:

* Send a recurring message to Discord
* Hit an API and report results

Tip: set the time zone to n8n so that your schedules don't dance.

### 2) Do something useful in the middle

Here you can:

* Query an API with HTTP Request
* Format data with a Set node
* Add conditions with IF

For the “Useless facts” example, I paste an API that returns a random data and send it directly to the channel. For “Downtime Detector”, I consult a URL and if it responds incorrectly, I warn.

### 3) Send the message to Discord

You have two paths. In the video I show the simplest one: Discord Webhook.

* In Discord: channel > Edit Channel > Integrations > Webhooks > New Webhook > Copy Webhook URL.
* In n8n: HTTP Request node
  + Method: POST
  + URL: paste the Webhook URL
  + Content Type: JSON
  + Bodysuit:

    ```
    {
    "content": "Hello from n8n 👋"
    }
    ```

    And voila, Discord receives the message. If you want more advanced formatting, you can also send embeds.

Tip: save the Webhook URL as credential/secret in n8n so as not to expose it on the node.

### Bonus: Input webhooks on n8n

When you don't want to rely on Cron, you use a Webhook node at startup. n8n gives you a public URL that you can call from anywhere (another service, a script, whatever) and that triggers the workflow. Ideal for hosting external events.

---

## Download the video workflows

* Downtime Detector: checks a URL and notifies Discord if it goes down  
  [Downtime-Detector.json](/public/posts/Downtime-Detector.json)
* Useless facts: brings a random piece of information and publishes it on Discord with a Cron  
  [Useless-facts.json](/public/posts/Useless-facts.json)

Import them into n8n and change the minimum: your Discord Webhook, the URL to monitor, and the Cron frequency.

---

## Watch the full tutorial

---

## Closing

If you were looking for your entry to n8n, this is the starting point. With a Cron and a Webhook to Discord you can automate useful things in minutes. From here, the curve is pure fun: add conditions, loops, credentials and start connecting pieces as if they were LEGO. If you have any questions, I'll read you in the comments. See you next time, with more coffee and less repetitive work.

---

> Original article in Spanish: [Tu primer workflow en n8n (Discord + Cron) - 2](https://codigomate.com/tu-primer-workflow-en-n8n-discord-cron-2/)