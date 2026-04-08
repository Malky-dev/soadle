<!DOCTYPE html>
<html>
<head>
    <title>Résultat</title>
</head>
<body>

<h1>Résultat</h1>

<p>Tu as proposé : <strong><?= htmlspecialchars($guess->name) ?></strong></p>

<ul>
    <li>Gender: <?= $result['gender'] ?></li>
    <li>First appearance: <?= $result['first_appearance_season'] ?></li>
    <li>Death season: <?= $result['death_season'] ?></li>
    <li>Affiliation: <?= $result['affiliation'] ?></li>
</ul>

<hr>

<form method="GET" action="/play">
    <button type="submit">Rejouer</button>
</form>

<form method="GET" action="/">
    <button type="submit">Menu</button>
</form>

</body>
</html>