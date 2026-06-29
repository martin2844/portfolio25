---
title: "Basic Self-Hosted Server Security (2026)"
publishDate: "2025-12-27"
slug: "basic-security-for-your-self-hosted-server-2026-ssh-firewall"
excerpt: "A practical security floor for anyone self-hosting: SSH hardening, firewall rules, automatic updates and backups so your VPS stays safe."
readingTime: 4
tags: ["docker", "coolify", "server", "self-hosting"]
---

If you self-host something (a VPS, a small server at home, Docker, Coolify, Caddy, Traefik), there is a security floor that you have to cover no matter what. I have been asked a lot: “What is the minimum to not be given away?” So I put together a step by step updated to 2026, smokeless, that you can apply in an afternoon and covers 80% of the most common risks.

## The important (and practical) thing about the video

### 1) Limit and audit ports: what is not exposed is not attacked

* First, look from the outside at what the Internet sees.
* Next, audit from the inside what is listening and where.

Useful commands:

```
# External (from your computer to the server's IP)
nmap -Pn --top-ports 1000 203.0.113.10

# Internal (on the server)
ss-tulpen
sudo lsof -i -P -n
```

Looks for services listening at 0.0.0.0 when they should be at 127.0.0.1. If you have reverse proxy, public is usually only 80/443 (and 22 for SSH). Databases (5432/3306), dashboards and metrics, all private or behind VPN.

Tip with provider firewall (DO/Droplets, AWS, Hetzner, etc.):

* Closes everything by default, opened only 22, 80 and 443.
* If there are admin panels, restrict by source IP if you can.

### 2) Strengthen SSH: non-root user, keys and “real” logs

Changing the port doesn't save you. It serves, but it is not the basis. The basis is: no root, passwords, and look at the logs.

Generate your password and upload it:

```
ssh-keygen -t ed25519 -C "your-mail"
ssh-copy-id user@server
```

Create user and hit sudo:

```
sudo adduser mate
sudo usermod -aG sudo mate
```

I hardened SSH in `/etc/ssh/sshd_config`:

```
PermitRootLogin no
PasswordAuthentication no
PubkeyAuthentication yes
KbdInteractiveAuthentication no
MaxAuthTries 3
Matt AllowUsers
LogLevel VERBOSE
```

Apply changes without cutting the branch:

```
sudo systemctl reload sshd
```

(Try another console that you can log into before closing the current session.)

Logs that matter:

```
sudo journalctl -u ssh -f
# or
sudo tail -f /var/log/auth.log
```

And if you use UFW, limit SSH:

```
sudo ufw limit 22/tcp
```

### 3) DO NOT use root (never to operate)

* Always log in with your common user with sudo.
* Use `sudo -v` when logging in to “warm up” credentials.
* If you need root quickly: `sudo -s` and exit quickly with `exit`.

### 4) Automatic security updates (without breaking anything)

On Debian/Ubuntu:

```
sudo apt update
sudo apt install unattended-upgrades
sudo dpkg-reconfigure unattended-upgrades
```

Check config:

```
sudo nano /etc/apt/apt.conf.d/50unattended-upgrades
```

Recommended:

* Only security and not everything crazy.
* With “needrestart” configured so that it does not turn off critical services without warning.
* If the server is critical, use maintenance and monitoring windows.

On Fedora/RHEL:

```
sudo dnf install dnf-automatic
sudo nano /etc/dnf/automatic.conf # apply_updates = yes
sudo systemctl enable --now dnf-automatic.timer
```

### 5) Firewall with UFW (and how it coexists with Docker and the provider)

Basic rules:

```
sudo ufw default deny incoming
sudo ufw default allow outgoing

sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

sudo ufw enable
sudo ufw status numbered
```

* If you use the provider's firewall, keep both: the provider's firewall filters “outside the server”, UFW filters inside. Double layer, less noise.
* With Docker, be careful: you can skip iptables. Simple solution to start: expose only what goes for 80/443 and leave the rest in internal Docker networks. If you need fine rules, use the DOCKER-USER chain or tune the daemon.

### 6) Backups are also security

It is not optional. Ransomware, a wrong rm -rf, a broken upgrade... it can happen to you. Makes restoring boring.

* 3-2-1 rule: 3 copies, 2 media, 1 off-server.
* Encrypt on the client (restic/borg are key).
* Try a real restore (don't tell stories).

Minimal example with restic + S3 compatible:

```
export RESTIC_PASSWORD="put-a-good-pass"
export AWS_ACCESS_KEY_ID="..."
export AWS_SECRET_ACCESS_KEY="..."

restic -r s3:https://s3.example.com/bucket init
restic -r s3:https://s3.example.com/bucket backup /etc /var/lib/docker/volumes /home

# retention policy
restic -r s3:https://s3.example.com/bucket forget --keep-daily 7 --keep-weekly 4 --keep-monthly 6 --prune
```

Create a cron and log outputs to detect failures. Without proven restoration, there is no backup.

---

## Embedded video

---

## Quick checklist to look “decent” today

* [ ] external nmap and internal ss/lsof: public 22/80/443 only
* [ ] non-root user with sudo, root disabled on SSH
* [ ] login by key only, PasswordAuthentication not
* [ ] SSH logs viewed and “ufw limit 22/tcp” active
* [ ] automatic security updates configured
* [ ] UFW and provider firewall closing everything except what is necessary
* [ ] encrypted backups, offsite and tested restoration

## Closing

There is no silver bullet, but with this you stop being given away and you gain time for what is important: building. If it works for you, apply it today. And if you have any specific questions about your setup (Docker, Coolify, Caddy, Traefik), leave them in the comments and we'll look at them together. Hugs and keep paddling with code and coffee.

---

> Original article in Spanish: [Seguridad básica para tu server self-hosted (2026) | SSH, Firewall, Updates y Backups](https://codigomate.com/seguridad-basica-para-tu-server-self-hosted-2026-ssh-firewall-updates-y-backups/)