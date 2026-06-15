---
title: "5 things you have to do in 2026 to avoid leaving your server exposed."
publishDate: "2025-12-27"
slug: "5-things-you-have-to-do-in-2026-to-avoid-leaving-your-server"
excerpt: "Lately I have received several messages like “they opened my VPS” or “they entered me via SSH and I don't know how.” I'm not going to sell you smoke: if you self-host, basic security is not optional. ..."
readingTime: 3
tags: ["SelfHosting", "Security"]
---

Lately I have received several messages like “they opened my VPS” or “they entered me via SSH and I don't know how.” I'm not going to sell you smoke: if you self-host, basic security is not optional. That's why I put together this video with a minimal, realistic and applicable checklist in 15–30 minutes so that your server is not given away. Nothing strange: what every server should have as a base.

## 1) Use a user other than root (and give sudo only when necessary)

Entering as root directly via SSH is inviting problems. Create a normal user and, if you need privileges, use sudo.

```

# Create user
adduser dev

# Hit sudo (on Debian/Ubuntu)
usermod -aG sudo dev

# Test login with the new user
su - dev
sudo -v
```

Optional (but recommended): disable root login via SSH. Edit:

```
sudo nano /etc/ssh/sshd_config

# Make sure you have:
PermitRootLogin no
```

And restart the service:

```
sudo systemctl restart ssh
```

## 2) SSH with keys, no passwords

You guess the passwords with brute force. The keys, no.

* Generate your key (on your machine):

  ```
  ssh-keygen -t ed25519 -C "your-mail"
  ```
* Upload to the server:

  ```
  ssh-copy-id dev@your-server
  ```
* Try to enter with the key and only then disable passwords:

  ```
  sudo nano /etc/ssh/sshd_config
  ```

# Make sure you have:

PasswordAuthentication no  
PubkeyAuthentication yes

# Optional:

#Port 2222

```
- Restart SSH:
```bash
sudo systemctl restart ssh
```

Tip: if you change the port, it is not “security” per se, but it reduces the bot noise.

## 3) Firewall on and only open what is necessary

That between what you decide, not everything.

With UFW (Debian/Ubuntu):

```

# Default restrictive policy
sudo ufw default deny incoming
sudo ufw default allow outgoing

# Allowed SSH (adjust if you changed the port)
sudo ufw allow OpenSSH

# or, if you use a custom port:

# sudo ufw allow 2222/tcp

# If you are going to serve web:
sudo ufw allow 80,443/tcp

# Activate and review
sudo ufw enable
sudo ufw status verbose
```

Shortcut: for SSH you can use rate limit:

```
sudo ufw limit OpenSSH
```

## 4) Automatic updates: security patches without thinking

Many intrusions come from already known vulnerabilities. Update and automate.

Debian/Ubuntu:

```
sudo apt update && sudo apt upgrade -y
sudo apt install -y unattended-upgrades
sudo dpkg-reconfigure --priority=low unattended-upgrades
```

Check that security packages are updated and that automatic reboots are configured if they help. On RPM distros, look at `dnf-automatic`.

## 5) Fail2ban to stop attempts and big-headed bots

Blocks IPs that fail many times, especially useful for SSH.

```
sudo apt install -y fail2ban
sudo systemctl enable --now fail2ban
```

Quick setup:

```

# Create local override
sudo nano /etc/fail2ban/jail.local

[sshd]
enabled = true
port = ssh
logpath = %(sshd_log)s
maxretry = 5
findtime = 10m
bantime = 1h
```

Apply and verify:

```
sudo systemctl restart fail2ban
sudo fail2ban-client status sshd
```

Optional bonus:

* 2FA for SSH (pam\_google\_authenticator).
* Backups outside the server (if they encrypt you or break something, you are saved).
* Basic alerts: logwatch, healthchecks for cron, and a ping if the server goes down.

---

## Watch the step by step in the video

---

## Closing

You don't have to be paranoid, but you do have to be disciplined. With these five things you go from “exposed” to “reasonable” in a very short time. Then you can add layers, but start here. If it helps, leave me a comment telling me what you added in your setup, and go audit your server with a mate at your side. See you in the next one.

---

> Original article in Spanish: [5 cosas que tenés que hacer en 2026 para no dejar tu server expuesto.](https://codigomate.com/5-cosas-que-tenes-que-hacer-en-2026-para-no-dejar-tu-server-expuesto-selfhosting-security/)