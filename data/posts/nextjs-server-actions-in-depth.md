---
title: "Nextjs server actions in depth"
publishDate: "2025-05-22"
slug: "nextjs-server-actions-in-depth"
excerpt: "Next.js Server Actions let you run mutations and server logic from React components. A practical look at how they work and when to use them."
readingTime: 9
tags: ["nextjs", "react", "javascript", "nodejs", "server"]
---

Next.js Server Actions offer us a very powerful way to handle mutations and server logic directly from our React components. This makes the lines between client/server a little blurred, but does it in a dev-friendly way. At first glance they seem like magic, but underneath each call there is a hyper sophisticated system moving the gears to make this magic work. **In this post I'm going to try to do a *deep dive* into how Next.js turns these direct function calls into what are essentially very well-orchestrated API interactions**.

I'm going to assume that you have experience with React and Next, and that you want to understand the mechanics under the hood of Server Actions.

## The main idea: RPC type calls

At its core, a Server Action allows you to write an asynchronous function that runs on the server, but you call it from a client component, a component with ``use client'` as if it were a local function.

```
// app/my-page/page.tsx
export default function MyPage() {
  async function myAction(formData: FormData) {
    'useserver'; //The magic directive!
    const data = Object.fromEntries(formData);
    // ... interact with database, perform server-side logic ...
    console.log('Data received on server:', data);
    // ... revalidate cache, redirect, or return data ...
  }

  return (
    <form action={myAction}>
      <input type="text" name="message" />
      <button type="submit">Send to Server</button>
    </form>
  );
}
```

The beauty of all this is how the server logic is placed together with the graphical interface that calls it. But how does `myAction` actually run on the server when the client invokes it (in this case, with form submission)?

> The transformation from a dev-friendly abstraction to a secure and efficient way of handling client-server interaction is a modern marvel of this beautiful framework called nextjs.

## The path of a Server Action

The process can be broken down into 4 main stages:

1. **Build-time: Preparation and transformation**
2. **Call from the client**
3. **Processing and execution on the server**
4. **Response management on the client**

Let's see each stage:

### 1. Build-time: Preparation and transformation (Turbopack & Rust)

This is where Next.js (mainly through Turbopack, its Rust build system) lays the foundation:

* **Discovery:** This build process scans all your code to find the `'use server'` directives. This directive can be before an async function or at the beginning of a file, marking all exports of the file as Server Actions.
* **Transformation:** When a Server Action is found, a transformation is carried out.
  + The crucial part is that metadata is injected into the module. It is generally seen in the form of a comment (e.g., `// __next_internal_action_entry_do_not_use__{"actionName": "...", ...}`) which orders in a list all the functions of that module that are called Server action
* **Unique ID Generation:** For each Server Action, Next.js generates a uniquely hashed ID (let's call it `actionId`). This ID is typically derived from the module file path and the name of the action function. This `actionId` is critical for routing the request on the server.
* **Manifest generation (`server-reference-manifest.json`):**
  + A JSON manifest file is created (e.g., `dist/server/app/my-page/server-reference-manifest.json`).
  + This manifest acts like a map. Binds each `actionId` with information about how to load and execute the corresponding action. This includes a `moduleId` (which points to a JavaScript file generated as a "loader") and the specific export name (which is the same `actionId`) within that loader.
  + The structure of the manifest would look something like this (simplified):

    ```
    {
      "node": { // Or "edge" for edge runtime
        "hashed_action_id_for_myAction": {
          "workers": {
            "app/my-page/page": { // Context/route key
              "moduleId": "./../../../../.next/server/app/my-page/actions.js", // Path to the loader
              "isAsync": true
            }
          },
          "layer": {
            "app/my-page/page": "actionLayerName" // RSC layer information
          }
        }
      }
    }
    ```
* **Generation of the Action Loader file:**
  + A JavaScript file that acts as a loader (e.g., `.next/server/app/my-page/actions.js`) is generated for the route.
  + This loader does not have the logic of the actions directly. What it does is that it dynamically imports into the original module where yourAction is defined and then re-exports the server functions under the previously generated actionIds.

```
    // Example: .next/server/app/my-page/actions.js (conceptual)
    // (Dynamically imports your current page.tsx or actions.ts)
    // and re-exports actions using their hashed IDs
    export { myActionFromOriginalModule as hashed_action_id_for_myAction } from './../../../../app/my-page/page';
    ```

### 2. Client-side call

When your client-side code (for example, a form submission or a direct call from a client component) invokes a Server Action:

* **Proxy function:** You are not calling your server code directly. React and Nextjs create a proxy function on the client.
  + **`serverActionReducer` & `fetchServerAction`:** This proxy when called, typically involves `serverActionReducer` (found in `packages/next/src/client/components/router-reducer/reducers/server-action-reducer.ts`).
  + `fetchServerAction` is the function inside this reducer that takes care of building and sending the request.
  + **HTTP `POST` Request:** A `POST` call is made to the URL of the current page (or the URL associated with the Server Action if this is defined in a separate file)
  + **Critical HTTP Headers:**
    - `Next-Action`: This header is set to the `actionId` (the unique hashed ID that we talked about above) of the Server Action that we are invoking. This is the main mechanism which the server uses to identify what action to execute.
    - `Content-Type`: Generally it is `application/x-www-form-urlencoded` for form submissions or `text/x-component` for RSC-related actions. The server has to know how to 'parse' the body.
    - `Next-Router-State-Tree`: The current state of the client's router is sent here (a serialized representation of the component tree). This allows the server to understand the client context which is important for updating and diffing RSCs.
    - Other headers are sent for RSC as `RSC_HEADER`
  + **Request Body:**
    - If the call is executed by a form action, it is automatically serialized and sent as the body.
    - If called directly (e.g. `myAction(args)`), arguments are serialized using a mechanism like `encodeReply` that exits the `react-server-dom-webpack` dependency.

### 3. Server-Side management and execution

The Next.js server (either the Node.js or Edge runtime) receives the `POST` request:

* **Request Reception & Parsing:** The server parses the incoming request.
* **The action is identified (`packages/next/src/server/app-render/action-handler.ts`):**
  + The server reads the `Next-Action` header and gets the `actionId`.
  + the reference is consulted in the `server-reference-manifest.json` (often referred to as `serverModuleMap` in the Nextjs codebase).
  + With the `actionId`, the corresponding `moduleId` is searched (which points to the generated file `actions.js`) and also the export name (which is the `actionId` itself).
* **Deserialization of arguments:** The request body (which has the formdata or arguments serialized) is deserialized. For direct invocations, a server-side counterpart to `encodeReply` (from `react-server-dom-webpack/server.edge` or `react-server-dom-webpack/server.node`) is used.
* **Security Checks (CSRF Protection):**
  + Next.js checks and has CSRF (Cross-Site Request Forgery) protection. Typically it checks that the `Origin` header of the request matches the `Host` (or `X-Forwarded-Host`) header. This ensures that the request is coming from a trusted domain. It can be configured in `next.config.js` with the `serverActions.allowedOrigins` property.
* **Load and execute the action:**
  + The form server does a `require()`s or `import()`s of the identified loader file (`actions.js`).
  + Then the loader-specific export is accessed using the `actionId`.
  + This export is the reference to the original Server Action function: (e.g., `myAction`).
  + Finally the action is executed with the de-serialized arguments.
* **Worker Forwarding (Special Cases):** In distributed environments (e.g. edge deployments with several workers), if the worker that initially receives the request does not have the specific action code locally, nextjs can forward the request including headers and body to the worker that does. A special header is added to correctly handle this internal routing, `x-action-forwarded: 1`.
* **Result management:**
  + **Return data and update the UI (RSC):** If the action returns data that should update the UI, this data is usually packaged as an RSC "flight" payload. The Nextjs `generateFlight` utility is used for this, the payload represents the diff of the changes to the UI.
  + **Redirects:** If the action calls `redirect('/new-path')`, Nextjs interprets this special redirect and prepares an appropriate HTTP response. Add the correct code 307-308 and add a `Location` header.
  + **Cache Revalidation:** If you call `revalidatePath('/my-path')` or `revalidateTag('my-tag')`, the server will perform a cache revalidation. Also set an `x-action-revalidated` header in the response to notify the client.
  + **Cookies:** Any modification of the cookies, be it set or delete, during the action are sent to the client via a `Set-Cookie` headers in the response.

### 4. Response processing on the Client-Side

The client receives the response from the server:

* **RSC Payload:** If the server sends an RSC flight payload (Content-Type `text/x-component`), React will intelligently merge these updates into the client-side component tree. This happens without doing a full page reload. Very soft, fluid for the user.
* **Redirect:** If the server has a redirect status and the `Location` header, the nextjs client router handles navigation to the appropriate url.
* **Revalidation signals:** The client inspects the `x-action-revalidated` header. This tells the client to invalidate any data related to tags or paths that may be stuck in the router's cache.
* **Cookies:** The browser will automatically process any `Set-Cookie` headers.
* **Error Handling:** If the action results in an unhandled error, the closest `error.js` file will catch it or if there is a `<Suspense>` boundary it will catch it depending on the setup.

## The benefit of this complexity

These are the benefits of this complex system:

* **Better experience for the dev:** By being able to have the server logic next to the client, you save the step of the API, the fetch and so on, the mental model is simpler, and you reduce the context switch (bouncing between client and server).
* **Progressive improvements:** When server actions are used with HTML `<form>`, they work even if JS is disabled or has not loaded yet.
* **Less JavaScript on the client:** There is less fetching in the code, less state handling for loading or errors, and less logic needed to synchronize data on the client.
* **Security by design:** CSRF protection included by default. Using hashed IDs and manifest-based routing adds a layer of obfuscation to your routes.
* **Integration with Next features:** It works very easily with the cache, revalidation, and redirection of the Nextjs app router.

## Conclusion

Nextjs Server Actions are not *syntactic sugar*, they are much more. They represent a sophisticated RPC-type mechanism, which is integrated at a very deep level into the Nextjs runtime and build. By understanding the complete path from a simple function in a component to how it is transformed into a `POST` request handled by a serverside dispatcher that is based on a manifest, and finally how the client updates the UI or navigation, you will be able to use this tool even better and you will be able to debug it with greater discretion. The transformation from a dev-friendly abstraction to a secure and efficient way of handling client-server interaction is a modern marvel of this beautiful framework called nextjs.

---

> Original article in Spanish: [Nextjs server actions en profundidad](https://codigomate.com/nextjs-server-actions-en-profundidad/)