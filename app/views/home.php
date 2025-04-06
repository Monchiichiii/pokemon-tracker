<?php include __DIR__ . '/../../includes/header.php'; ?>

<section class="home-container">
    <div class="welcome-box">
        <h2>✨ Welcome to <span class="title-highlight">Pokémon Card Tracker</span> ✨</h2>
        <p class="subtext">Gotta track ’em all! Build your dream card collection — one click at a time.</p>

        <?php if (isset($_SESSION['user'])): ?>
            <p class="welcome-user"><span class="waving">👋</span> Welcome back, <?= htmlspecialchars($_SESSION['user']['name']) ?>!</p>
            <a class="login-btn" href="index.php?action=browse">Browse Cards</a>
        <?php else: ?>
            <a class="login-btn" href="<?= $login_url ?>">Login with Google</a>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

<style>
/* === Animated Emoji Wave === */
.waving {
    display: inline-block;
    animation: wave 1.5s infinite;
    transform-origin: 70% 70%;
}

@keyframes wave {
    0% { transform: rotate(0deg); }
    20% { transform: rotate(10deg); }
    40% { transform: rotate(-8deg); }
    60% { transform: rotate(6deg); }
    80% { transform: rotate(-4deg); }
    100% { transform: rotate(0deg); }
}

/* === Cute Home Styles === */
.home-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 4rem 2rem;
    background: #fdfcfe;
    min-height: 70vh;
}

.welcome-box {
    background: linear-gradient(145deg, #fff0f5, #e3f2fd);
    border-radius: 20px;
    padding: 3rem;
    max-width: 550px;
    width: 100%;
    text-align: center;
    box-shadow: 0 8px 20px rgba(255, 128, 171, 0.2);
    transition: transform 0.3s ease;
}

.welcome-box:hover {
    transform: translateY(-5px);
}

.welcome-box h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #ff4081;
}

.title-highlight {
    color: #ff69b4;
}

.subtext {
    font-size: 1rem;
    color: #555;
    margin-bottom: 1.5rem;
}

.welcome-user {
    font-weight: bold;
    font-size: 1rem;
    color: #2e7d32;
    margin-bottom: 1rem;
}

.login-btn {
    display: inline-block;
    background: linear-gradient(45deg, #ff4081, #ff80ab);
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    font-size: 1rem;
    transition: background 0.3s ease, transform 0.2s ease;
    box-shadow: 0 4px 10px rgba(255, 105, 135, 0.2);
}

.login-btn:hover {
    background: linear-gradient(45deg, #f06292, #f48fb1);
    transform: scale(1.05);
}
</style>
