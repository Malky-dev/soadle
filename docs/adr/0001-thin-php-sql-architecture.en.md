# ADR-001 — Architecture: Thin PHP + Direct SQL + Server-Side Rendering

French version: [0001-thin-php-sql-architecture.fr.md](./0001-thin-php-sql-architecture.fr.md)

---

## Status

Accepted

---

## Context

The soadle project is a daily web-based game with the following constraints:

- low latency
- potential traffic growth
- minimal hosting cost
- full control over execution
- simplicity and maintainability

The project is non-commercial and must remain lightweight, robust, and easily deployable on standard hosting environments.

---

## Decision

The project adopts an architecture based on:

- native PHP (no heavy framework)
- direct SQL (no ORM)
- server-side HTML rendering (SSR)
- minimal client-side JavaScript

---

## Rationale

### 1. Simplicity (KISS)

Reduce overall complexity:
- fewer dependencies
- fewer layers
- fewer points of failure

---

### 2. Performance

- no framework overhead
- no ORM cost
- full control over SQL queries
- reduced response time

---

### 3. Control

- full understanding of the stack
- predictable behavior
- no hidden abstractions

---

### 4. Deployment

- compatible with standard hosting
- no specific runtime required
- easy to deploy and maintain

---

## Consequences

### Benefits

- high performance
- low resource usage
- operational simplicity
- full code control

### Trade-offs

- more code to write manually
- no built-in features
- requires strict discipline

---

## Considered Alternatives

### PHP Frameworks (Laravel, Symfony)

Rejected due to:
- unnecessary overhead for this project
- increased complexity
- higher runtime cost

---

### Frontend SPA (React, Vue)

Rejected due to:
- unnecessary complexity
- increased JavaScript footprint
- potential performance degradation

---

### ORM

Rejected due to:
- loss of control over queries
- performance overhead
- unnecessary abstraction for a simple data model

---

### Node.js backend

Rejected due to:
- deployment complexity
- not required for this use case

---

## Derived Rules

- prioritize server-rendered HTML
- limit JavaScript to essential interactions
- write explicit SQL queries
- avoid unnecessary abstractions
- optimize critical paths

---

## Notes

This decision is intentionally conservative.

It prioritizes:
- robustness
- performance
- simplicity

Any change to this architecture must be justified by:
- a real need
- measurable evidence
- a significant benefit