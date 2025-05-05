<!DOCTYPE html>
<html lang="fr">
<?php
require_once __DIR__ . '/../../enums/chemin_page.php';
use App\Enums\CheminPage;

$url = "http://" . $_SERVER["HTTP_HOST"];
$css_ajoutpromo = CheminPage::CSS_AJOUTPROMO->value;

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une promotion</title>
    <link rel="stylesheet" href="<?= $url . $css_ajoutpromo ?>" />
</head>

<body>

<div>
    <?php
        // Démarrer la session et charger les erreurs
        require_once CheminPage::ERROR_FR->value;
        use App\ENUM\ERREUR\ErreurEnum;

        $erreurs = recuperer_session('errors', []);
        $success = recuperer_session('success', []);

    ?>
    <div>
        <a href="#" class="close-btn">&times;</a>

        <?php if (!empty($erreurs)): ?>
            <div class="global-error">
                <ul>
                    <?php foreach ($erreurs as $erreur): ?>
                        <li><?= htmlspecialchars($erreur) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form class="modal-form" method="POST" action="?page=liste_promo" enctype="multipart/form-data">
            <input type="hidden" name="nouvelle_promo" value="1">

            <h2>Ajouter une promotion</h2>
            <p class="subtitle">Veuillez remplir tous les champs</p>

            <!-- Champ NOM -->
            <label for="nom">Nom de la promotion</label>
            <input type="text" name="nom_promo" id="nom"

                value="<?= htmlspecialchars($oldInputs['nom_promo'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

                <?php if (isset($erreurs['PROMO_NAME_REQUIRED'])): ?>
                <div class="error-message"><?= $error[$erreurs['login']] ?></div>
                <?php endif; ?>


            <!-- Dates -->
            <div class="date-fields">
                <label for="date_debut">Date de début</label>
                <input 
                    type="text" 
                    name="date_debut" 
                    id="date_debut"
                    placeholder="AAAA-MM-JJ"
                    value="<?= htmlspecialchars($oldInputs['date_debut'] ?? '') ?>">

                <?php if (isset($erreurs['date_debut'])): ?>
                    <div class="error-message"><?= $error[$erreurs['login']] ?></div>
                <?php endif; ?>

                

            </div>
            <div class="date-fields">

                <label for="date_fin">Date de fin</label>
                <input 
                    type="text" 
                    name="date_fin" 
                    id="date_fin"
                    placeholder="AAAA-MM-JJ"
                    class="<?= !empty($erreurs['date_fin']) ? 'alert' : '' ?>"
                    value="<?= htmlspecialchars($oldInputs['date_fin'] ?? '') ?>">
                <?php if (!empty($erreurs['date_fin'])): ?>
                    <div class="error-message"><?= htmlspecialchars($erreurs['date_fin']) ?></div>
                <?php endif; ?>
            </div>

            <!-- Photo -->
            <label class="file-upload">
                Photo de la promotion

                <?php if (!empty($erreurs['PROMO_DATE_REQUIRED'])): ?>
                    <div class="error-message"><?= htmlspecialchars($erreurs['PROMO_DATE_REQUIRED']) ?></div>
                <?php endif; ?>

                <div class="drop-zone">
                    <span class="drop-text">Ajouter<br><small>ou glisser</small></span>
                    <input
                        type="file"
                        name="photo"
                        accept="image/png, image/jpeg"
                        class="<?= !empty($erreurs['photo']) ? 'alert' : '' ?>"
                    />
                </div>
                <small class="file-hint">Format JPG, PNG. Taille max 2MB</small>
            </label>

            <!-- Référentiels -->
            <label>Référentiels :</label>
            <div class="checkbox-group">
                <?php
                $selected = $_SESSION['old_inputs']['referenciel_id'] ?? [];

                foreach ($referentiels as $ref):
                    $id = $ref['id'];
                    $label = $ref['nom'];
                ?>
                    <div>
                        <label>
                            <input
                                type="checkbox"
                                name="referenciel_id[]"
                                value="<?= $id ?>"
                                <?= is_array($selected) && in_array($id, $selected) ? 'checked' : '' ?>
                                class="<?= !empty($erreurs['referenciel_id']) ? 'alert' : '' ?>"
                            />
                            <?= htmlspecialchars($label) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($erreurs['referenciel_id'])): ?>
                <div class="error-message"><?= htmlspecialchars($erreurs['referenciel_id']) ?></div>
            <?php endif; ?>

            <!-- Actions -->
            <div class="modal-actions">
                <a href="index.php?page=liste_promo" class="btn btn-secondary cancel">Annuler</a>
                <button type="submit" class="submit-btn">Créer la promotion</button>

            </div>

        </form>
    </div>
</div>

<?php unset($_SESSION['success']); ?>

<?php unset($_SESSION['errors']); ?>

</body>
</html>
