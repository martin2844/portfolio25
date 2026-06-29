---
title: "Solving the Two Sum Problem in JavaScript"
publishDate: "2024-10-23T17:00:00Z"
excerpt: "My first LeetCode problem started as a naive O(n²) mess and ended with a hash map. A walkthrough of the mistakes and the final O(n) solution."
coverImage: "https://cloud.codigomate.com/c9ucli.jpg"
readingTime: 6
tags: ["leetcode", "javascript"]
slug: "two-sum"
---

## Two Sum

So Im finishing up my degree on CS, and realized I've never proper done leet code.
Of course I know programming, I've been doing it for over 5 years now, but what about leet code stuff?
I've done challenges before specially for interviews but never really did better than just passing with the naive solutions. I feel personally like that is a nice approach though, start small and build up.
The naive approach is not always the worst right?

Anwyays, opened up leet code, and started with problem number 1: two sum.

My naive approach was to iterate through the array with two for loops and check if the sum of any two numbers equal the target.
It took my five minutes and it worked right? but its disgusting, and its at the end of the list of solutions. Quickly looking at it, the complexity is O(n^2) and the space is O(1).
I mean, double loops is always at least O(n^2) right?

These is the task:

```text
Given an array of integers nums and an integer target, return indices of the two numbers such that they add up to target.

You may assume that each input would have exactly one solution, and you may not use the same element twice.

You can return the answer in any order.

 

Example 1:

Input: nums = [2,7,11,15], target = 9
Output: [0,1]
Explanation: Because nums[0] + nums[1] == 9, we return [0, 1].
Example 2:

Input: nums = [3,2,4], target = 6
Output: [1,2]
Example 3:

Input: nums = [3,3], target = 6
Output: [0,1]
```

This is my first attempt at the code:

```javascript
var twoSum = function(nums, target) {
        const answer = []
        for(i = 0; i < nums.length; i++) {
                if(answer.length) {
                        break;
                }
                nums.forEach((n, j) => {
                  if(j !== i) {
                   if((nums[i] + n) === target) {
                           answer.push(i)
                           answer.push(j)
                           return
                   }
                  }
                })
        }
        return answer;
};
```

Also the returns there struck me wrong somehow, they dont feel really neat.
These were the results:
![naive two sum](https://cloud.codigomate.com/hrrenw.webp)

Second iteration, I went to the drawing board. Literally got a pen and paper and thought about the problem.
We could at least use i as a pointer, and split the array into two, so that we start always from the next element of i, instead of the beginning of the array. This should save some time right?

so, for the second attempt, I did this:

```javascript
var twoSum = function(nums, target) {
        const answer = []
        let found = false;
        for(i = 0; i < nums.length; i++) {
           //Cursor is I - so first we split the array between i and the rest
           const arr = nums.slice(i + 1, nums.length)
           for(j = 0; j < arr.length; j++) {
                   if(found) {
                           break;
                   }
                if((nums[i] + arr[j]) === target) {
                        // find original pointer based on j
                        // arr.length nums.length - arr.length
                        answer.push(i)
                        answer.push(j + nums.length - arr.length)
                        found = true;
                        break
                }
           }
        }
        return answer;
};
```

So now, we improved but still very weak, still at O(n^2) and space O(1).

![second two sum](https://cloud.codigomate.com/vkpva4.webp)

More intense staring at my drawings....
so then I remembered the memes --- use a hash map. Its actually brilliant. How can I fit a hashmap to my design lol?
Well, staring at the drawing of my array, what I could do is for every iteration, I can calculate what that position is missing to reach the target and store it as a a key.

So for the example of 2,7,11,15 and target 9:
when I am standing at position 2, I can calculate what is missing to reach the target, which is 7.
So I store 7 as a key with the value of the position, which is 0.
For the next iteration, I am in position 1, and the value is a 7. We can check in the hashmap if that key exists, and if it does, we have found the key of the number that makes up the target.

Same can be done for the other example, 3,2,4 and target 6.
On the first position we find 3, we would need another 3 to reach the target so we store 3 as a key with the value of the position, which is 0.
On the next position we find 2, we would need 4 to reach the target, so we store 4 as a key with the value of the position, which is 1.
On the last position we find 4, we would need 2 to reach the target, and we already know that 2 is in the hashmap with the value of 1, so we have found the two numbers that make up the target.

It took me a while, because for the first example the value is literally 0, and that broke the if statement because I was checking if a value was truthy.

```javascript
var twoSum = function(nums, target) {
        let answer = []
        const hashMap = {}
        for(i = 0; i < nums.length; i++) {
          const whatImMissing = target - nums[i];
          hashMap[whatImMissing] = i;
          if(hashMap[nums[i]] !== undefined) {
                  const key = nums[i]
                  answer = [hashMap[key], i]
          }
        }
        return answer;
};
```

So I was doing `if(hashMap[nums[i]]) {}` and of course if that produces a 0, which it did on the first example, it broke the if statement because 0 is falsy.

Anyways, we will run this now, and newbie mistake. I forgot to test all examples and it was failing on the last one:
[3,3] and target 6.

So it wasnt evident for two seconds, but then I realized I was overwriting the keys. I should check first if the key exists and if it does not then we add it, if it does we have the answer.

So finally:
```javascript
var twoSum = function(nums, target) {
        let answer = []
        const hashMap = {}
        for(i = 0; i < nums.length; i++) {
          if(hashMap[nums[i]] !== undefined) {
                  const key = nums[i]
                  answer = [hashMap[key], i]
          }
          const whatImMissing = target - nums[i];
          hashMap[whatImMissing] = i;
        }
        return answer;
}
```

With the correct answer:

![third two sum](https://cloud.codigomate.com/po8fse.webp)

Beats 96.95% of the answers, good for me, for my first leet code problem I guess...
Time complexity is O(n) and space is O(n).
Very nice, we went from O(n^2) to O(n). Its massive.

### Key takeaways

- Always test all examples, even if you think you have the correct answer.
- Hash maps are a good tool to have in your belt.
- 0 is falsy, so be careful with that.

Also, I dont think I would've done this well if I wasnt doing a course on algorithms and data structures, even though we're going through things like merge sort, recursion and other basic introductory things, 
having my mind fresh to these kind of coding helped a ton. The course is also in java which also forces me to think outside the box and not really on easy js methods...

although, while Im typing this I just realized I could've used a reducer and make it more cleaner. 

```javascript
var twoSum = (nums, target) => {
  const initialState = { hashMap: {}, answer: [] };

  return nums.reduce((acc, num, index) => {
    if (acc.hashMap[num] !== undefined) {
      acc.answer = [acc.hashMap[num], index];
    }
    const whatImMissing = target - num;
    acc.hashMap[whatImMissing] = index;
    return acc;
  }, initialState).answer;
};
```
This does marginally better, beats 97.15% of the answers. But very close, so will stick to for loops for clarity.