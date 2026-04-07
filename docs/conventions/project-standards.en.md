# Project Standards — soadle

French version: [project-standards.fr.md](./project-standards.fr.md)

---

## 1. Core Principles

- simplicity over sophistication (KISS)
- performance by design (each decision must anticipate CPU, memory, I/O and network costs)
- readability over clever code (KISS, YAGNI)
- minimal dependencies
- full control over execution

Performance is treated as a design constraint, not as a late optimization phase.

Each component must be designed with:
- number of SQL queries
- payload size
- CPU cost
- call frequency
- cacheability

No feature should be implemented without considering its impact in production.

- object-oriented design principles applied pragmatically (SOLID)

SOLID principles are used to improve:
- readability
- maintainability
- separation of concerns

They must never introduce:
- premature abstraction
- unnecessary complexity
- unjustified structures (KISS, YAGNI)

---

## 2. Architecture

### General Rules

- strict separation of concerns
- no business logic in HTTP controllers
- no SQL logic in templates
- no circular dependencies

An HTTP controller must only:
- receive a request
- validate inputs
- delegate processing
- return a response

### Layers

- **Domain**: pure business logic
- **Application**: use-case orchestration
- **Infrastructure**: database, files, external services
- **Http**: request and response handling

---

## 3. PHP Code

### Rules

- PHP 8.x with strict typing
- explicit typing required
- small and focused functions
- no hidden side effects
- one function = one responsibility

### Naming (semantic)

- explicit, domain-oriented names
- no ambiguous abbreviations
- variables: `$characterName` > `$cn`
- functions: `findCharacterByName()` > `findChar()`
- classes: `GuessEvaluator` > `Evaluator`

### Comments

- prefer self-explanatory code
- comments must explain “why”, never “what”
- prefer structured annotations (PHPDoc)
- no inline comments except when strictly justified (KISS, Clean Code)

### Forbidden

- global variables
- long functions without justification (> 50 lines)
- unjustified duplication (DRY)
- business logic in controllers

---

## 4. SQL

### Principles

- explicit queries only
- no `SELECT *` unless justified
- mandatory indexes on critical columns
- prepared statements required (PDO or equivalent)

### Performance

- each query must be readable and optimizable
- avoid unnecessary joins
- prioritize fast reads
- limit the number of queries per endpoint

---

## 5. Frontend

### Rules

- server-rendered HTML first
- JavaScript only for interactions
- no heavy framework without justification

### Performance

- minimize JavaScript
- optimize images (WebP/AVIF)
- limit CSS
- prefer Tailwind for consistency

---

## 6. Git

### Branches and workflow

- `main` always stable (production)
- no direct commits on `main`
- `main` only receives validated merges
- `develop` is used as integration branch (pre-production)

### Naming

- `feature/...`
- `fix/...`
- `chore/...`
- `docs/...`

### Commits

- use **Conventional Commits**
- commit messages must be written in English

Examples:
feat: add project standards
docs: add french and english conventions
refactor: isolate guess evaluator
test: add unit test
fix: correct character search query


---

## 7. Pull Requests

Each PR must include:

- a clear objective
- a description of changes
- the technical impact
- a testing method

PRs must be small, focused and readable.

---

## 8. Tests

### Types

- Unit: business logic
- Integration: database and services
- E2E: user flows

### Rules

- prioritize core business logic
- tests must be readable and maintainable
- avoid unnecessary tests
- target coverage: high (≈ 90–95% on business logic)

---

## 9. Performance

### Backend

- optimized SQL queries
- minimal payload
- no unnecessary computation per request

### Frontend

- limit JavaScript
- reduce asset size
- avoid unnecessary re-renders

### HTTP

- use cache headers when possible
- enable compression

---

## 10. Dependencies

- every dependency must be justified
- avoid heavy libraries
- prefer simple code

---

## 11. Security

- strict input validation
- systematic output escaping
- prepared SQL statements required
- never trust user input

---

## 12. Decision Rules

When in doubt, always prefer:

- static over dynamic
- simple over complex
- SQL over abstraction
- server over client
- readability over premature optimization (except critical paths) (KISS, YAGNI)

---

## 13. Discipline

- no quick hacks in production
- no code that is not fully understood
- no feature without minimal performance consideration
- all complexity must be justified

---

## Conclusion

This project prioritizes:

- control
- performance
- simplicity

All contributions must strictly follow these standards.