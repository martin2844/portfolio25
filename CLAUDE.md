# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a minimal, server-side rendered (SSR) portfolio site built with PHP 8.3, nginx, and Docker. The project emphasizes performance, accessibility, and zero JavaScript requirements while maintaining a VS Code dark theme aesthetic.

## Development Commands

```bash
# Quick Development Setup (with hot reload)
./dev.sh                      # Start development environment with hot reload
# Visit http://localhost:3002  # Changes are reflected immediately

# Manual Development Commands
docker-compose -f docker-compose.dev.yml up --build -d  # Start dev environment
docker-compose -f docker-compose.dev.yml down           # Stop dev environment
docker-compose -f docker-compose.dev.yml logs -f        # View logs

# Production Commands
docker-compose up -d           # Start production server
docker-compose down           # Stop production server

# Direct Docker commands
docker build -t minimal-ssr .  # Build production image
docker run -p 3002:8080 minimal-ssr  # Run production container
```

## Architecture

### Tech Stack
- **PHP 8.3-FPM** with OPcache for ultra-fast server-side rendering
- **nginx** with gzip compression and aggressive caching
- **Docker** with Alpine Linux for minimal container size
- **File-based content management** using Markdown and YAML

### Core Components

#### Router (`index.php`)
Simple URL routing system that maps clean URLs to page controllers:
- `/` → `pages/home.php`
- `/blog` → `pages/blog.php` 
- `/blog/{slug}` → `pages/blog-post.php`
- `/portfolio` → `pages/portfolio.php`
- `/notes` → `pages/notes.php`
- `/about` → `pages/about.php`
- `/cv` → `pages/cv.php`

#### Content Management (`includes/functions.php`)
Contains two main classes:
- **MarkdownParser**: Converts Markdown files with YAML frontmatter to HTML
- **DataLoader**: Loads and processes content from data files

#### Layout System (`templates/layout.php`)
Single template file providing:
- SEO-optimized meta tags and structured data
- Accessibility features (skip links, ARIA labels, focus management)
- VS Code dark theme styling
- Performance optimizations

### Content Structure

```
/data/
├── posts/           # Blog posts (.md files with YAML frontmatter)
├── notes/           # Technical notes (.md files with YAML frontmatter)
└── pages/           # Static content (.yaml and .md files)
    ├── portfolio.yaml    # Project showcase data
    ├── cars.yaml        # Car collection data
    ├── cv.yaml          # Resume/CV data
    ├── about.md         # About page content
    └── metadata.yaml    # Site metadata
```

#### Content Format
**Markdown files** use YAML frontmatter:
```yaml
---
title: "Post Title"
publishDate: "2024-01-01"
excerpt: "Brief description"
tags: ["tag1", "tag2"]
---
# Content starts here
```

**YAML files** store structured data for dynamic content rendering.

### Performance Features
- **OPcache enabled** for PHP bytecode caching
- **nginx gzip compression** with optimized settings
- **Aggressive HTTP caching** headers
- **Static file optimization** with 1-year cache expiry
- **~50ms response times** for dynamic pages

### Security Measures
- Files run as non-root `webuser`
- Hidden sensitive files (`.yaml`, `.md`, dotfiles)
- Security headers (X-Frame-Options, X-Content-Type-Options, etc.)
- Input sanitization in content parsing

## Development Guidelines

### Adding New Content
1. **Blog posts**: Create `.md` files in `data/posts/` with YAML frontmatter
2. **Notes**: Create `.md` files in `data/notes/` with YAML frontmatter  
3. **Portfolio items**: Edit `data/pages/portfolio.yaml`
4. **Static pages**: Add route to `index.php` and create corresponding page in `pages/`

### Code Conventions
- Use PHP 8.3 syntax and features
- Follow existing error handling patterns
- Maintain the file-based content approach
- Preserve the VS Code theme aesthetic
- Ensure accessibility compliance

### Performance Considerations
- Keep container size minimal (currently ~20MB)
- Optimize file I/O operations
- Leverage nginx caching effectively
- Maintain zero JavaScript requirement

## Development vs Production

### Development Setup
- **Hot reload enabled**: Files mounted as volumes for instant changes
- **OPcache configured for development**: 2-second revalidation frequency
- **No caching**: All HTTP cache headers disabled
- **Error display enabled**: PHP errors shown for debugging
- **Faster container startup**: Optimized for development workflow

### Production Setup
- **Optimized OPcache**: Zero revalidation for maximum performance
- **Aggressive caching**: Long-lived HTTP cache headers
- **Gzip compression**: Enabled for bandwidth optimization
- **Error logging**: Errors logged but not displayed

## Container Configuration

### nginx
- **Production** (`nginx-ssr.conf`): Gzip enabled, aggressive caching
- **Development** (`nginx-ssr.dev.conf`): No caching, instant updates

### PHP-FPM
- **Production** (`php-fpm.conf`): Optimized process management
- **Development** (`php-fpm.dev.conf`): Error display enabled, fewer processes

### Docker
- **Production** (`Dockerfile`): Copies files into container
- **Development** (`Dockerfile.dev`): Volume mounts for hot reload