---
title: "Your first PHP server"
publishDate: "2021-01-08"
slug: "your-first-php-server"
excerpt: "PHP is a language that has been around since 1995, just like JAVASCRIPT. But for servers it has been used for a long time. That is why today it is still very important.  Getting started! How to start?..."
readingTime: 1
tags: ["php", "javascript", "server"]
---

PHP is a language that has been around since 1995, just like JAVASCRIPT. But for servers it has been used for a long time. That is why today it is still very important.

# Getting started!

How to start?

1. go directly to <https://php.net>
2. click on downloads.
3. Click on Windows Downloads for the latest available version. (stable release)
4. Download thread safe zip file for our operating system version, in my case x64.
5. Extract all the contents of the zip to C:\php.
6. Add the PATH variable to windows.  
   ![Enviroment Variables](/public/posts/1.png)
7. Go to environment variables.
8. Click on PATH, and add NEW with address in C:\php.
9. open CMD and test if PHP works, with the php-v command
10. It should show the version. To run our server, we are simply going to give php -s localhost:4000.
11. The server will be listening at that address, and with that we will be able to start coding in PHP.
12. The default path will be C:\Users(yourUserName)
13. There create a folder called www. and inside it a .PHP file, like site.php.
14. Let's make some simple lines, to test.  
    ![PHP TEST](/public/posts/2.png)
15. Go in the browser to <http://localhost:4000/www/site.php>
16. We should see the following:  
    ![PHP TEST](/public/posts/3.png)

The server is already working!

---

> Original article in Spanish: [Tu primer server PHP](https://codigomate.com/tu-primer-server-php/)