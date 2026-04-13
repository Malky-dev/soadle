# Roadmap

## Now

### 1. Ship a playable core loop
- [x] Define the minimal game state needed for a first playable loop
- [x] Add the first guess input flow
- [x] Add the first result comparison rules
- [x] Keep the gameplay loop server-rendered and minimal
- [x] Extract gameplay state handling into a dedicated application use case
- [x] Move gameplay orchestration out of GameController
- [ ] Harden answer submission and invalid input handling
- [ ] Add a clear win state
- [ ] Add a clear lose state
- [ ] Improve gameplay feedback readability

---

### 2. Build a consistent character dataset
- [ ] Define a minimum viable dataset size (e.g. 20-50 characters)
- [ ] Ensure all characters have complete required fields
- [ ] Validate data consistency across all characters
- [ ] Normalize affiliation, gender and season values
- [ ] Remove incomplete or low-quality entries
- [ ] Add validation rules for future data additions

---

### 3. Prepare production-ready assets
- [ ] Ensure every character has a valid image
- [ ] Add a reliable fallback image strategy
- [ ] Normalize image naming and paths
- [ ] Ensure consistent image formats and sizes
- [ ] Remove broken or missing assets
- [ ] Optimize images for web performance

---

### 4. Improve gameplay performance
- [ ] Reduce repeated character reads in the gameplay flow
- [ ] Reduce the number of database queries per gameplay request
- [ ] Optimize character retrieval during gameplay
- [ ] Cache static character data when useful

---

### 5. Stabilize the runtime for a v1
- [x] Add the `/health` endpoint
- [x] Ensure runtime bootstrap and local scripts use the same connection entrypoint
- [x] Verify the same database target is used by migrations and `/health`
- [x] Confirm the local reset + migrate flow works end-to-end
- [ ] Add basic error pages
- [ ] Improve runtime error feedback for local debugging
- [ ] Remove duplicate or misleading local database artifacts

---

### 6. Keep the engineering baseline healthy
- [x] Add automated tests for the core gameplay application flow
- [x] Extend automated tests across domain and application layers
- [x] Add GitHub Actions checks for Tests and Health
- [x] Add testing documentation
- [ ] Add regression tests for every gameplay bug fix
- [ ] Keep README and docs aligned with the actual product state

---

## Next

### Gameplay depth
- [ ] Improve comparison clarity and UX feedback
- [ ] Add better character selection experience
- [ ] Improve replay flow
- [ ] Improve edge-case handling and empty states

### Content expansion
- [ ] Expand the character dataset beyond the initial pool
- [ ] Introduce richer character attributes if useful
- [ ] Improve data balancing for gameplay fairness

### Maintainability
- [ ] Add a local seed loading command
- [ ] Remove dead configuration or unused infrastructure code
- [ ] Add targeted integration tests for SQL repositories
- [ ] Add targeted integration tests for critical HTTP flows

---

## Later

### Product expansion
- [ ] Add admin CRUD for game data
- [ ] Add daily mode
- [ ] Add player stats
- [ ] Add result sharing

### Technical evolution
- [ ] Add deployment-oriented environment rules
- [ ] Add diagnostics and observability improvements
- [ ] Add optional coverage reporting
- [ ] Consider PHPUnit only if justified by test suite growth

---

## Maybe

- [ ] Add lightweight caching where useful
- [ ] Add theme or presentation variants
- [ ] Add richer game modes