---
title: "Install n8n with Docker the Easy Way"
publishDate: "2025-11-19"
slug: "how-to-install-n8n-with-docker-the-easiest-way-1"
excerpt: "Start with n8n without breaking your machine. Two Docker commands are enough to have a local workflow automation server up and running."
readingTime: 3
tags: ["docker", "n8n"]
---

I had been receiving many questions like: “Hey, how do I start with n8n without breaking anything on my machine?” So I started recording the simplest and fastest way: Docker. Seriously, in two commands you have it up and running and you can automate things without having to deal with dependencies or strange installations.

---

## The essentials of the video

### What is n8n and why is it so good?

n8n is an open-source tool to automate tasks and connect services, something like a Zapier/Make but that you can host yourself. You build “workflows” by joining nodes: triggers (cron, webhooks, events) and actions (APIs, databases, emails, etc.). Ideal to get rid of repetitive work and let things run by themselves.

* Open-source, without vendor lock-in
* Runs locally, on a VPS or in the n8n cloud
* Visual interface but with a lot of flexibility for advanced things

### Where should it be installed?

* Local with Docker: perfect for testing and creating your first flows. Fast and risk-free.
* Own VPS: when you want something online, stable and with your domain/SSL. Cheaper in the long term than SaaS platforms.
* n8n.cloud: zero maintenance. You pay for convenience, ideal if you don't want to touch servers.

### Installation with Docker (recommended)

The idea is to leave it running in minutes and with persistence so as not to lose anything. You have two paths:

1) The minimum possible (to test quickly):

```
docker volume create n8n_data

docker run -d \
  --name n8n \
  -p 5678:5678 \
  -v n8n_data:/home/node/.n8n \
  n8nio/n8n:latest
```

2) With Docker Compose (my favorite to leave it fixed):

```

# docker-compose.yml
services:
  n8n:
    image: n8nio/n8n:latest
    ports:
      - "5678:5678"
    volumes:
      - n8n_data:/home/node/.n8n
    environment:
      - N8N_SECURE_COOKIE=false # useful locally without HTTPS
      - N8N_LOG_LEVEL=info
    restart: unless-stopped

volumes:
  n8n_data:
```

And you lift it with:

```
docker compose up -d
```

For VPS production you will want to add domain and SSL behind a reverse proxy (Caddy/Traefik/Nginx) and variables such as N8N\_HOST, N8N\_EDITOR\_BASE\_URL and WEBHOOK\_URL. But to start locally, it is not necessary.

### How do I verify that it turned out correctly?

* I opened <http://localhost:5678>
* Create your user (first start)
* If you want to look at the boot:

  ```
  docker logs -f n8n
  ```

Quick test inside n8n:

* New workflow with “Manual Trigger”
* Add a “Set” node and enter any key/value
* Hit “Execute” and make sure everything turns green

With that you are ready to create post automations.

---

## Watch the step by step in the video

---

## Closing

n8n is one of those tools that gives you time back. With Docker you can do it in no time and you can automate everything from simple things (notices in Slack, emails, backups) to more spicy integrations with APIs and databases. In future posts I am going to take it to a VPS with a domain and SSL, and tune it for production. If it helped you, tell me what workflow you would like to build and we'll put it together.

---

> Original article in Spanish: [Cómo instalar n8n con Docker (la forma más fácil) - 1](https://codigomate.com/como-instalar-n8n-con-docker-la-forma-mas-facil-1/)