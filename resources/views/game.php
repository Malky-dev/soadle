<?php

declare(strict_types=1);

$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Soadle - Partie</title>

    <link rel="stylesheet" href="/assets/css/game.css">
</head>
<body>
    <h1>Devine le personnage</h1>

    <?php if ($isWin): ?>
        <p><strong>Bravo, tu as trouvé le personnage mystère.</strong></p>

        <p>
            <a href="/play">Nouvelle partie</a>
        </p>

        <p>
            <a href="/">Retour au menu</a>
        </p>
    <?php else: ?>

        <?php if ($error !== null): ?>
            <p role="alert">
                <strong><?= $escape($error) ?></strong>
            </p>
        <?php endif; ?>

        <?php if ($availableCharacters !== []): ?>
            <form method="POST" action="/guess">
                <!-- Game state preserved between guesses. -->
                <input
                    type="hidden"
                    name="target_id"
                    value="<?= $targetId ?>"
                >

                <input
                    type="hidden"
                    name="attempted_ids"
                    value="<?= $escape($attemptedIdsValue) ?>"
                >

                <label for="character_search">
                    Rechercher un personnage
                </label>

                <input
                    type="text"
                    id="character_search"
                    autocomplete="off"
                    placeholder="Tape un nom..."
                    aria-describedby="character_search_help"
                >

                <!-- Submitted value expected by the server. -->
                <input
                    type="hidden"
                    id="guess_id"
                    name="guess_id"
                    required
                >

                <p id="character_search_help">
                    Tape au moins une lettre puis sélectionne un personnage.
                </p>

                <!-- Pre-rendered search results filtered client-side. -->
                <div
                    id="character_search_results"
                    class="search-results"
                >
                    <?php foreach ($availableCharacters as $character): ?>
                        <div
                            class="search-result"
                            data-character-id="<?= $character['id'] ?>"
                            data-character-name="<?= $escape($character['normalizedName']) ?>"
                            hidden
                        >
                            <?= $escape($character['name']) ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p
                    id="character_search_empty"
                    hidden
                >
                    Aucun personnage ne correspond à cette recherche.
                </p>

                <!-- Form submission is triggered through JavaScript. -->
                <button
                    type="submit"
                    hidden
                >
                    Guess
                </button>
            </form>
        <?php else: ?>
            <p>Plus aucun personnage disponible.</p>

            <p>
                <a href="/play">Nouvelle partie</a>
            </p>
        <?php endif; ?>

    <?php endif; ?>

    <?php if ($attempts !== []): ?>
        <h2>Essais précédents</h2>

        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Personnage</th>
                    <th>Gender</th>
                    <th>First appearance</th>
                    <th>Death season</th>
                    <th>Affiliation</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($attempts as $attempt): ?>
                    <?php
                    // Comparison values are computed by the application layer.
                    $comparison = $attempt['comparison'];
                    ?>

                    <tr>
                        <td>
                            <?= $escape($attempt['character']->name) ?>
                        </td>

                        <td>
                            <?= $escape($comparison['gender']) ?>
                        </td>

                        <td>
                            <?= $escape($comparison['first_appearance_season']) ?>
                        </td>

                        <td>
                            <?= $escape($comparison['death_season']) ?>
                        </td>

                        <td>
                            <?= $escape($comparison['affiliation']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p>
        <a href="/">Retour au menu</a>
    </p>

    <script src="/assets/js/game.js" defer></script>
</body>
</html>