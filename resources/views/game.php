<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Soadle - Partie</title>
</head>
<body>
    <h1>Devine le personnage</h1>

    <?php if ($isWin): ?>
        <p><strong>Bravo, tu as trouvé le personnage mystère.</strong></p>
        <p><a href="/play">Nouvelle partie</a></p>
        <p><a href="/">Retour au menu</a></p>
    <?php else: ?>
        <?php if ($error !== null): ?>
            <p role="alert">
                <strong><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></strong>
            </p>
        <?php endif; ?>

        <?php if ($availableCharacters !== []): ?>
            <form method="POST" action="/guess">
                <input type="hidden" name="target_id" value="<?= $targetId ?>">
                <input type="hidden" name="attempted_ids" value="<?= htmlspecialchars($attemptedIdsValue, ENT_QUOTES, 'UTF-8') ?>">

                <label for="character_search">Rechercher un personnage</label>
                <input
                    type="text"
                    id="character_search"
                    autocomplete="off"
                    placeholder="Tape un nom..."
                    aria-describedby="character_search_help"
                >

                <input type="hidden" id="guess_id" name="guess_id" required>

                <p id="character_search_help">
                    Tape au moins une lettre puis sélectionne un personnage.
                </p>

                <div id="character_search_results" class="search-results">
                    <?php foreach ($availableCharacters as $character): ?>
                        <div
                            class="search-result"
                            data-character-id="<?= $character->id ?>"
                            data-character-name="<?= htmlspecialchars(mb_strtolower($character->name, 'UTF-8'), ENT_QUOTES, 'UTF-8') ?>"
                            hidden
                        >
                            <?= htmlspecialchars($character->name, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p id="character_search_empty" hidden>
                    Aucun personnage ne correspond à cette recherche.
                </p>

                <button type="submit" hidden>Guess</button>
            </form>
        <?php else: ?>
            <p>Plus aucun personnage disponible.</p>
            <p><a href="/play">Nouvelle partie</a></p>
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
                    // Each attempt carries the comparison payload computed by GameController.
                    $comparison = $attempt['comparison'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($attempt['character']->name, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($comparison['gender'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($comparison['first_appearance_season'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($comparison['death_season'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($comparison['affiliation'], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="/">Retour au menu</a></p>
    <link rel="stylesheet" href="/assets/css/game.css">
    <script src="/assets/js/game.js" defer></script>
</body>
</html>