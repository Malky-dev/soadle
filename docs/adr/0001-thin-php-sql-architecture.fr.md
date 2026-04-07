# ADR-001 — Architecture : PHP léger + SQL direct + rendu serveur

Version anglaise : [0001-thin-php-sql-architecture.en.md](./0001-thin-php-sql-architecture.en.md)

---

## Statut

Accepté

---

## Contexte

Le projet soadle est un jeu web quotidien avec les contraintes suivantes :

- faible latence
- montée en charge possible
- coût d’hébergement minimal
- contrôle total de l’exécution
- simplicité de maintenance

Le projet est non lucratif et doit rester léger, robuste et facilement déployable sur un hébergement standard.

---

## Décision

Le projet adopte une architecture basée sur :

- PHP natif (sans framework lourd)
- SQL direct (sans ORM)
- rendu HTML côté serveur (SSR)
- JavaScript minimal côté client

---

## Justification

### 1. Simplicité (KISS)

Réduire la complexité globale :
- moins de dépendances
- moins de couches
- moins de points de défaillance

---

### 2. Performance

- pas de surcharge liée à un framework
- pas de couche ORM coûteuse
- contrôle total des requêtes SQL
- réduction du temps de réponse

---

### 3. Maîtrise

- compréhension complète de la stack
- comportement prévisible
- pas de magie implicite

---

### 4. Déploiement

- compatible avec un hébergement classique
- pas de runtime spécifique requis
- facilité de mise en production

---

## Conséquences

### Avantages

- performances élevées
- faible consommation de ressources
- simplicité d’exploitation
- contrôle total du code

### Inconvénients

- plus de code à écrire
- absence de fonctionnalités “clé en main”
- nécessité de discipline stricte

---

## Alternatives considérées

### Framework PHP (Laravel, Symfony)

Rejeté pour :
- surcharge inutile pour ce type de projet
- complexité accrue
- coût runtime plus élevé

---

### Frontend SPA (React, Vue)

Rejeté pour :
- complexité inutile
- augmentation du JavaScript
- dégradation potentielle des performances

---

### ORM

Rejeté pour :
- perte de contrôle sur les requêtes
- coût en performance
- abstraction inutile pour un modèle simple

---

### Backend Node.js

Rejeté pour :
- complexité de déploiement
- non nécessaire pour ce cas d’usage

---

## Règles dérivées

- privilégier HTML serveur
- limiter JavaScript au strict nécessaire
- écrire des requêtes SQL explicites
- éviter toute abstraction inutile
- optimiser les chemins critiques

---

## Notes

Cette décision est volontairement conservatrice.

Elle privilégie :
- la robustesse
- la performance
- la simplicité

Toute remise en cause de cette architecture doit être justifiée par :
- un besoin réel
- une mesure concrète
- un gain significatif