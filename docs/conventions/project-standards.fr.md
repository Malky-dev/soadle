# Standards du projet — soadle

Version anglaise : [project-standards.en.md](./project-standards.en.md)

---

## 1. Principes fondamentaux

- simplicité avant sophistication (KISS)
- performance dès la conception (chaque choix doit anticiper son coût CPU, mémoire, I/O et réseau)
- lisibilité avant “code malin” (KISS, YAGNI)
- dépendances minimales
- maîtrise complète de l’exécution

La performance est une contrainte de conception, pas une phase d’optimisation tardive.

Chaque composant doit être pensé en termes de :
- nombre de requêtes SQL
- taille des payloads
- coût CPU
- fréquence d’appel
- possibilité de mise en cache

Aucune fonctionnalité ne doit être implémentée sans réflexion sur son impact en production.

- principes de conception orientée objet appliqués avec pragmatisme (SOLID)

Les principes SOLID sont utilisés pour améliorer :
- la lisibilité
- la maintenabilité
- la séparation des responsabilités

Ils ne doivent jamais introduire :
- d’abstraction prématurée
- de complexité inutile
- de structures non justifiées (KISS, YAGNI)

---

## 2. Architecture

### Règles générales

- séparation stricte des responsabilités
- aucune logique métier dans les contrôleurs HTTP
- aucune logique SQL dans les templates
- aucune dépendance circulaire

Un contrôleur HTTP doit uniquement :
- recevoir une requête
- valider les entrées
- déléguer le traitement
- retourner une réponse

### Couches

- **Domain** : logique métier pure
- **Application** : orchestration des cas d’usage
- **Infrastructure** : base de données, fichiers, services externes
- **Http** : gestion des requêtes et réponses

---

## 3. Code PHP

### Règles

- PHP 8.x avec typage strict
- typage explicite obligatoire
- fonctions courtes et ciblées
- pas d’effets de bord cachés
- une fonction = une responsabilité

### Nommage (sémantique)

- noms explicites et orientés métier
- pas d’abréviations ambiguës
- variables : `$characterName` > `$cn`
- fonctions : `findCharacterByName()` > `findChar()`
- classes : `GuessEvaluator` > `Evaluator`

### Commentaires

- privilégier un code auto-explicatif
- commenter uniquement pour expliquer le “pourquoi”, jamais le “quoi”
- privilégier les annotations structurées (PHPDoc)
- pas de commentaires inline sauf cas exceptionnel justifié (KISS, Clean Code)

### Interdits

- variables globales
- fonctions longues sans justification (> 50 lignes)
- duplication non justifiée (DRY)
- logique métier dans les contrôleurs

---

## 4. SQL

### Principes

- requêtes explicites uniquement
- pas de `SELECT *` sauf cas justifié
- index obligatoires sur les colonnes critiques
- requêtes préparées obligatoires (PDO ou équivalent)

### Performance

- chaque requête doit être lisible et optimisable
- éviter les jointures inutiles
- privilégier des lectures rapides
- limiter le nombre de requêtes par endpoint

---

## 5. Frontend

### Règles

- HTML rendu côté serveur prioritaire
- JavaScript uniquement pour les interactions
- pas de framework lourd sans justification

### Performance

- minimiser le JavaScript
- optimiser les images (WebP/AVIF)
- limiter le CSS
- privilégier Tailwind pour la cohérence

---

## 6. Git

### Branches et workflow

- `main` toujours stable (production)
- aucun commit direct sur `main`
- `main` reçoit uniquement des merges validés
- `develop` sert de branche d’intégration (préproduction)

### Nommage

- `feature/...`
- `fix/...`
- `chore/...`
- `docs/...`

### Commits

- utilisation des **Conventional Commits**
- commentaire en anglais

Exemples :
feat: add project standards
docs: add french and english conventions
refactor: isolate guess evaluator
test: add unit test
fix: correct character search query


---

## 7. Pull Requests

Chaque PR doit contenir :

- un objectif clair
- une description des changements
- l’impact technique
- une méthode de test

Les PR doivent être petites, ciblées et lisibles.

---

## 8. Tests

### Types

- Unitaires : logique métier
- Intégration : base de données et services
- E2E : parcours utilisateur

### Règles

- priorité au test du cœur métier
- tests lisibles et maintenables
- pas de tests inutiles
- coverage cible : élevé (≈ 90–95% sur le métier)

---

## 9. Performance

### Backend

- requêtes SQL optimisées
- payload minimal
- aucun calcul inutile à chaque requête

### Frontend

- limiter le JavaScript
- réduire la taille des assets
- éviter les re-renders inutiles

### HTTP

- headers de cache utilisés quand possible
- compression activée

---

## 10. Dépendances

- chaque dépendance doit être justifiée
- éviter les librairies lourdes
- privilégier le code simple

---

## 11. Sécurité

- validation stricte des entrées
- échappement systématique des sorties
- requêtes SQL préparées obligatoires
- ne jamais faire confiance aux données utilisateur

---

## 12. Règles de décision

En cas de doute, toujours préférer :

- statique > dynamique
- simple > complexe
- SQL direct > abstraction
- serveur > client
- lisibilité > optimisation prématurée (sauf cas critique) (KISS, YAGNI)

---

## 13. Discipline

- aucun “quick hack” en production
- aucun code non compris
- aucune fonctionnalité sans réflexion performance minimale
- toute complexité doit être justifiée

---

## Conclusion

Ce projet privilégie :

- la maîtrise
- la performance
- la simplicité

Toute contribution doit respecter strictement ces standards.