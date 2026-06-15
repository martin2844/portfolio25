---
title: "This is how I leave my Mac ready to program from scratch (2025)"
publishDate: "2025-04-14"
slug: "this-is-how-i-leave-my-mac-ready-to-program-from-scratch-202"
excerpt: "Every time I pick up a new Mac, the same thing happens to me: I want to be ready to work in less than an hour, without wasting time with a thousand windows or strange configurations. That's why I put ..."
readingTime: 4
tags: ["docker", "sqlite"]
---

Every time I pick up a new Mac, the same thing happens to me: I want to be ready to work in less than an hour, without wasting time with a thousand windows or strange configurations. That's why I put together this step by step with what I install and how I leave everything neat to code, move quickly between desktops and not fight with the environment. It is the recipe I use today in 2025, simple and direct.

## What do I install and why

### 1) Chrome

I boot to the browser to sync bookmarks, passwords, and basic dev extensions. My must-haves:

* uBlock Origin (to clean the web)
* JSON Viewer (so as not to cry with API responses)
* Vimium or similar if you like to navigate with a keyboard

Tip: log in immediately and leave sync activated, it will save you hours.

### 2) Homebrew (the package manager)

I need Brew to install everything else without twists.

```
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
brew doctor
brew update
```

While we're at it, I activate the nerd fonts so that the icons and powerlines look good in the terminal:

```
brew tap homebrew/cask-fonts
brew install --cask font-jetbrains-mono-nerd-font
```

### 3) Oh My Zsh (shell on steroids)

Zsh already comes on macOS, but with Oh My Zsh everything gets a little more love:

```
sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"
```

Extras that speed me up:

```

# Autocomplete and suggestions
brew install zsh-autosuggestions zsh-syntax-highlighting
```

Then you activate them in your ~/.zshrc:

```
plugins=(git zsh-autosuggestions zsh-syntax-highlighting)
```

Optional: JetBrains Mono Nerd font in Terminal and that's it.

### 4) NVM (Node Version Manager)

To manage Node versions and not break anything from project to project:

```
brew install nvm
mkdir -p ~/.nvm
echo 'export NVM_DIR="$HOME/.nvm"' >> ~/.zshrc
echo '[ -s "/opt/homebrew/opt/nvm/nvm.sh" ] && . "/opt/homebrew/opt/nvm/nvm.sh"' >> ~/.zshrc
source ~/.zshrc

nvm install --lts
nvm alias default lts/*
node -v
```

### 5) Cursor (editor)

I switched to Cursor for speed and integrated AI features, but with the “feel” of VSCode. I install it like this:

```
brew install --cask cursor
```

Quick tips:

* Activate configuration sync.
* Vim style keybindings if you use a keyboard.
* Minimum and necessary extensions (linters/formatters per language).

### 6) Tmux (terminal multiplexer)

For me, tmux = superpowers in the terminal. Windows, pans and persistent sessions.

```
brew install tmux
```

Basic config in ~/.tmux.conf to get started comfortably:

```
set -g mouse on
setw -g mode-keys vi
set -g history-limit 100000
unbind C-b
set -g prefix C-a
bind C-a send-prefix
```

And if you want to go one step further: tpm (plugin manager), tmux-resurrect, cool bar status. But with this you'll fly.

### 7) DBeaver (database)

Universal client to handle Postgres, MySQL, SQLite, whatever comes.

```
brew install --cask dbeaver-community
```

Tip: use SSH tunneling to connect to VPS bases without opening ports. And dark mode, obviously.

### 8) Docker

To quickly build services and test premises without drama.

```
brew install --cask docker
```

If you have Apple Silicon:

* Many images already support arm64.
* If something fails, try `--platform linux/amd64` in `docker run` or `docker-compose.yml`.

## How do I configure desktops to “fly” between screens

Mac has a good Spaces system, but it needs to be tamed a little:

* System Settings → Desktop and Dock:

  + Disable “Automatically reorder spaces”.
  + Keep “Screens have separate spaces” enabled if you use multiple monitors.
  + Animations: if they make you dizzy, turn them down.
* Keyboard shortcuts:

  + Settings → Keyboard → Shortcuts → Mission Control:
  + Activate “Switch to desktop 1… 6” and put Ctrl+1, Ctrl+2, etc.
  + “Move a window to desktop left/right” also adds up.
* Hot Corners:

  + Top left: Mission Control.
  + Bottom right: Desk.
  + (I chose the ones that work for you, but with these two you already gain speed.)
* Assign apps to spaces:

  + Right click on the Dock icon → Options → Assign to “This Desktop”.
  + I do something like this:
  + Desktop 1: browser
  + Desktop 2: editor
  + Desktop 3: terminal/tmux
  + Desktop 4: DB/DBeaver
  + Desktop 5: docs/notes
  + With Ctrl+Number you jump in one touch and everything is organized.

## The video step by step

## Closing

That is my 2025 recipe to leave the Mac ready and go out to build without obstacles. If it works for you, try adapting it to your flow and tell me which tool you are missing on a new machine. As always, thank you for being on the other side. If you liked it, share it with someone who is new to Mac and see you next time with another mate and more code. 🙌☕️

---

> Original article in Spanish: [Así dejo mi Mac lista para programar desde cero (2025)](https://codigomate.com/asi-dejo-mi-mac-lista-para-programar-desde-cero-2025/)