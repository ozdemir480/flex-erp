<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
<h1><?= htmlspecialchars($message) ?></h1>
<?= csrf_input() ?>
</body>
</html>
