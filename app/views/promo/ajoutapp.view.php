<!DOCTYPE html>
<html lang="fr">
<?php
require_once __DIR__ . '/../../enums/chemin_page.php';
use App\Enums\CheminPage;
$url = "http://" . $_SERVER["HTTP_HOST"];
$css_ajoutapp = CheminPage::CSS_AJOUTAPP->value;
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un apprenant</title>
    <link rel="stylesheet" href="<?= $url . $css_ajoutapp ?>" />
</head>
<body>
    <div class="container">
        <h1>Ajouter un apprenant</h1>
        <form method="POST" action="index.php?page=ajoutapprenant" enctype="multipart/form-data">
            <section class="section-form">
                <h2>Informations de l'apprenant</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="prenom">Prénom(s)</label>
                        <input type="text" name="prenom" id="prenom" placeholder="Entrer le prénom" <?= isset($_FILES['import_excel']) && !empty($_FILES['import_excel']['name']) ? '' : 'required' ?>>
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" id="nom" placeholder="Entrer le nom" <?= isset($_FILES['import_excel']) && !empty($_FILES['import_excel']['name']) ? '' : 'required' ?>>
                    </div>
                    <div class="form-group">
                        <label for="date_naissance">Date de naissance</label>
                        <input type="date" name="date_naissance" id="date_naissance" <?= isset($_FILES['import_excel']) && !empty($_FILES['import_excel']['name']) ? '' : 'required' ?>>
                    </div>
                    <div class="form-group">
                        <label for="lieu_naissance">Lieu de naissance</label>
                        <input type="text" name="lieu_naissance" id="lieu_naissance" placeholder="Entrer le lieu" <?= isset($_FILES['import_excel']) && !empty($_FILES['import_excel']['name']) ? '' : 'required' ?>>
                    </div>
                    <div class="form-group">
                        <label for="adresse">Adresse</label>
                        <input type="text" name="adresse" id="adresse" placeholder="Entrer l'adresse" <?= isset($_FILES['import_excel']) && !empty($_FILES['import_excel']['name']) ? '' : 'required' ?>>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Entrer l'email" <?= isset($_FILES['import_excel']) && !empty($_FILES['import_excel']['name']) ? '' : 'required' ?>>
                    </div>
                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="text" name="telephone" id="telephone" placeholder="Entrer le numéro" <?= isset($_FILES['import_excel']) && !empty($_FILES['import_excel']['name']) ? '' : 'required' ?>>
                    </div>
                    <div class="form-group">
                        <label for="photo">Photo</label>
                        <input type="file" name="photo" id="photo" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="referentiel">Référentiel</label>
                        <input type="text" name="referentiel" id="referentiel" placeholder="Entrer le référentiel" <?= isset($_POST['export_excel']) ? '' : 'required' ?>>
                    </div>
                    <div class="form-group">
                        <label for="statut">Statut</label>
                        <select name="statut" id="statut" <?= isset($_POST['export_excel']) ? '' : 'required' ?>>
                            <option value="">-- Sélectionner un statut --</option>
                            <option value="actif">Actif</option>
                            <option value="inactif">Inactif</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="import_excel">Importer un fichier Excel</label>
                        <input type="file" name="import_excel" id="import_excel" accept=".xlsx, .xls">
                    </div>
                </div>
            </section>

            <section class="section-form">
                <h2>Informations du tuteur</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nom_tuteur">Nom complet</label>
                        <input type="text" name="nom_tuteur" id="nom_tuteur" placeholder="Entrer le nom complet" required>
                    </div>
                    <div class="form-group">
                        <label for="lien_parente">Lien de parenté</label>
                        <input type="text" name="lien_parente" id="lien_parente" placeholder="Ex: Père, Mère..." required>
                    </div>
                    <div class="form-group">
                        <label for="adresse_tuteur">Adresse</label>
                        <input type="text" name="adresse_tuteur" id="adresse_tuteur" placeholder="Entrer l'adresse" required>
                    </div>
                    <div class="form-group">
                        <label for="telephone_tuteur">Téléphone</label>
                        <input type="text" name="telephone_tuteur" id="telephone_tuteur" placeholder="Entrer le numéro" required>
                    </div>
                </div>
            </section>

            <div class="buttons">
                <a href="index.php?page=liste_apprenant" class="cancel-btn">Annuler</a>
                <button type="submit" class="submit-btn">Enregistrer</button>
            </div>
        </form>
        
    </div>
    <table>
        <tr>
            <td><?= htmlspecialchars($apprenant['matricule'] ?? 'Non défini') ?></td>
            <td><?= htmlspecialchars($apprenant['prenom'] ?? 'Non défini') ?></td>
            <td><?= htmlspecialchars($apprenant['nom'] ?? 'Non défini') ?></td>
            <td><?= htmlspecialchars($apprenant['adresse'] ?? 'Non défini') ?></td>
            <td><?= htmlspecialchars($apprenant['telephone'] ?? 'Non défini') ?></td>
            <td><?= htmlspecialchars($apprenant['referentiel'] ?? 'Non défini') ?></td>
            <td><?= htmlspecialchars($apprenant['statut'] ?? 'Non défini') ?></td>
        </tr>
    </table>
</body>
</html>
