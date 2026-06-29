---
title: "WordPress Deploy to Cloud Compute"
publishDate: "2023-05-20"
slug: "wordpress-deploy-to-cloud-compute"
excerpt: "Host WordPress for free on Google Cloud Compute or AWS. A step-by-step guide to set up a LAMP stack and get WordPress running on your own VM."
readingTime: 6
tags: ["wordpress"]
---
## WordPress Deploy to Cloud Compute

After doing a lot of research, there isn't really a good solution for free WordPress hosting.  
Really everyone has a con, or they don't allow you SSL

Well, there is an answer to this. Since there are free VMs (VPS), you can launch a WordPress site through one of these instances. You can use Amazon, or Google Cloud, since both offer a Free Tier for Virtual Machines for a year and both are very good.

Both AWS and Google Cloud Compute allow you to launch a VM already armed with WordPress but it is more expensive than a common machine in Ubuntu, and the reality is that the machine bare with UBUNTU is much more customizable and would allow us to have several WordPress sites within it.

So this will be a guide to be able to configure your own WordPress site from the VM terminal, that is, through an SSH connection.

In this post we are not going to go into much detail about what each thing is and what it does. But if you follow this guide you will end up with a WordPress instance up and running.

1. First you have to create the account in Google Cloud Compute. They ask for a credit card, but they charge you something that is refunded. It is simply to verify your identity, so that you are not spam.
2. Go to google cloud compute and launch a new instance. The size of the instance will vary depending on different factors, traffic, storage needs, processing power.

We have to allow HTTPS and HTTP traffic.

3. Once you choose the size of the instance, I chose Micro, we have to wait for the instance to finish creating.
4. We press SSH, this is something very convenient that Google Cloud Compute offers, from the VPS panel we can press SSH and a terminal opens. It is very useful if we are on Windows, we save downloading the key and using putty. At once we enter the Ubuntu terminal.  
   

It should look like this:

5. After that, the fun begins. First we need to install several packages on the VM instance.

First do update and upgrade.

```
    sudo apt update -y
    sudo apt upgrade -y
```

Then we need to set up apache! or the famous LAMP

Linux, Apache, MySQL and PHP

Then we are going to install the famous LAMP stack, Linux-Apache-MySql-Php

6. Run the following

```
    sudo apt install apache2
```

Once ready, Apache2 should be running, we can check it with the following command.

```
    sudo systemctl status apache2
```

It should say, its active and running.

If you walk it should say "active and running"

If it is not working we can start it manually:

```
    sudo systemctl start apache2
```

We should be able to check that Apache is working, we copy the IP of our VP and paste it in the browser bar. It should show us the Apache stock page. Make sure you are not forcing HTTPS on the VM's IP, it still would not be using HTTPs, we are missing some things. So if you put HTTPS it will not enter.

This is your IP

This is the default Apache page.  

To guarantee that the apache2 service always runs we have to execute the following command:

```
    sudo systemctl enable apache2
```

If the VM is restarted, apache2 boots itself again.

7. Now we are going to install the database, we are going to use MARIADB this time.

```
    sudo apt install mariadb-server mariadb-client
```

We are installing both the client and the server. The server is to set up our database server, the client is used to connect to the DB server.

Now we start the server:

```
    sudo systemctl start mariadb
```

Again, we check the status:

```
    sudo systemctl status mariadb
```

Again, I should say UP and Running

8. Now we are going to configure the DB, we use the following command:

```
    sudo mysql_secure_installation
```

We press enter at the prompt that says "enter current password for root" since we do not have a password yet.  
You press Y to "set root password".  
We choose our password for root, which we must remember.

We choose Y to remove the anonymous user, and again Y to disallow root login remotly.

After these commands we restart MariaDB.

```
    sudo systemctl restart mariadb
```

9. Now we are going to install some requirements to be able to get PHP running, since WordPress is basically PHP.

```
    sudo apt install php php-mysql php-gd php-cli php-common
```

10. Let's download wordpress.

But first, we need `wget` and `unzip`. Wget should already have it, but unzip probably doesn't, so we run the following.

```
    sudo apt install wget unzip
```

Once this is ready, we download wordpress with `wget`

```
    sudo wget https://wordpress.org/latest.zip
```

This will download the latest version of WordPress, but in zip. So we have to unzip it.

```
    sudo unzip latest.zip
```

It will generate a wordpress directory, check it by using the "ls" command.

A wordpress directory will be created, we can check it using the `ls` command

11. Let's move the wordpress folder to the apache2 directory

```
    sudo cp -r wordpress/* /var/www/html
```

This will move all the files to the apache folder. Let's go to that directory now:

```
    cd /var/www/html
    ls
```

We should already have the files there, now we are going to change the user privileges, so that Apache can use them:

```
    sudo chwon www-data:www-data -R /var/www/html
```

Now, to access the page we have to remove the stock Apache page. Let's delete the index.html

```
    sudo rm -rf index.html
```

Now if we go to the IP of our server we should see the wordpress installation page! GOOD!

12. A little bit is missing, we have to set the DB for wordpress:

We return to the SSH terminal and go with the following:

```
    sudo mysql -u root -p
```

It will ask us for our root password, which was the one we set before. We put it in and it should log us into MariaDB. So let's set up a database now:

```
    create database wpdb;
```

wpdb can be whatever you want, you can give it wordpress, database, bd, whatever name you want. Then we need a user for wordpress to use:

```
    create user "user"@"%" identified by "password";
```

Change user and password to the user and password you want. Now you have to give this new user access to the DB.

```
    grant all privileges on wpdb.* to "user"@"%";
```

We leave with the command:

```
    exit
```

Now returning to the browser, we are going to give WordPress the data it requested!

Database name: wpdb  
username: user  
password: password  
Database Host: localhost  
table prefix: wp\_

We press Submit and it should be ready.

You may see a message saying that the database could not be written. I can give you a PHP code to add to our configuration.  
Returning to the terminal, inside the wordpress folder we go with the command:

```
    sudo nano wp-config.php
```

Once inside, we delete everything and paste the code that we just copied. We do Ctrl-X to exit, and Y to save.

13. Ready, we go back, press continue and we run the installation. It should work fine

Site title: Whatever  
username: the user to log in to the wordpress admin  
password: password

email: an email to recover the user

After that, we log in to WordPress and that's it.  
The only thing missing is the SSL, and the custom domain, but that is in another post.

---

> Original article in Spanish: [Deploy de wordpress a Cloud Compute](https://codigomate.com/deploy-wordpress-google/)