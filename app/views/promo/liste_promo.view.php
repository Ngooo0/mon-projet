<?php
// Nombre d'éléments par page (par défaut à 5)
$perPage = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total = count($promotions);
$pages = ceil($total / $perPage);
$start = ($page - 1) * $perPage;
$paginatedPromos = array_slice($promotions, $start, $perPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>

<?php
require_once __DIR__ . '/../../enums/chemin_page.php';
use App\Enums\CheminPage;
$url = "http://" . $_SERVER["HTTP_HOST"];
$css_promo = CheminPage::CSS_PROMO->value;
?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des promotions</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= $url . $css_promo ?>">
</head>
<body>
  <!-- En-tête -->
  <div class="header">
    <h1>Promotion</h1>
    <span class="count">180 apprenants</span>
  </div>
  
  <!-- Barre d'outils -->
  <div class="toolbar">
    <div class="search-box">
      <i class="fa fa-search"></i>
      <input type="text" placeholder="Rechercher...">
    </div>
    <div class="filter-dropdown">
      <select>
        <option>Filtre par classe</option>
      </select>
    </div>
    <div class="filter-dropdown">
      <select>
        <option>Filtre par status</option>
      </select>
    </div>
    <button >
      <a href="index.php?page=ajoutpromo" class="add-btn">+ Ajouter une promotion</a>
    </button>
  </div>
  
  <!-- Cartes d'information -->
  <div class="cards">
    <div class="card">
      <div class="icon">
        <i class="fa fa-graduation-cap"></i>
      </div>
      <div class="info">
        <div class="number">180</div>
        <div class="label">Apprenants</div>
      </div>
    </div>
    <div class="card">
      <div class="icon">
        <i class="fa fa-folder"></i>
      </div>
      <div class="info">
        <div class="number">5</div>
        <div class="label">Référentiels</div>
      </div>
    </div>
    <div class="card">
      <div class="icon">
        <i class="fa fa-user-graduate"></i>
      </div>
      <div class="info">
        <div class="number">5</div>
        <div class="label">Stagiaires</div>
      </div>
    </div>
    <div class="card">
      <div class="icon">
        <i class="fa fa-users"></i>
      </div>
      <div class="info">
        <div class="number">13</div>
        <div class="label">Permanent</div>
      </div>
    </div>
  </div>
  
  <!-- Tableau -->
  <table>
    <thead>
      <tr>
        <th>Photo</th>
        <th>Promotion</th>
        <th>Date de début</th>
        <th>Date de fin</th>
        <th>Référentiel</th>
        <th>Statut</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>

    <?php


  foreach ($paginatedPromos as $promo): ?>
  <tr>
      <td class="photo-cell"><img src="<?= $promo["photo"] ?>" alt="photo" width="50"></td>
      <td class="promo-cell"><?= $promo["nom"] ?></td>
      <td class="date-cell"><?= $promo["dateDebut"] ?></td>
      <td class="date-cell"><?= $promo["dateFin"] ?></td>
      <td>
          <div class="tag">
              <span class="tag dev-web">DEV WEB/MOBILE</span>
              <span class="tag ref-dig">REF DIG</span>
              <span class="tag dev-data">DEV DATA</span>
              <span class="tag aws">AWS</span>
              <span class="tag hackeuse">HACKEUSE</span>
          </div>
      </td>
      <td>
          <form method="POST" action="index.php?page=liste_promo">
              <input type="hidden" name="toggle_promo_id" value="<?= $promo['id'] ?>">
              <input type="hidden" name="current_status" value="<?= $promo['statut'] ?>">
              <button type="submit" class="status <?= strtolower($promo['statut']) ?>">
                  <?= $promo['statut'] ?>
              </button>
          </form>
      </td>
      <td class="action-cell"><span class="dots">•••</span></td>
  </tr>
  <?php endforeach; ?>
  
?>
     
    </tbody>
  </table>
  
  <div class="pagination">
  <div class="page-size">
    <span>page</span>
    <form method="get" style="display: inline;">
      <select name="limit" onchange="this.form.submit()">
        <option <?= $perPage == 5 ? 'selected' : '' ?>>5</option>
        <option <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
        <option <?= $perPage == 20 ? 'selected' : '' ?>>20</option>
      </select>
      <input type="hidden" name="page" value="<?= $page ?>">
    </form>
  </div>

  <div class="page-info"><?= $start + 1 ?> à <?= min($start + $perPage, $total) ?> pour <?= $total ?></div>

  <div class="page-controls">
    <?php if ($page > 1): ?>
      <a href="?page=<?= $page - 1 ?>&limit=<?= $perPage ?>"><button><i class="fa fa-angle-left"></i></button></a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $pages; $i++): ?>
      <a href="?page=<?= $i ?>&limit=<?= $perPage ?>"><button class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></button></a>
    <?php endfor; ?>

    <?php if ($page < $pages): ?>
      <a href="?page=<?= $page + 1 ?>&limit=<?= $perPage ?>"><button><i class="fa fa-angle-right"></i></button></a>
    <?php endif; ?>
  </div>
</div>


<div id="popup">
    <div class="modal">
        <a href="#" class="close-btn">&times;</a>
        <h2>Créer une nouvelle promotion</h2>
        <p class="subtitle">Remplissez les informations ci-dessous pour créer une nouvelle promotion.</p>

        <form class="modal-form" method="POST" action="?page=liste_promo" enctype="multipart/form-data">
            <input type="hidden" name="nouvelle_promo" value="1">

            <label>
                Nom de la promotion
                <?php if (!empty($_SESSION['errors']['nom_promo'])): ?>
                <p class="error-message"><?= htmlspecialchars($_SESSION['errors']['nom_promo']) ?></p>
            <?php endif; ?> </p>
                <input
                    type="text"
                    name="nom_promo"
                    placeholder="Ex: Promotion 2025"
                />
            </label>
           

            <div class="date-fields">
                <label>
                    Date de début
                    <?php if (!empty($_SESSION['errors']['date_debut'])): ?>
                    <p class="error-message"><?= htmlspecialchars($_SESSION['errors']['date_debut']) ?></p>
                <?php endif; ?>
                    <input
                        type="text"
                        name="date_debut"
                        placeholder="YYYY-MM-DD"
                        class="<?= !empty($_SESSION['errors']['date_debut']) ? 'alert' : '' ?>"
                    />
                </label>
               

                <label>
                    Date de fin
                    <?php if (!empty($_SESSION['errors']['date_fin'])): ?>
                    <p class="error-message"><?= htmlspecialchars($_SESSION['errors']['date_fin']) ?></p>
                <?php endif; ?>
                    <input
                        type="text"
                        name="date_fin"
                        placeholder="YYYY-MM-DD"
                    />
                </label>
               
            </div>

            <label class="file-upload">
                Photo de la promotion
                <?php if (!empty($_SESSION['errors']['photo'])): ?>
                <p class="error-message"><?= htmlspecialchars($_SESSION['errors']['photo']) ?></p>
            <?php endif; ?>
                <div class="drop-zone">
                    <span class="drop-text">Ajouter<br><small>ou glisser</small></span>
                    <input
                        type="file"
                        name="photo"
                        accept="image/png, image/jpeg"
                        class="<?= !empty($_SESSION['errors']['photo']) ? 'alert' : '' ?>"
                    />
                </div>
                <small class="file-hint">Format JPG, PNG. Taille max 2MB</small>
            </label>
         
            <label>
                Référentiel ID
                <input
                    type="text"
                    name="referenciel_id"
                    placeholder="Ex: 1"
                    class="<?= !empty($_SESSION['errors']['referenciel_id']) ? 'alert' : '' ?>"
                />
            </label>
            <?php if (!empty($_SESSION['errors']['referenciel_id'])): ?>
                <p class="error-message"><?= htmlspecialchars($_SESSION['errors']['referenciel_id']) ?></p>
            <?php endif; ?>

            <div class="modal-actions">
                <a href="#" class="cancel-btn">Annuler</a>
                <button type="submit" class="submit-btn">Créer la promotion</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>