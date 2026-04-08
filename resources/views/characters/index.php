<?php

declare(strict_types=1);

/** @var array<\App\Domain\Character> $characters */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Characters</title>
    <style>
        body {
            margin: 0;
            padding: 32px;
            font-family: Arial, sans-serif;
            background: #111;
            color: #f5f5f5;
        }

        h1 {
            margin-bottom: 24px;
        }

        .characters {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 24px;
        }

        .card {
            background: #1b1b1b;
            border: 1px solid #2c2c2c;
            border-radius: 12px;
            overflow: hidden;
        }

        .card img {
            display: block;
            width: 100%;
            height: 320px;
            object-fit: cover;
            background: #000;
        }

        .card-content {
            padding: 16px;
        }

        .card h2 {
            margin: 0 0 12px;
            font-size: 20px;
        }

        .card ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .card li {
            margin-bottom: 8px;
            color: #d0d0d0;
        }
    </style>
</head>
<body>
    <h1>Characters</h1>

    <div class="characters">
        <?php foreach ($characters as $character): ?>
            <article class="card">
                <img
                    src="/assets/<?= htmlspecialchars($character->resolvedImagePath(), ENT_QUOTES, 'UTF-8') ?>"
                    alt="<?= htmlspecialchars($character->name, ENT_QUOTES, 'UTF-8') ?>"
                >

                <div class="card-content">
                    <h2><?= htmlspecialchars($character->name, ENT_QUOTES, 'UTF-8') ?></h2>

                    <ul>
                        <li>Gender: <?= htmlspecialchars($character->gender, ENT_QUOTES, 'UTF-8') ?></li>
                        <li>First appearance: season <?= $character->firstAppearanceSeason ?></li>
                        <li>
                            Death:
                            <?= $character->deathSeason === null ? 'alive' : 'season ' . $character->deathSeason ?>
                        </li>
                        <li>Affiliation: <?= htmlspecialchars($character->affiliation, ENT_QUOTES, 'UTF-8') ?></li>
                    </ul>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</body>
</html>