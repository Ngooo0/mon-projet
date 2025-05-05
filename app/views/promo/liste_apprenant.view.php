<?php
// Nombre d'éléments par page (par défaut à 5)
$perPage = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total = count($apprenants);
$pages = ceil($total / $perPage);
$start = ($page - 1) * $perPage;
$paginatedApprenants = array_slice($apprenants, $start, $perPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<?php
require_once __DIR__ . '/../../enums/chemin_page.php';
use App\Enums\CheminPage;
$url = "http://" . $_SERVER["HTTP_HOST"];
$css_apprenant = CheminPage::CSS_APPRENANT->value;
?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Liste des apprenants</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= $url . $css_apprenant ?>">
</head>
<body>
  <!-- En-tête -->
  <div class="header">
    <h1>Liste des apprenants</h1>
    <span class="count"><?= $total ?> apprenants</span>
  </div>
  
  <!-- Barre d'outils -->
  <div class="toolbar">
    <div class="search-box">
      <i class="fa fa-search"></i>
      <form method="GET" action="">
        <input type="hidden" name="page" value="liste_apprenant" />
        <input type="text" name="search" placeholder="Rechercher un apprenant..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit" class="submit-btn">Rechercher</button>
      </form>
    </div>
    <div class="filter-dropdown">
      <select onchange="location = this.value;">
        <option value="?filtre=tous" <?= ($_GET['filtre'] ?? '') === 'tous' ? 'selected' : '' ?>>Tous</option>
        <option value="?filtre=active" <?= ($_GET['filtre'] ?? '') === 'active' ? 'selected' : '' ?>>Actifs</option>
        <option value="?filtre=inactive" <?= ($_GET['filtre'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactifs</option>
      </select>
    </div>
  </div>
  
  <!-- Tableau -->
  <table>
    <thead>
      <tr>
        <th>Photo</th>
        <th>Matricule</th>
        <th>Nom</th>
        <th>Adresse</th>
        <th>Téléphone</th>
        <th>Référentiel</th>
        <th>Statut</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php if (!empty($paginatedApprenants)): ?>
      <?php foreach ($paginatedApprenants as $apprenant): ?>
        <tr>
          <td><img src="<?= htmlspecialchars($apprenant['photo'], ENT_QUOTES, 'UTF-8') ?>" alt="Photo" width="50"></td>
          <td><?= htmlspecialchars($apprenant['matricule'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($apprenant['nom'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($apprenant['adresse'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($apprenant['telephone'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($apprenant['referentiel'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><span class="status <?= strtolower($apprenant['statut']) ?>"><?= htmlspecialchars($apprenant['statut'], ENT_QUOTES, 'UTF-8') ?></span></td>
          <td><button class="action-btn">Modifier</button></td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="8">Aucun apprenant trouvé.</td>
      </tr>
    <?php endif; ?>
    </tbody>
  </table>
  
  <!-- Pagination -->
  <div class="pagination">
    <div class="page-size">
      <span>Page</span>
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
</body>
</html>