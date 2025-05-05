<!DOCTYPE html>
<html lang="fr">
<?php
require_once __DIR__ . '/../../enums/chemin_page.php';
use App\Enums\CheminPage;
$url = "http://" . $_SERVER["HTTP_HOST"];
$css_ref = '/assets/css/referenciel/all_referenciel.css';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les Référentiels</title>
    <link rel="stylesheet" href="<?= $url . $css_ref ?>">
</head>
<body>
    <div class="ref-container">
        <div class="ref-header">
            <a href="?page=referenciel" class="back-link">
                <i class="fas fa-arrow-left"></i> 
                Retour aux référentiels actifs
            </a>
            <h1>Tous les Référentiels</h1>
            <p>Liste complète des référentiels de formation</p>
        </div>

        <div class="search-actions">
            <div class="search-bar">
                <input type="text" placeholder="Rechercher un référentiel...">
            </div>
            <button class="create-ref-btn">+ Créer un référentiel</button>
        </div>

        <div class="ref-grid">
            <?php foreach ($referentiels as $ref): ?>
            <div class="ref-card">
                <img src="<?= htmlspecialchars($ref['photo']) ?>" alt="<?= htmlspecialchars($ref['nom']) ?>">
                <div class="ref-content">
                    <h3><?= htmlspecialchars($ref['nom']) ?></h3>
                    <p><?= htmlspecialchars($ref['description'] ?? '') ?></p>
                    <div class="ref-info">
                        <span>Capacité: <?= $ref['capacite'] ?> places</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>