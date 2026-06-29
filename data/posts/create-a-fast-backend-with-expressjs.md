---
title: "Create a fast backend with ExpressJS"
publishDate: "2024-07-27"
slug: "create-a-fast-backend-with-expressjs"
excerpt: "Build a backend for your next MVP without getting lost in giant boilerplates. Pure Express, clear structure and minimal files that just work."
readingTime: 3
tags: ["docker"]
---

I started this video because many times we want to build a backend for an idea or an MVP and we get lost in giant “boilerplates”. Here I'm going to keep it simple: Pure Express, clear structure and minimal decisions to get going in minutes. This is what I have been using and it works for me to iterate quickly without mortgaging the future of the project.

## Why Express and for whom?

* Because it is minimalist: you choose the pieces.
* Ideal for MVPs, internal APIs, webhooks, small and medium services.
* If you later want to climb, the structure is already organized.

## The minimal structure I use

I like to separate the app from the server, and have routes, controllers and middlewares in separate folders. Something like this:

```
project/
├─ package.json
├─ .env
└─ src/
   ├─ app.js
   ├─ server.js
   ├─ routes/
   │ └─ index.js
   ├─ controllers/
   │ └─ health.controller.js
   ├─ middlewares/
   │ └─ notFound.js
   └─ config/
      └─ env.js
```

## Base dependencies

* Production: express, cors, morgan, dotenv
* Dev: nodemon

Quick commands:

```
npm i express cors morgan dotenv
npm i -D nodemon
```

In package.json:

```
"scripts": {
  "dev": "nodemon src/server.js",
  "start": "node src/server.js"
}
```

## separate app.js and server.js

Keeping them separate lets you test the app without setting up the server and makes scaling easier.

src/app.js:

```
const express = require('express');
const morgan = require('morgan');
const cors = require('cors');

const routes = require('./routes');
const notFound = require('./middlewares/notFound');

const app = express();

app.use(cors());
app.use(express.json());
app.use(morgan('dev'));

app.use('/api', routes);

app.use(notFound);

module.exports = app;
```

src/server.js:

```
require('dotenv').config();
const app = require('./app');

const PORT = process.env.PORT || 3000;

app.listen(PORT, () => {
  console.log(`Server listening on http://localhost:${PORT}`);
});
```

## Simple routes and controllers

src/routes/index.js:

```
const { Router } = require('express');
const { health } = require('../controllers/health.controller');

const router = Router();

router.get('/health', health);

module.exports = router;
```

src/controllers/health.controller.js:

```
function health(req, res) {
  res.json({ ok: true, uptime: process.uptime() });
}

module.exports = { health };
```

## 404 Middleware

src/middlewares/notFound.js:

```
module.exports = (req, res, next) => {
  res.status(404).json({ error: 'Path not found' });
};
```

## Environment variables

In .env:

```
PORT=3000
NODE_ENV=development
```

And a little helper in case you want to centralize:  
src/config/env.js:

```
module.exports = {
  port: process.env.PORT || 3000,
  nodeEnv: process.env.NODE_ENV || 'development',
};
```

## What's left for next?

Error handling. It's a big topic: error middlewares, categories, HTTP codes, logging, and consistent responses. We see it in depth in the next part of the minicourse.

## Video

Below I leave you the full episode so you can see it step by step.

## Bonus: fast deploy

If you want to take this to production in minutes, I have a video where I show how to deploy with Ubuntu + Docker. It can save you if you are validating an idea quickly:  
[Watch on YouTube (spanish)](<https://www.youtube.com/watch?v=Hz_Jr2I_n8w&ab_channel=CodigoMate>)

---

Thanks for banking on the other side. If it helped you, leave a like and tell me in comments what you want to see in the next parts of the Express mini-course. Big hug and see you in the next one, which comes with errors... but well handled.

---

> Original article in Spanish: [Crea un backend rapido con ExpressJS](https://codigomate.com/crea-un-backend-rapido-con-expressjs/)