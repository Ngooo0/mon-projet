<!DOCTYPE html>
<html lang="fr">
<?php
require_once __DIR__ . '/../../enums/chemin_page.php';
use App\Enums\CheminPage;
$url = "http://" . $_SERVER["HTTP_HOST"];
$css_ref = CheminPage::CSS_REFERENCIEL->value;
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Référentiels</title>
    <link rel="stylesheet" href="<?= $url . $css_ref ?>">
</head>
<body>
    <div class="ref-container">
        <header>
            <h1>Référentiels</h1>
            <p>Gérer les référentiels de la promotion</p>
        </header>

        <div class="search-bar">
            <form method="GET" action="">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Rechercher un référentiel..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                >
            </form>
            <div class="actions">
                <button class="btn btn-orange" onclick="location.href='?page=all_referenciel'">
                    📋 Tous les référentiels
                </button>
                <button class="btn btn-green" onclick="location.href='#popup-add'">+ Ajouter à la promotion</button>
            </div>
        </div>

        <div class="ref-grid">
            <?php foreach ($referentiels as $ref): ?>
                <div class="ref-card">
                    <div class="ref-image">
                        <img src="<?= htmlspecialchars($ref['photo']) ?>" alt="<?= htmlspecialchars($ref['nom']) ?>">
                    </div>
                    <div class="ref-content">
                        <h3><?= htmlspecialchars($ref['nom']) ?></h3>
                        <p class="description">
                            <?= htmlspecialchars($ref['description'] ?? 'Aucune description disponible') ?>
                        </p>
                        <div class="ref-stats">
                            <span><?= $ref['modules'] ?? 0 ?> modules</span>
                            <span><?= $ref['apprenants'] ?? 0 ?> apprenants</span>
                        </div>
                        <div class="ref-capacity">
                            <span>Capacité: <?= $ref['capacite'] ?> places</span>
                        </div>
                        <div class="apprenant-icons">
                            <?php 
                            $totalApprenants = min(($ref['apprenants'] ?? 0), 3);
                            for($i = 0; $i < $totalApprenants; $i++): 
                            ?>
                                <div class="apprenant-icon"></div>
                            <?php endfor; ?>
                            <?php if(($ref['apprenants'] ?? 0) > 3): ?>
                                <div class="remaining-count">+<?= ($ref['apprenants'] - 3) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Popup d'ajout de référentiel -->
    <div id="popup-add" class="modal">
        <div class="modal-content">
            <a href="#" class="close-btn">&times;</a>
            <h2>Nouveau référentiel</h2>
            
            <form method="POST" action="?page=referenciel" enctype="multipart/form-data">
                <input type="hidden" name="action" value="ajouter">
                
                <div class="form-group">
                    <label for="photo">Photo du référentiel*</label>
                    <div class="upload-wrapper">
                        <label for="photo" class="upload-label">
                            <span class="upload-text">Cliquez pour ajouter une image</span>
                            <input 
                                type="file" 
                                id="photo" 
                                name="photo" 
                                accept="image/*"
                                class="file-input"
                            >
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nom">Nom du référentiel*</label>
                    <input 
                        type="text" 
                        id="nom" 
                        name="nom" 

                        placeholder="Ex: Développement Web"
                    >
                </div>

                <div class="form-group">
                    <label for="capacite">Capacité*</label>
                    <input 
                        type="text"
                        id="capacite" 
                        name="capacite" 


                        placeholder="Ex: 30"
                    >
                </div>

                <div class="form-actions">
                    <a href="#" class="cancel-btn">Annuler</a>
                    <button type="submit" class="submit-btn">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    
</body>
</html>