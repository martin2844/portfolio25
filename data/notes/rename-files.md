---
title: "Bash One-Liner to Rename Files in Bulk"
publishDate: "2024-01-01"
excerpt: "Rename a folder of images in order with a single Bash loop. The one-liner I used to clean up weird filenames from Google Photos downloads."
tags: ["note", "test"]
slug: "rename-files"
---

## Bash rename files 1 liner

Building this website, I found out images are better being served from the nextjs server, instead of from my cloud image hosting.
So I had to rename all the images, because they had weird names as they were downloaded from google photos.
To rename each car image, if you are just looking for `1.webp`, `2.webp`, etc, you can use this command:

```bash
counter=1; for file in *.webp; do mv "$file" "$counter.webp"; ((counter++)); done
```