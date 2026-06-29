---
title: "I Built My Own Lead Generation Tool"
publishDate: "2025-12-23"
slug: "i-got-tired-of-paying-for-lead-tools-and-i-created-my-own"
excerpt: "Lead tools keep raising prices and hiding features behind Pro plans. I got tired of it and built my own lead scraper to stay in control."
readingTime: 4
tags: ["buildinpublic", "automation", "n8n", "indiehacker", "saas"]
---

Desde hace meses vengo renegando con las herramientas de lead generation. Te prometen oro, pero entre planes “Pro”, límites raros y features bloqueadas, terminás pagando cada mes sin tener control real del proceso. Así que me puse cabezón: arranqué con un par de automatizaciones en n8n… y sin querer queriendo terminé armando una app completa para encontrar, enriquecer y verificar leads. En este post te cuento el recorrido, qué aprendí y cuándo (para mí) conviene pagar vs. construir.

## Por qué dejé de pagar

* Costos que se acumulan: si necesitás scraping liviano + enriquecimiento + verificación + algo de outreach, fácil te vas a USD 150–400/mes sumando varias tools.
* Bloqueos y límites: rate limits bajos, listas capadas o features clave detrás de planes más caros.
* Poca flexibilidad: querés meter una regla tonta o un campo custom y ya es un quilombo.
* Vendor lock-in: tus procesos dependen de terceros; si cambian una API, se te cae el castillo.

No es hate: muchas tools son buenísimas. Pero para mi caso, necesitaba control fino y costos razonables. Y me picó el bicho de construir.

## Cómo lo armé (y qué terminó saliendo)

Arranqué con un flujo simple en n8n para validar si podía replicar lo básico. Cuando vi que funcionaba, escalé a una app chiquita con UI y backend. El flujo quedó más o menos así:

### 1) Ingesta y fuentes

* Carga de CSV o búsqueda por queries (boolean, filtros por industria/país).
* Fuentes mixtas: sitios públicos, directorios y páginas de empresa.
* Respeto robots.txt y límites; nada agresivo.

### 2) Scraping y parsing

* Playwright para navegación headless (con rotación de user-agents y delays humanos).
* Cheerio para parsear HTML y extraer datos clave (dominio, redes, contacto, tamaño, tech stack básico si está a mano).
* Rate limiting fuerte para que no explote nada.

### 3) Enriquecimiento

* Deducción de patrones de email por dominio (firstname.lastname, flastname, etc.).
* Normalización de nombres, roles e industria.
* Geolocalización y categorización liviana (tags por palabras clave).

### 4) Verificación de emails

* Verificación SMTP no intrusiva, checks MX y parámetros técnicos.
* Scoring simple para calidad (deliverability + signals del dominio).

### 5) Deduplicación y calidad

* Postgres con índices únicos por email y dominio.
* Fuzzy matching con trigramas para evitar duplicados “parecidos”.
* Reglas de prioridad (si hay varios, me quedo con el más completo).

### 6) Salida y acciones

* Export CSV filtrado por calidad.
* Webhooks para meter leads validados directo a CRM o a nuevas secuencias.
* Tareas manuales en la UI cuando el sistema duda (human-in-the-loop).

## Stack técnico (minimalista y barato)

* Orquestación: n8n (self-host) para los pipelines y reintentos.
* Workers: Node/TypeScript con Playwright y Cheerio.
* DB: Postgres (con pg\_trgm) + Redis para colas.
* API: Node/Express con algunos jobs en cron.
* UI: una mini app en Next.js para ver estados, revisar leads y exportar.
* Infra: VPS baratito con Docker Compose, Traefik para reverse proxy y TLS.

Costos mensuales estimados:

* VPS: USD 6–12
* Proxies residenciales livianos: USD 10–20 (según volumen)
* Envío de emails verificados (tipo SES): centavos por 1k  
  Total: ~USD 20–35/mes, vs. varias suscripciones separadas.

## Lo que más me sorprendió

* La limpieza de datos es el 80% del valor. Un buen dedupe + normalización vale oro.
* Captchas y rate limits son la realidad. Mejor ir lento y consistente que rápido y bloqueado.
* El logging es tu mejor amigo. Cuando algo falla, querés saber el porqué y reintentar sin romper todo.
* La UI, por mínima que sea, ahorra horas. Un lugar para revisar “casos dudosos” cambia el juego.
* Hacerlo legal y prolijo no es opcional: respetar términos, robots.txt, privacidad, opt-out fácil y buen gusto en el outreach.

## ¿Pagar o construir? Mi regla simple

Pagá el SaaS si:

* Tu volumen es bajo/variable y no querés mantenimiento.
* Necesitás resultados ya mismo y tu tiempo vale más que el setup.
* La herramienta “de caja” ya resuelve el 90% de tu proceso.

Construí si:

* Necesitás control y workflows raros.
* Te duele el costo mensual y vas a usarlo todos los días.
* Te divierte (posta) mantenerlo y mejorarlo.

## Roadmap que me quedé con ganas de meter

* Scoring con features más “ML light” (sin caer en humo).
* Integraciones directas con CRMs populares y webhooks más granulares.
* Sistema de créditos por workspace y multi-tenant.
* Plantillas de secuencias con variables dinámicas y A/B testing.

## Mirá el video

Acá muestro el paso a paso, con números, tropiezos y la app funcionando.

## Cierre

No creo que haya una respuesta única. A veces lo más inteligente es sacar la tarjeta, y otras, construir te da una ventaja real. En mi caso, necesitaba aprender, ahorrar y tener control. ¿Lo próximo? Hacerlo más robusto y, si pinta, abrir una versión para que otros la usen.

Si te interesaría que libere el flujo de n8n o cuente más del setup en la VPS, decime en los comentarios. Gracias por bancar y por estar del otro lado. Vamos a seguir remándola con código y café.

---

> Original article in Spanish: [Me cansé de pagar tools de leads y armé la mía](https://codigomate.com/me-canse-de-pagar-tools-de-leads-y-arme-la-mia-buildinpublic-automation-n8n-indiehacker-saas/)