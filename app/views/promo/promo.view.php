<!DOCTYPE html>
<html lang="fr">
<?php
require_once __DIR__ . '/../../enums/chemin_page.php';
use App\Enums\CheminPage;
$url = "http://" . $_SERVER["HTTP_HOST"];
$css_promo = CheminPage::CSS_PROMO->value;


?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Promotions</title>
    <link rel="stylesheet" href="<?= $url . $css_promo ?>" />
</head>
<body>
<div class="promo-container">
    <header class="header">
        <h2>Promotion</h2>
        <p>Gérer les promotions de l'école</p>
    </header>

    <div class="stats">
        <div class="stat orange">
            <div class="stat-content">
                <strong class="stat-value">0</strong>
                <span class="stat-label">Apprenants</span>
            </div>
            <div class="icon"><img src="/assets/images/icone1.png" alt=""></div>
        </div>
        <div class="stat orange">
            <div class="stat-content">
                <strong class="stat-value">5</strong>
                <span class="stat-label">Référentiels</span>
            </div>
            <div class="icon"><img src="/assets/images/ICONE2.png" alt=""></div>
        </div>
        <div class="stat orange">
            <div class="stat-content">
                <strong class="stat-value">1</strong>
                <span class="stat-label">Promotions actives</span>
            </div>
            <div class="icon"><img src="/assets/images/ICONE3.png" alt=""></div>
        </div>
        <div class="stat orange">
            <div class="stat-content">
                <strong class="stat-value">3</strong>
                <span class="stat-label">Total promotions</span>
            </div>
            <div class="icon"><img src="/assets/images/ICONE4.png" alt=""></div>
        </div>
        <a href="index.php?page=ajoutpromo" class="add-btn">+ Ajouter une promotion</a>
    </div>

    <div class="search-filter">
        
        <form method="GET" action="" style="display: flex; flex: 1;">
            <input type="hidden" name="page" value="liste_promo" />
            <input type="text" name="search" placeholder="Rechercher une promotion..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" />

            <select name="filtre">
                <option value="tous">Tous</option>
                <option value="active" <?= ($_GET['filtre'] ?? '') === 'active' ? 'selected' : '' ?>>Actives</option>
                <option value="inactive" <?= ($_GET['filtre'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactives</option>
            </select>
            <button type="submit" class="submit-btn">Rechercher</button>
        </form>
        

        <div class="view-toggle">
            <form method="GET" action="">
            <button class="active">Grille</button>
            <input type="hidden" name="page" value="liste_table_promo" />
            <button type="submit">Liste</button>
            </form>
        </div>

    </div>

    <!-- Liste des promotions -->
    <div class="card-grid">
        <?php foreach ($promotions as $promo): ?>
            <div class="promo-card">
                <!--
                <div class="toggle-container">
                    //Formulaire pour activer une promotion 
                    <form method="GET" action="index.php">
                        <input type="hidden" name="page" value="activer_promo">
                        <input type="hidden" name="activer_promo" value="<?= $promo['id'] ?>">
                        <button type="submit" class="toggle-label <?= $promo["statut"] === "Active" ? "active" : "" ?>">
                         <div class="status-pill"></div>
                            <div class="power-button">
                                <svg class="power-icon" viewBox="0 0 24 24">
                                    <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path>
                                    <line x1="12" y1="2" x2="12" y2="12"></line>
                                </svg>
                            </div>
                        </button>
                    </form>
                </div>
        -->
        <div class="toggle-container">
    <form method="GET" action="index.php">
        <input type="hidden" name="page" value="activer_promo">
        <input type="hidden" name="activer_promo" value="<?= $promo['id'] ?>">
        <button type="submit" class="toggle-label <?= $promo["statut"] === "Active" ? "active" : "" ?>">
            <span class="status-pill">
                <?= $promo["statut"] === "Active" ? "Active" : "Inactive" ?>
            </span>
            <div class="power-button">
                <svg class="power-icon" viewBox="0 0 24 24">
                    <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path>
                    <line x1="12" y1="2" x2="12" y2="12"></line>
                </svg>
            </div>
        </button>
    </form>
</div>

                <div class="promo-body">
                    <div class="promo-image">
                        <img src="<?= $promo['photo'] ?>" alt="<?= $promo['nom'] ?>">
                    </div>
                    <div class="promo-details">
                        <h3><?= htmlspecialchars($promo['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?></h3>
                        <p class="promo-date"><?= date("d/m/Y", strtotime($promo['dateDebut'])) ?> - <?= date("d/m/Y", strtotime($promo['dateFin'])) ?></p>
                    </div>
                </div>

                <div class="student">
                    <div class="promo-students">
                        <p class="p"><?= $promo['nbrApprenant'] ?> apprenant<?= $promo['nbrApprenant'] > 1 ? "s" : "" ?></p>
                    </div>
                </div>

                <div class="promo-footer">
                    <button class="details-btn">Voir détails ></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($total > 1): ?>
        <div class="custom-pagination">
            <!-- Flèche gauche -->
            <a href="?page=liste_promo&p=<?= max(1, $page - 1) ?>" class="arrow <?= $page === 1 ? 'disabled' : '' ?>">&#10094;</a>

            <!-- Pages -->
            <?php for ($i = 1; $i <= $total; $i++): ?>
                <a href="?page=liste_promo&p=<?= $i ?>" class="page-number <?= $i === $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <!-- Flèche droite -->
            <a href="?page=liste_promo&p=<?= min($total, $page + 1) ?>" class="arrow <?= $page === $total ? 'disabled' : '' ?>">&#10095;</a>
        </div>

        <!-- Affichage "1 à 5 pour 8" -->
        <div class="pagination-info">
            <?= $debut + 1 ?> à <?= min($debut + $parPage, $total) ?> pour <?= $total ?>
        </div>
    <?php endif; ?>


</div>





<!-- fin -->
</body>
</html>