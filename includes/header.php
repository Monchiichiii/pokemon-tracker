<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pokémon Tracker</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="assets/images/pokeball.png">
</head>
<body>
    <header>
        <div class="navbar">

            <!-- Logo -->
            <div class="nav-logo-wrapper">
                <a href="index.php?action=home" class="nav-logo">
                    <img src="assets/images/pika.png" alt="Pokémon Tracker Logo" class="logo-img">
                    <span class="logo-text">Pokémon Tracker</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="nav-center">
                <a class="pokeball-btn" href="index.php?action=home">Home</a>
                <a class="pokeball-btn" href="index.php?action=browse">Browse</a>
                <a class="pokeball-btn" href="index.php?action=myCollection">Collection</a>
            </nav>

            <!-- User Info & Logout -->
            <div class="nav-right">
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="user-info">
                        <span>⚡ Welcome, <strong><?= htmlspecialchars($_SESSION['user']['name']) ?></strong></span>
                        <a href="logout.php" class="logout-btn">Logout</a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </header>
