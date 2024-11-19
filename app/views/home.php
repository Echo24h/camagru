<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <p> Bonjour <?= htmlspecialchars($_SESSION['username']) ?>!</p>
    <p> SESSION ID: <?= session_id() ?></p>
    <p> SESSION NAME: <?= session_name() ?></p>
    <p> SESSION COOKIE PARAMS: <?= print_r(session_get_cookie_params()) ?></p>
    <p> SESSION <?= print_r($_SESSION) ?></p>
    <h1>Liste des utilisateurs</h1>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><?php echo htmlspecialchars($user['username']); ?> (<?php echo htmlspecialchars($user['email']); ?>)</li>
        <?php endforeach; ?>
    </ul>
    <p><a href="/logout">Se déconnecter</a></p>
</body>
</html>