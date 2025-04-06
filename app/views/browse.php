<?php include __DIR__ . '/../../includes/header.php'; ?>

<h2>Browse Pokémon Cards</h2>
<form method="GET" action="index.php">
    <input type="hidden" name="action" value="browse">
    <input type="text" name="q" placeholder="Search by name..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">

    <select name="orderBy">
        <option value="name" <?= ($_GET['orderBy'] ?? '') === 'name' ? 'selected' : '' ?>>Name A-Z</option>
        <option value="-name" <?= ($_GET['orderBy'] ?? '') === '-name' ? 'selected' : '' ?>>Name Z-A</option>
    </select>

    <select name="set">
        <option value="">All Sets</option>
        <?php foreach ($sets as $s): ?>
            <option value="<?= htmlspecialchars($s['id']) ?>" <?= ($_GET['set'] ?? '') === $s['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['series']) ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Search</button>
</form>

<?php $userCollection = $_SESSION['user_collection'] ?? []; ?>

<div class="card-grid">
    <?php foreach ($cards as $card): ?>
        <?php
            $id = $card['id'] ?? '';
            $name = $card['name'] ?? 'Unknown';
            $image = $card['images']['large'] ?? $card['images']['small'] ?? '';
            $hp = $card['hp'] ?? '?';
            $types = implode(', ', $card['types'] ?? []);
            $setName = $card['set']['name'] ?? 'Unknown';
            $price = $card['tcgplayer']['prices']['normal']['market'] ?? 'N/A';
        ?>
        <div class="card">
            <?php if ($image): ?>
                <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($name) ?>" class="enlargeable" data-full="<?= htmlspecialchars($image) ?>">
            <?php endif; ?>
            <h4><?= htmlspecialchars($name) ?></h4>
            <p><strong>HP:</strong> <?= htmlspecialchars($hp) ?></p>
            <p><strong>Type:</strong> <?= htmlspecialchars($types) ?></p>
            <p><strong>Set:</strong> <?= htmlspecialchars($setName) ?></p>
            <p><strong>Market Price:</strong> $<?= is_numeric($price) ? number_format($price, 2) : $price ?></p>

            <?php if (in_array($id, $userCollection)): ?>
                <p class="already-saved">✔ Already in Collection</p>
                <form action="index.php?action=removeCard" method="POST">
                    <input type="hidden" name="card_id" value="<?= htmlspecialchars($id) ?>">
                    <button type="submit" class="remove-btn">Remove from Collection</button>
                </form>
            <?php else: ?>
                <form action="index.php?action=addCard" method="POST">
                    <input type="hidden" name="card_id" value="<?= htmlspecialchars($id) ?>">
                    <button type="submit">Add to Collection</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$prevPage = max($currentPage - 1, 1);
$nextPage = $currentPage + 1;
$query = $_GET['q'] ?? '';
$orderBy = $_GET['orderBy'] ?? 'name';
$set = $_GET['set'] ?? '';
?>

<div style="text-align: center; margin-top: 2rem;">
    <?php if ($currentPage > 1): ?>
        <a href="index.php?action=browse&q=<?= urlencode($query) ?>&orderBy=<?= urlencode($orderBy) ?>&set=<?= urlencode($set) ?>&page=<?= $prevPage ?>" class="page-btn">← Previous Page</a>
    <?php endif; ?>

    <a href="index.php?action=browse&q=<?= urlencode($query) ?>&orderBy=<?= urlencode($orderBy) ?>&set=<?= urlencode($set) ?>&page=<?= $nextPage ?>" class="page-btn">Next Page →</a>
</div>

<style>

</style>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
