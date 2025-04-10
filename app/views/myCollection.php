<?php include __DIR__ . '/../../includes/header.php'; ?>

<h2>My Pokémon Collection</h2>

<pre style="max-height: 300px; overflow-y: auto; font-size: 0.8rem;">
<?= 'GET[set_id]: ' . ($_GET['set_id'] ?? 'none') . "\n" ?>
<?= 'Total $cards: ' . (isset($cards) ? count($cards) : 'unset') . "\n" ?>
<?= 'Total $sets: ' . (isset($sets) ? count($sets) : 'unset') . "\n" ?>
<?= 'First card name (if any): ' . ($cards[0]['name'] ?? 'none') . "\n" ?>
</pre>

<?php if (!isset($_GET['set_id']) && empty($sets)): ?>
    <p style="text-align: center; margin-top: 2rem;">You haven’t added any cards yet.</p>

<?php elseif (isset($_GET['set_id']) && empty($cards)): ?>
    <p style="text-align: center; margin-top: 2rem;">You don’t have any cards in this set yet.</p>

<?php elseif (isset($_GET['set_id'])): ?>
    <p style="text-align: center;">
        <a href="index.php?action=myCollection">← Back to All Sets</a>
    </p>
    <p style="text-align: center; font-weight: bold;">
        Total Cards in Set: <?= count($cards) ?>
    </p>

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

                <form action="index.php?action=removeCard" method="POST">
                    <input type="hidden" name="card_id" value="<?= htmlspecialchars($id) ?>">
                    <button type="submit" class="remove-btn">Remove from Collection</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

<?php else: ?>
    <div class="card-grid">
        <?php foreach ($sets as $set): ?>
            <div class="card">
                <a href="index.php?action=myCollection&set_id=<?= urlencode($set['id']) ?>" style="text-decoration: none; color: inherit;">
                    <img src="<?= htmlspecialchars($set['logo']) ?>" alt="<?= htmlspecialchars($set['name']) ?>" style="max-height: 120px;">
                    <h4><?= htmlspecialchars($set['name']) ?></h4>
                    <p style="font-size: 0.9rem; color: #333;">
                        <?= $set['collected'] ?> / <?= $set['total'] ?> cards
                    </p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
