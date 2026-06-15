---
title: "Record EVERYTHING your users do (with Clarity) - put it in nextjs"
publishDate: "2025-04-10"
slug: "record-everything-your-users-do-with-clarity-put-it-in-nextj"
excerpt: "For a while I wanted to bring something simple, free and that gives value to your touch. Microsoft Clarity is exactly that: a “put and see” to understand how your users navigate, with heat maps and se..."
readingTime: 3
tags: ["nextjs"]
---

For a while I wanted to bring something simple, free and that gives value to your touch. Microsoft Clarity is exactly that: a “put and see” to understand how your users navigate, with heat maps and session recordings. I tested it on several projects and the data it gives you changes UI decisions in minutes. In this post I tell you what it is, how I use it and how to integrate it into Next.js in 2 minutes.

## What is Clarity and why is it worth it?

Clarity is an analytics tool focused on behavior, not vanity metrics. It allows you:

* See real session recordings (Session Replay).
* Analyze where people click, how far they scroll and what they ignore (Heatmaps).
* Detect signals such as rage clicks, dead clicks, excessive scrolling, etc.

It's free, it doesn't ask for a card and for small/medium sized places you're more than enough.

## A quick look at the dashboard

The dashboard is very direct:

* Timeline with recorded sessions, filters by device, country, source, etc.
* Automatic insights panel (rage clicks, U-turns, JS errors).
* Heatmaps per page, with click, scroll and movement views.

Tip: filter by “Rage clicks” and “JS errors” to discover friction that is not seen with Google Analytics.

## Session Replay: where the real pain appears

Watching sessions shows you things that numbers don't tell you:

* Fields where people hesitate or retry.
* Elements that seem clickable but are not.
* Layouts that on mobile are broken by one pixel.

You can adjust speed, jump to interactions and share a link of the recording with your team.

## Integrate it into Next.js in 2 minutes

Clarity is a script that you load asynchronously. The ideal in Next.js is to use next/script. I leave you two variants depending on your setup.

### App Router (app/)

In app/layout.tsx (or in the root layout):

```
// app/layout.tsx
import Script from "next/script";

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body>
        {children}
        {process.env.NEXT_PUBLIC_CLARITY_ID && (
          <Script id="clarity" strategy="afterInteractive">
            {`
              (function(c,l,a,r,i,t,y){
                c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/" + i;
                y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
              })(window, document, "clarity", "script", "${process.env.NEXT_PUBLIC_CLARITY_ID}");
            `}
          </Script>
        )}
      </body>
    </html>
  );
}
```

### Pages Router (pages/)

In pages/\_app.tsx:

```
// pages/_app.tsx
import type { AppProps } from "next/app";
import Script from "next/script";

export default function MyApp({ Component, pageProps }: AppProps) {
  return (
    <>
      <Component {...pageProps} />
      {process.env.NEXT_PUBLIC_CLARITY_ID && (
        <Script id="clarity" strategy="afterInteractive">
          {`
            (function(c,l,a,r,i,t,y){
              c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
              t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/" + i;
              y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
            })(window, document, "clarity", "script", "${process.env.NEXT_PUBLIC_CLARITY_ID}");
          `}
        </Script>
      )}
    </>
  );
}
```

Recommended:

* Save the project ID in NEXT\_PUBLIC\_CLARITY\_ID.
* Load it only in production (you can check process.env.NODE\_ENV === "production").
* If you have private/admin routes, filter them from Clarity with “URL filters”.

## Tips to get the most out of it

* Heatmaps: wait for a little traffic so they are representative. Look at desktop vs mobile separately.
* Custom events: log key actions to better filter recordings:

  ```
  // For example, when someone clicks "Buy"
  window.clarity && window.clarity("event", "cta_buy_click");
  ```
* Identify users (without PII): if you have an internal userId, you can associate it:

  ```
  window.clarity && window.clarity("identify", "user_1234");
  ```

  Be careful: do not send emails or sensitive data.
* Privacy: Clarity masks inputs by default. If you want to hide something extra, add the clarity-mask class. To unmask something specific, clarity-unmask.
* Consent: if you use cookie banners, you can load the script only when there is consent or use Clarity's “consent” mode.

## See the step by step here

## Closing

If you've never looked at a real session of your users, I swear you're going to be surprised. Clarity gives you that gentle slap that helps you prioritize impactful changes. If you integrated it or got stuck with something, leave me a comment and we'll see about it. See you in the next one, and continue rowing it with code and mate.

---

> Original article in Spanish: [Grabá TODO lo que hacen tus usuarios (con Clarity) - mételo en nextjs](https://codigomate.com/graba-todo-lo-que-hacen-tus-usuarios-con-clarity-metelo-en-nextjs/)