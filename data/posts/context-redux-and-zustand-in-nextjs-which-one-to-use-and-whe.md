---
title: "Context, Redux or Zustand in Next.js?"
publishDate: "2025-01-28"
slug: "context-redux-and-zustand-in-nextjs-which-one-to-use-and-whe"
excerpt: "A work project forced me to compare Context, Redux and Zustand for global state. Here is when each one makes sense in React and Next.js."
readingTime: 8
tags: ["nextjs"]
---

There was a situation at work where a project in React (React without anything else, the old way, with Webpack) began to need some way to maintain a "global" state. We needed to access user information from multiple components, and sometimes there were 4 or 5 levels, or even more, deep in the component hierarchy tree.

![Many levels](/public/posts/screenshot-2025-01-28-092444.webp "Many levels")

---

If it were a personal project, I would have set up a store with Zustand and that's it.

```
import { create } from 'zustand';

const useUserStore = create((set) => ({
  email: '',
  name: '',
  setUser: (email, name) => set({ email, name }),
  clearUser: () => set({ email: '', name: '' }),
}));

export default useUserStore;
```

Very simple.

We need to use it and we do

```
import React from 'react';
import useUserStore from './userStore';

const UserProfile = () => {
  const { email, name, setUser, clearUser } = useUserStore();

  return (
    <div>
      <h2>User Profile</h2>
      <p>Email: {email || 'No email set'}</p>
      <p>Name: {name || 'No name set'}</p>
      <button onClick={() => setUser('user@example.com', 'John Doe')}>
        SetUser
      </button>
      <button onClick={clearUser}>Clear User</button>
    </div>
  );
};

export defaultUserProfile;
```

## And then? What happened? Why didn't I use it?

Well, at work it's not that simple. They require that each new bookstore have a justification; It's no use saying "it's simpler and that's it". It has to be analyzed from several points of view:

* Bookcase weight
* Ease of use
* Integration with existing projects
* Security (vulnerabilities)
* Popularity and maintenance

Everything is possible, right? I mean, without going too deep, I think Zustand complies with everything, but the thing is that they are going to ask you in depth about several things. And well, I don't know about you, but I don't have the whim of using the bookstore at work either. In other words, I'm not going to become an expert in Zustand or spend 4 hours at an expo so that maybe they'll tell me: "it's better not to use it." All to have two values ​​in global state.

### And what alternative do I have?

Use Redux (which someone already introduced and was left years ago) or use Context. Context is a React API, so there's no need to ask for permission, obviously. It's included there, you can use it.

If you know me at all (as a dev), you know that I would never choose Redux. But maybe you don't know me, so I'll explain why:

* I kind of hate Redux and its patterns. Having to add things in multiple files (`dispatchers`, `reducers`, `actions`) makes me tired and confused. I always have to read already assembled files and copy them, my brain refuses to learn the pattern by heart.
* Although Redux boasts of having good performance (and I understand that it is super tested as well), for this case where we have two variables, we do not need it. Two variables, three or twenty are going to go well with Zustand or Context. It would be different if there were a thousand, right?
* Redux shines when you need highly structured global state with middleware, logging, or devtools, but for something as simple as storing user data, it just adds unnecessary friction.

So, by default, let's go with Context, which although I don't really like it, would look something like this:

`UserContext.ts`

```
import React, { createContext, useContext, useState } from 'react';

// Create the context
const UserContext = createContext();

export const UserProvider = ({ children, initialUser }) => {
  const [user, setUser] = useState(initialUser);

  const setUserInfo = (email, name) => setUser({ email, name });
  const clearUser = () => setUser({ email: '', name: '' });

  return (
    <UserContext.Provider value={{ ...user, setUserInfo, clearUser }}>
      {children}
    </UserContext.Provider>
  );
};

// Hook to use the context
export const useUser = () => useContext(UserContext);
```

With a fetch in the root of the component that obtains the logged in user:

```
import React, { useEffect, useState } from 'react';
import ReactDOM from 'react-dom';
import App from './App';
import { UserProvider } from './UserContext';

const Root = () => {
  const [user, setUser] = useState(null);

  useEffect(() => {
    fetch('/api/user') 
      .then((res) => res.json())
      .then((data) => setUser(data))
      .catch(() => setUser({ email: '', name: '' })); 
  }, []);

  if (user === null) return <p>Loading...</p>;

  return (
    <UserProvider initialUser={user}>
      <App />
    </UserProvider>
  );
};

ReactDOM.render(<Root />, document.getElementById('root'));
```

In React, it makes sense to have a `Context` to avoid so much *prop drilling*.

### Why do I never use `Context` in Next.js?

Because Next.js has its paths defined in folders and *out of the box* comes with SSR components, we are loading the user from the server. Having a well-put together layout for each page and the necessary state in each one, the user information and/or anything that needs to be global is one or two levels away from the component, three or four at most.

![few levels deep](/public/posts/Screenshot-2025-01-28-092703.webp "few levels deep")

I think that with four levels it doesn't even make sense to add global status, especially for such small things.

Additionally, **most of the action logic is in actions files (`actions.ts`)**, and *data fetching* occurs in asynchronous components. This eliminates the need for `reducers`, `dispatchers` and `useEffects` in many cases.

For example:  
`app/actions/getUser.ts`

```
export async function getUser() {
  const res = await fetch(`${process.env.API_URL}/api/user`, { cache: 'no-store' });
  if (!res.ok) throw new Error('Failed to fetch user');
  return res.json();
}
```

Our layout if you need the data:

```
import { getUser } from '@/lib/actions';

export default async function Layout({ children }) {
  const user = await getUser();

  return (
    <div>
      <header>
        <h1>Welcome, {user.name}</h1>
      </header>
      {children}
    </div>
  );
}
```

And the page would do the same.

```
export default async function ProfilePage() {
  const user = await getUser();
  return (
    <div>
      <h2>Profile</h2>
      <p>Email: {user.email}</p>
      <p>Name: {user.name}</p>
    </div>
  );
}
```

Two API calls instead of one, nothing happens.

First:

> Next.js extends the [`fetch` API](https://nextjs.org/docs/app/building-your-application/caching#fetch) to automatically **memoize** requests that have the same URL and options. This means you can call a fetch function for the same data in multiple places in a React component tree while only executing it once.

Second:

It's just an example. If you were using something like `next-auth`, the user would be defined in memory, and in every server component that needs the user:

```
import { getServerSession } from "next-auth/next"
import { authOptions } from "@/utils/authOptions"
import { redirect } from "next/navigation"
import { getUserBillingInfo } from '@/services/billing'
import { getUserSites } from '@/services/site'
import { BillingCard } from '@/components/dashboard/billing-card'
import { TransactionHistory } from '@/components/billing/transaction-history'
import { User } from '@/types'

type UserWithId = User & { id: number }

export default async function BillingPage() {
    const session = await getServerSession(authOptions)
    const user = session?.user as UserWithId;

    if (!user) {
        redirect('/api/auth/signin')
    }

    const sites = await getUserSites(user.id)
    const billingInfo = await getUserBillingInfo(user.id)

    return (
        <div className="py-6">
            <div className="container mx-auto px-4 space-y-6">
                <h2 className="text-3xl font-bold tracking-tight">Billing & Payments</h2>

<div className="space-y-6">
                    <BillingCard billingInfo={billingInfo} sites={sites} />
                    <TransactionHistory userId={user.id} />
                </div>
            </div>
        </div>
    )
}
```

The same would happen with `i18n`. If we had to manage locals without any library, we would probably need `Context`, but with `next-intl` or something else, all that handling is done by the library.

## When would you use global state in Next.js?

The case where I found that I do need global state is when there are certain options or information that the user gives us and we want to persist it temporarily while navigating through different routes.

### Example: a *flow* of "Get Started"

* User chooses certain options and clicks pay.
* You are redirected to complete your information.
* You are redirected to the cart page.

In this case, we have to keep your cart info in state. It makes sense to save this in the *browser*, but not in a BDD, since it may be temporary.

Here we put a `Zustand` with `persist`, and we have many arguments to use it. We could also do it with `Context`.

But it's not that common in my Next.js projects, let alone something that happens very often, unless the application is really big. *Flows* I encounter them from time to time, and ultimately, it doesn't matter how often you encounter them, but that you understand when one pattern should prevail over another.

Final comparison:

| Solution | Pros | Cons |
| --- | --- | --- |
| **Zustand** | Simple, minimal friction, good performance | It's not as popular as Redux and it's not that much better than context |
| **Context API** | React native, no dependencies | Can affect performance if abused |
| **Redux** | Scalable, advanced tools | Boilerplate unnecessary for simple cases |

### Final thoughts (summary)

Anyway, if you have to take anything from this it is:

* Don't set global status if it's not necessary. With 3 or 4 levels, *prop drilling* is not a real problem.
* In Next.js, many times `Context` is unnecessary because data can be obtained from the server in each component that needs it.
* Redux is overkill for most simple cases. If you only have to save a couple of values, it is better to avoid the boilerplate.
* If you really need to persist global state on the client (example: a checkout flow), Zustand with `persist` is a solid option.
* In the end, it doesn't matter how often you encounter these cases, but rather understanding when a pattern is worth it and when it's just going to complicate your life.

---

> Original article in Spanish: [Context, Redux y Zustand en Next.js: ¿Cuál usar y cuándo?](https://codigomate.com/context-redux-y-zustand-en-next-js-cual-usar-y-cuando/)