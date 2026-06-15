---
title: "Sqlite vs Postgres - Which is best for you? -"
publishDate: "2024-10-09"
slug: "sqlite-vs-postgres-which-is-best-for-you"
excerpt: "Every time I show a new project the same question appears: “Do I use SQLite or Postgres?” And yes… the easy answer is “it depends”, but I wanted to go further and measure it in real scenarios. In this..."
readingTime: 4
tags: ["sqlite", "postgres", "sql"]
---

Every time I show a new project the same question appears: “Do I use SQLite or Postgres?” And yes… the easy answer is “it depends”, but I wanted to go further and measure it in real scenarios. In this video I tested both with writes, reads, concurrency, and some heavier queries to see where each shines and where it falls short.

## What I measured and how

* Environment: a humble VPS (1 vCPU/1GB RAM), typical for cheap self-hosting.
* SQLite 3 with WAL enabled. Postgres 16 with almost default config.
* Tests: sequential inserts (100k rows), concurrent reads, 10 parallel writers and some queries with joins and indexes.

## Results that matter

### Sequential writes (single connection)

* SQLite flies. With WAL enabled and a single connection, the single file makes everything very straightforward.
* Postgres is a little slower due to server overhead and durability, but stable.

When it suits you: If your app writes one thing at a time (e.g. desktop app, CLI, offline jobs), SQLite works great for you.

### Write concurrency (multiple writers)

* Postgres wins comfortable. With 10 writers in parallel, it maintains good throughput and reasonable latencies.
* SQLite handles many reads well, but with many simultaneous writes it starts to get stuck due to locks.

When it suits you: if you have concurrent users, APIs with real traffic or multiple processes writing, go with Postgres.

### Concurrent reads

* With WAL, SQLite reads in parallel without drama. For “read-heavy” it performs more than it seems.
* Postgres remains consistent and responsive, even under mixed load.

When it suits you: Both work well, but if you also need to write a lot at the same time, Postgres is still stronger.

### Complex queries and planner

* Postgres breaks it with large joins, CTEs, more mature scheduler, types, extensions, etc.
* SQLite compliant, but designed for simplicity. In heavy queries the difference is noticeable.

When it suits you: if you are going to do reporting, analytics, complex filters, advanced searches... Postgres.

### Operation, backups and mental cost

* SQLite is a file. Zero admin, backup with copy the file or `VACUUM INTO`, and that's it. Ideal for quick and cheap deployment.
* Postgres requires service, users, roles, backups with `pg_dump`/`pg_basebackup`, autovacuum, pooling (pgbouncer) if there are many connections, etc. More work, more power.

### Game-changing features

* Postgres: extensions (PostGIS, pgvector), Row Level Security, advanced types, partial indexes, replication, partitioning, powerful triggers.
* SQLite: FTS5 (full-text search) and JSON1 go a long way, but not at the same level as Postgres. For edge/embedded, it's a great goal.

## I chose wisely (without marrying anyone)

I chose SQLite if:

* Your app is single-user or with few concurrent writes.
* You want zero-admin and portability (one file and bye).
* You're on edge, CLI, desktop, prototyping, or lightweight microservices.
* You are looking for very low costs and extreme simplicity.

I chose Postgres if:

* You have real traffic with multiple clients writing at the same time.
* You are going to make complex queries, reports or analytics.
* You need replication, high availability, row security, extensions.
* Your SaaS or API is going to grow and you want a robust foundation from day one.

## Quick practical tips

For SQLite:

* Enable WAL: `PRAGMA journal_mode=WAL;`
* If you need performance and can tolerate a little less durability: `PRAGMA synchronous=NORMAL;`
* Backups: `VACUUM INTO` or `.backup` and that's it.

For Postgres:

* Use pgbouncer if you have many short connections.
* Tune with pgtune as a starting point.
* Leave autovacuum well configured.
* Serious backups: `pg_basebackup`/WAL archiving.

## Watch the video

[Watch on YouTube (spanish)](https://www.youtube.com/watch?v=T2_9Gnzs_jg)

Closing

My conclusion: in most multi-user web projects, Postgres is the safe bet. But don't underestimate SQLite: for many everyday things it is more than enough and saves you money, time and complexity. It is not a religion; is choosing the right tool.

If you want to learn how to deploy your bases or apps without going crazy or spending too much, I leave you this video where I show you how to do it step by step: [Watch on YouTube (spanish)](<https://www.youtube.com/watch?v=DAaXdNrcTV0>)

Tell me in the comments what you are using and why. We read each other!

---

> Original article in Spanish: [Sqlite vs Postgres - Que te conviene? -](https://codigomate.com/sqlite-vs-postgres-que-te-conviene-sqlite-postgres-sql/)