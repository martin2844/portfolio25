---
title: "Next.js Internationalization in 10 Minutes"
publishDate: "2025-01-06"
slug: "nextjs-next-intl-learn-internationalization-in-10-minutes"
excerpt: "Stop postponing i18n. Here is a 10-minute walkthrough to set up next-intl in a Next.js app and keep translations organized from the start."
readingTime: 3
tags: ["nextjs"]
---

Have you ever kicked the i18n for “later” and it ended up being left for never? It happened to me a thousand times. That's why I put together this short video where, in 10 minutes, I ready a Next.js app with internationalization using next-intl. It is simple, scalable and you don't have to fight with strange routes or a thousand providers.

---

## The key to the video

### Why next-intl?

* Works perfect with the Next.js App Router.
* Supports server and client components.
* Gives you helpers for dates, numbers and pluralization without you having to reinvent the wheel.
* Middleware ready to redirect to the correct locale.

### Minimal structure I use

* Routes with language prefix: `/[locale]/...`
* A middleware to detect/force locale.
* A `NextIntlClientProvider` in the layout.
* Message archives by language.

### 1) Middleware for local

Define what languages you support and the default. That's it, next-intl takes care of the rest.

```
// middleware.ts
import createMiddleware from 'next-intl/middleware';

export default createMiddleware({
  local: ['es', 'en'],
  defaultLocale: 'en'
});

export const config = {
  // Avoid interfering with internal assets and static files
  matcher: ['/((?!_next|.*\\..*).*)']
};
```

### 2) Provider in the layout of each location

We load the messages and wrap the app so that everything has access to the translations.

```
// app/[locale]/layout.tsx
import {NextIntlClientProvider} from 'next-intl';
import {notFound} from 'next/navigation';

export function generateStaticParams() {
  return [{locale: 'es'}, {locale: 'en'}];
}

export default async function LocaleLayout({
  children,
  params: {locale}
}: {
  children: React.ReactNode;
  params: {locale: 'es' | 'in'};
}) {
  let messages;
  try {
    messages = (await import(`../../messages/${locale}.json`)).default;
  } catch (error) {
    notFound();
  }

  return (
    <html lang={locale}>
      <body>
        <NextIntlClientProvider locale={locale} messages={messages}>
          {children}
        </NextIntlClientProvider>
      </body>
    </html>
  );
}
```

### 3) Messages by language

Define your texts by namespace. Example:

```
// messages/es.json
{
  "Home": {
    "title": "Welcome",
    "cta": "See flights"
  }
}
```

```
// messages/en.json
{
  "Home": {
    "title": "Welcome",
    "cta": "Browse flights"
  }
}
```

### 4) Use translations in Server or Client

* Server (recommended when possible): `getTranslations`.
* Client: `useTranslations`.

```
// app/[locale]/page.tsx (Server Component)
import {getTranslations} from 'next-intl/server';

export default async function Page({params: {locale}}: {params: {locale: 'es' | 'en'}}) {
  const t = await getTranslations({locale, namespace: 'Home'});
  return (
    <>
      <h1>{t('title')}</h1>
      <p>{t('cta')}</p>
    </>
  );
}
```

```
// components/CTA.tsx (Client Component)
'use client';

import {useTranslations} from 'next-intl';

export default function CTA() {
  const t = useTranslations('Home');
  return <button>{t('cta')}</button>;
}
```

### 5) Language changer (without breaking current URL)

Using the next-intl `Link` and the pathname of the App Router.

```
// components/LocaleSwitcher.tsx
'use client';

import {useLocale} from 'next-intl';
import {usePathname} from 'next/navigation';
import Link from 'next-intl/link';

export default function LocaleSwitcher() {
  const locale = useLocale();
  const pathname = usePathname();

  return (
    <nav>
      <Link href={pathname} locale="es" aria-current={locale === 'es' ? 'page' : undefined}>
        EN
      </Link>
      {' · '}
      <Link href={pathname} locale="en" aria-current={locale === 'en' ? 'page' : undefined}>
        IN
      </Link>
    </nav>
  );
}
```

### Bonus: date and number formatting

It is very professional and respects the active premises.

```
// components/Price.tsx
'use client';

import {useFormatter} from 'next-intl';

export default function Price({value}: {value: number}) {
  const f = useFormatter();
  return <span>{f.number(value, {style: 'currency', currency: 'USD'})}</span>;
}
```

---

## Watch the full video

---

## Resources and repo

* Library: <https://next-intl.dev/>
* Example repo (leave a little star!): <https://github.com/martin2844/flights-next-intl-example>
* Deploy your app with Coolify in 5 minutes: [Watch on YouTube (spanish)](<https://youtu.be/DAaXdNrcTV0>)
* Free credits at Hetzner for your VPS: <https://hetzner.cloud/?ref=Sswaf20wbckq>

---

## Closing

If you were avoiding the i18n, with next-intl there are no excuses. It's neat, maintainable, and allows you to scale to multiple languages ​​painlessly. Leave me any questions in the comments, and if it helped you, share it with that friend who always says “I'll do it later.” See you next time, with more code and mate.

---

> Original article in Spanish: [Next.js + next-intl: Aprende Internacionalización en 10 Minutos](https://codigomate.com/next-js-next-intl-aprende-internacionalizacion-en-10-minutos/)