# Roadmap

## Now

### 1. Stabilize the foundation
- [x] Define the base project structure
- [x] Add the HTTP entrypoint
- [x] Add the minimal router
- [x] Add the `/health` endpoint
- [x] Add the PDO connection layer
- [x] Add the `.env` loader
- [x] Add the migration runner
- [x] Add the local database reset script
- [x] Add pre-push checks
- [x] Keep the bootstrap simple and explicit

### 2. Align runtime and local workflow
- [x] Ensure the local `.env` matches the migration setup
- [x] Verify the same database target is used by migrations and `/health`
- [x] Confirm the local reset + migrate flow works end-to-end
- [x] Update the README with the current local workflow

### 3. Introduce the first real domain slice
- [x] Add the `Character` domain model
- [x] Define the minimal character fields required by the game
- [x] Add the first `CharacterRepository`
- [x] Add a SQL implementation for character reads
- [x] Add a migration for the `characters` table

### 4. Validate a real application flow
- [x] Add a first use case to fetch one character
- [x] Add a first controller using real database data
- [x] Add a first SSR page rendered from live data
- [x] Validate the full request → application → infrastructure flow

### 5. Prepare the gameplay base
- [ ] Define the minimal game state needed for a first playable loop
- [ ] Add seed data for local development
- [ ] Add the first guess input flow
- [ ] Add the first result comparison rules

## Next

- [ ] Add the core guessing use case
- [ ] Add answer submission handling
- [ ] Add basic error pages
- [ ] Add local seed loading command
- [ ] Keep the HTTP layer thin
- [ ] Keep SQL explicit and focused

## Later

- [ ] Add admin CRUD for game data
- [ ] Add CI checks on `main` and `develop`
- [ ] Add deployment-oriented environment rules
- [ ] Add diagnostics and observability improvements

## Maybe

- [ ] Add daily mode
- [ ] Add player stats
- [ ] Add result sharing
- [ ] Add lightweight caching where useful