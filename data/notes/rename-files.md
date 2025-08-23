---
title: "Bash rename files 1 liner"
publishDate: "2024-01-01"
excerpt: "This is a test note"
tags: ["note", "test"]
slug: "rename-files"
---

# Bash rename files 1 liner

Building this website, I found out images are better being served from the nextjs server, instead of from my cloud image hosting.
So I had to rename all the images, because they had weird names as they were downloaded from google photos.
To rename each car image, if you are just looking for `1.webp`, `2.webp`, etc, you can use this command:

```bash
counter=1; for file in *.webp; do mv "$file" "$counter.webp"; ((counter++)); done
```