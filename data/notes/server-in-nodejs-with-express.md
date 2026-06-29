---
title: "First Node.js Server with Express"
publishDate: "2021-01-08"
slug: "server-in-nodejs-with-express"
excerpt: "Set up your first local web server with Node.js and Express in under 15 minutes. A beginner-friendly guide with code and a video walkthrough."
readingTime: 1
tags: ["javascript", "nodejs", "express", "server"]
---

In this tutorial you will learn how to set up your first local web server using NodeJS and Express. It is very simple and fast, but it is also very useful. In less than 15 minutes you will have a server running locally, serving all your html files and more.

## How to make a server in less than 15 minutes.

1. Download NodeJs, from [nodejs](https://nodejs.org/en/), you must download the recommended version for all users, simply to make sure that no errors occur because the version does not yet have full support.
2. Check that it was installed correctly by running the following from the command console:

```
node -v
```

If it returns the version it means that it was installed correctly, otherwise it will say that it does not recognize the command.

3. Create a new folder
4. open the folder with VSCODE.
5. New terminal in VSCODE, we run NPM init. Everything yes.
6. Then in terminal, run "npm i express" to install express
7. New File, called index.js with the following:

```
const express = require("express");
const app = express();
const PORT = (process.env.PORT || 3000);

app.get("/", (req,res) => {
    res.sendFile(__dirname + "/index.html");
});
app.get("/contact", (req,res) => {
    res.sendFile(__dirname + "/contact.html");
});

app.listen(PORT, ()=> {
    console.log("The server is running");
});
```

8. Create index.html and contact.html files with whatever we want. Each of those files will be accessible through the paths that we specify with the app.get. For example, to go to the contact page we will go to <http://localhost:3000/contacto>.
9. Before trying it, run in console

   ```
   node index.js
   ```

### Our server is ready, try it!

[![Watch the video](https://i.ytimg.com/vi/BThMYS4AmQA/hqdefault.jpg)](https://www.youtube.com/watch?v=BThMYS4AmQA)

---

> Original article in Spanish: [Servidor en NodeJs con Express](https://codigomate.com/servidor-en-nodejs-con-express/)