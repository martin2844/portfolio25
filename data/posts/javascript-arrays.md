---
title: "Javascript Arrays"
publishDate: "2021-01-08"
slug: "javascript-arrays"
excerpt: "In this tutorial you will be able to see 8 methods applicable to arrays in Javascript that will help us work better with them.   Filter, map, some, includes, every, forEach, reduce, find  Part 1 1.Fil..."
readingTime: 3
tags: ["javascript", "finance"]
---

In this tutorial you will be able to see 8 methods applicable to arrays in Javascript that will help us work better with them.  
Filter, map, some, includes, every, forEach, reduce, find

### Part 1

1.Filter

```
const members = [
        {name: "John", age: 20},
        {name: "Raul", age: 22},
        {name: "Ignacio", age: 16},
        {name: "Felipe", age: 14},
        {name: "Roberto", age: 17},
        {name: "Faust", age: 19},
]

const minors = members.filter((member) => {
    return member.age < 18
})

console.log(minors);
```

will return this =

```
(3) [{…}, {…}, {…}]
0: {name: "Ignacio", age: 16}
1: {name: "Felipe", age: 14}
2: {name: "Roberto", age: 17}
length: 3
__proto__: Array(0)
```

---

2.Map

Map will create a new array with the result of the function for each element of the array.  
It works in the previous example, if we want an array with only the ages

```
const members = [
        {name: "John", age: 20},
        {name: "Raul", age: 22},
        {name: "Ignacio", age: 16},
        {name: "Felipe", age: 14},
        {name: "Roberto", age: 17},
        {name: "Faust", age: 19},
]

const onlyNames = members.map((member) => {
    return member.name
})

console.log(onlyNames);
```

will return this =

```
(6) ["Juan", "Raul", "Ignacio", "Felipe", "Roberto", "Fausto"]
0: "John"
1: "Raul"
2: "Ignatius"
3: "Philip"
4: "Robert"
5: "Faust"
length: 6
__proto__: Array(0)
```

---

3. Some

The some method tests that at least one of the elements of the array passes the test that we put in the function.  
Returns a Boolean, that is, true or false.

```
const array = [1,2,3,4,5,6,7,8];

const thereareEvenNumbers = array.some((number) => {
    return number % 2 === 0;
});

console.log(thereareEvenNumbers);
```

simply returns true

---

4. Includes

Like some, it will return a Boolean. This method will verify that the array contains a certain element.

```
const array = [1,2,3,4,5,6,7,8];

const containsNumberSeven = array.includes(7);

console.log(thereareEvenNumbers);
```

simply returns true

---

5. Every

It will be determined that each element of the array meets the condition that we set. If true, it returns true, otherwise false.  
Example:

```
var array2 = [1,2,3,4,5,6,7];

var menoraQue8 = array2.every((number) => {
    return number < 8
});

console.log(menoraQue8);
```

simply returns true

---

6. forEach

This is probably one of the most used, it serves to avoid a traditional for loop. It is much cleaner in its syntax.  
What it will do is repeat what we pass through each element of the array.

```
var array2 = [1,2,3,4,5,6,7];

array2.forEach((number) => {
    console.log("this is a for each Loop " + number );

});
```

Returns -->

```
this is a for each Loop 1
this is a for each Loop 2
this is a for each Loop 3
this is a for each Loop 4
this is a for each Loop 5
this is a for each Loop 6
this is a for each Loop 7
```

---

8.Find

```
var array1 = [6, 14, 28, 4, 34];

var found = array1.find(function(number) {
  return number > 10;
});

console.log(found);
```

Returns ---> 14

This is because it returns the first result it finds that meets the condition we pass to it.

---

## Watch the video, everything is much better explained!

[![Watch the video](https://i.ytimg.com/vi/IFzWJ_Gtr4A/hqdefault.jpg)](https://www.youtube.com/watch?v=IFzWJ_Gtr4A&t)

---

> Original article in Spanish: [Javascript Arrays](https://codigomate.com/javascript-arrays/)