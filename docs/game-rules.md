# Game rules

## Goal

The player must guess a character from the Sons of Anarchy universe.

## First game mode

The first game mode compares characters using four attributes:

- gender
- first appearance season
- death season
- affiliation

## Attribute rules

### Gender

Allowed values:

- male
- female

### First appearance season

- Integer from 1 to 7
- Represents the first season where the character appears

### Death season

- Integer from 1 to 7
- `null` means the character survives until the end of the series

### Affiliation

Allowed values:

- sons
- mayans
- police
- cartel
- ira
- civilian
- other

## Image rule

- `image_path` is nullable
- When `image_path` is null, the application must render the default character image

## First playable scope

- Server-side rendered only
- Local database only
- No user accounts
- No daily mode
- No statistics
- No admin back-office

## Data quality rule

Character data must be explicit and consistent.

Do not use:
- free-text affiliation variants
- ambiguous gender values
- `null` death season for unknown data

`null` for death season only means the character does not die during the series.