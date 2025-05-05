<!DOCTYPE html>
<html lang="fr">
<?php
require_once __DIR__ . '/../../enums/chemin_page.php';
use App\Enums\CheminPage;
$url = "http://" . $_SERVER["HTTP_HOST"];
$css_apprenant = CheminPage::CSS_APPRENANT->value;

// --- Partie ajoutée pour la recherche et le filtre ---
$search = $_GET['search'] ?? '';   // Récupère le texte de recherche
$filtre = $_GET['filtre'] ?? ''; // Récupère le filtre actif (tous, actifs, inactifs)

    // On filtre les apprenants en fonction de la recherche et du statut
    $apprenantsFiltres = array_filter($apprenants, function($apprenant) use ($search, $filtre) {
    // Filtre la recherche par nom (insensible à la casse)
    $correspondRecherche = empty($search) || stripos($apprenant['nom'], $search) !== false;

    // Filtre le statut (tous, actifs, inactifs)
    $correspondFiltre = empty($filtre) || stripos($apprenant['re'], $filtre) !== false;
                        
    // On retourne vrai si les deux conditions sont remplies
    return $correspondRecherche && $correspondFiltre;
});
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des apprenants</title>
    <link rel="stylesheet" href="<?= $url . $css_apprenant ?>" />
</head>

<body>

<header class="header">
    <h2>Liste des apprenants</h2>
</header>

<div class="search-bar">

    <form method="GET" action="" style="display: flex; flex: 1;">

        <input type="hidden" name="page" value="liste_apprenant" />
        <input type="text" name="search" placeholder="Rechercher un apprenant..." value="<?= htmlspecialchars($search) ?>" />

        <select name="filtre">
            <option value="tous" <?= ($filtre === 'tous') ? 'selected' : '' ?>>Tous</option>
            <option value="active" <?= ($filtre === 'active') ? 'selected' : '' ?>>Actifs</option>
            <option value="inactive" <?= ($filtre === 'inactive') ? 'selected' : '' ?>>Inactifs</option>
        </select>

        <button type="submit" class="submit-btn">Rechercher</button>

    </form>

    <button type="submit" class="add-btn2">
            Télécharger la liste
    </button>


    <a class="add-btn" href="index.php?page=ajoutapp">
         Ajouter un apprenant
</a>

</div>
<main>
    <table>
        <thead>
            <tr>
                <th>Photo</th>
                <th>Matricule</th>
                <th>Nom Complet</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Référentiel</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($apprenantsFiltres)): ?>
                <?php foreach ($apprenantsFiltres as $apprenant): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($apprenant['photo'], ENT_QUOTES, 'UTF-8') ?>" alt="Photo" class="photo"></td>
                        <td><?= htmlspecialchars($apprenant['matricule'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($apprenant['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($apprenant['adresse'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($apprenant['telephone'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="referentiel"><?= htmlspecialchars($apprenant['referentiel'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><span class="status <?= strtolower($apprenant['statut']) ?>"><?= htmlspecialchars($apprenant['statut'], ENT_QUOTES, 'UTF-8') ?></span></td>
                        <td><button class="action-btn">...</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Aucun apprenant trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

</body>
</html>
