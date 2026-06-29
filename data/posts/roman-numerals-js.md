---
title: "Roman Numerals to Integer in JavaScript"
publishDate: "2024-10-26T12:00:00Z"
excerpt: "Convert Roman numerals like III or IX to integers in JavaScript. A fun logic problem that rewards careful step-by-step thinking."
coverImage: "https://cloud.codigomate.com/c9ucli.jpg"
readingTime: 2
tags: ["leetcode", "javascript"]
slug: "roman-numerals-js"
---

## Roman Numerals

The problem is to convert a Roman numeral to an integer. You get a string like "III" and you need to return 3.
You know roman numerals right? Essentially, they are a numeral system originating in ancient Rome that uses letters to represent numbers.
The letters are I, V, X, L, C, D, and M. And they have the following values:
I = 1
V = 5
X = 10
L = 50
C = 100
D = 500
M = 1000

So the problem has some bounds:

```
It is guaranteed that s is a valid roman numeral in the range [1, 3999].
```

So we know the input is always valid.

You also get an input in a string format. Just looking at this, it seems very easy, just parse each value and sum them up.
The only caveat is when you substract, like IV = 4 or CM = 900.

So we got three cases:

```
Example 1:

Input: s = "III"
Output: 3
Explanation: III = 3.
Example 2:

Input: s = "LVIII"
Output: 58
Explanation: L = 50, V= 5, III = 3.
Example 3:

Input: s = "MCMXCIV"
Output: 1994
Explanation: M = 1000, CM = 900, XC = 90 and IV = 4.
```

This is my intial approach, Im not particulary proud of the flag and the continue, but it works.

```javascript
/**
 * @param {string} s
 * @return {number}
 */
var romanToInt = function(s) {
    //Maybe parse the string to array and start summing. Create a key of values, and do the substraction logic
    const values = {
        "I": 1,
        "V": 5,
        "X": 10,
        "L": 50,
        "C": 100,
        "D": 500,
        "M": 1000
    }
    const romanArray = s.split("");
    let accumulatedTotal = 0;
    let skipNext = false;
    for(let i = 0; i < romanArray.length; i++) {
        if(skipNext) {
            skipNext = false;
            continue
        }
        const currValue = values[romanArray[i]]
        const nextValue = values[romanArray[i + 1]]
        if(i === romanArray.length - 1 || currValue >= nextValue) {
            accumulatedTotal+= currValue
        } else {
            accumulatedTotal+= (nextValue - currValue)
            skipNext = true;
        }
    }
    return accumulatedTotal
};
```

Essentially, we iterate through the string by splitting it into an array and using a traditional for loop as I've been doing. 

We could probably just use a reducer: 

```javascript

/**
 * @param {string} s
 * @return {number}
 */
var romanToInt = function(s) {
    const values = {
        "I": 1,
        "V": 5,
        "X": 10,
        "L": 50,
        "C": 100,
        "D": 500,
        "M": 1000
    };

    return s.split("").reduce((accumulatedTotal, currChar, i, romanArray) => {
        const currValue = values[currChar];
        const nextValue = values[romanArray[i + 1]];

        // Add or subtract depending on the comparison with the next value
        if (i === romanArray.length - 1 || currValue >= nextValue) {
            return accumulatedTotal + currValue;
        } else {
            return accumulatedTotal + (nextValue - currValue);
        }
    }, 0);
};

```

But still, I think its more readable and easier to understand the first approach.

Submitting this to leetcode, we get the following results:

![Roman numerals js](https://cloud.codigomate.com/0atukf.webp)

Not really sure how you would get less performance than that, like it seems the majority of people got right on the middle, which takes 100ms. This one takes 6ms.

Complexity wise, it is O(n) because we are iterating through the string once. 

Still, I think this is a good problem to practice general logic and problem solving. This one took less than 10 minutes to solve and a fun experience!