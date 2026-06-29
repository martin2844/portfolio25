---
title: "Solve Palindrome in JavaScript"
publishDate: "2024-10-24T22:00:00Z"
excerpt: "LeetCode problem 9 solved in one line using JavaScript built-ins. A quick look at toString, split, reverse and why knowing the basics helps."
coverImage: "https://cloud.codigomate.com/c9ucli.jpg"
readingTime: 2
tags: ["leetcode", "javascript"]
slug: "palindrome"
---

## Palindrome

So, I couldnt wait that much to do another one. Had 10 minutes to spare, and I was like, "okay, I'll do problem 9 of leet code". Essentially this was the next easy problem following the two sum.

Right of the bat I knew this can be done in a one liner, so I wanted to test if its efficient I mean, I'm sure that built in methods are efficient right?

So this is what I came up with

```javascript
var isPalindrome = function(x) {
    return x.toString().split("").reverse().join("") === x.toString();
};
```

We need to convert the `x` to a string since its an integer, then we split it into an array, reverse it, and join it back into a string.
By doing the `===` we are comparing the reversed string with the original string and returning a boolean forced by the comparison operator.
This complies with the problem requirements, and all in one line.

Essentially this was o(n) so good enough

![palindrome](https://cloud.codigomate.com/e7fn76.webp)