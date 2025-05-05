<?php
global $promos;
require_once __DIR__ . '/../enums/chemin_page.php';
use App\Enums\CheminPage;
require_once CheminPage::MESSAGE_ENUM->value;
require_once CheminPage::ERREUR_ENUM->value;
require_once CheminPage::SESSION_SERVICE->value;
require_once CheminPage::VALIDATOR_SERVICE->value;

use App\ENUM\ERREUR\ErreurEnum;
use App\Models\PROMOMETHODE;
use App\Models\JSONMETHODE;
use App\ENUM\MESSAGE\MSGENUM;
use App\ENUM\VALIDATOR\VALIDATORMETHODE;
require_once CheminPage::PROMO_MODEL->value;

function afficher_promotions($message = null, $error = []): void {
    global $promos;
    $nomRecherche = $_GET['search'] ?? null;
    $filtreStatut = $_GET['filtre'] ?? null;
    $liste_promos = $promos[PROMOMETHODE::GET_ALL->value]($nomRecherche, $filtreStatut);
    $referentiels = charger_referentiels_depuis_json(CheminPage::DATA_JSON->value);

    // Récupérer la promotion active et la séparer
    $activePromo = null;
    $promotionsSansActive = [];

    foreach ($liste_promos as $promo) {
        if (strtolower($promo['statut']) === 'active') {
            $activePromo = $promo; // promotion active
        } else {
            $promotionsSansActive[] = $promo; // autres promotions
        }
    }

    // Si une promotion active existe, on la met en première position
    if ($activePromo) {
        array_unshift($promotionsSansActive, $activePromo);
    }

    // Pagination sur la liste mise à jour
    $parPage = 4;
    $total = count($promotionsSansActive);
    $pageCourante = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
    $pageCourante = max(1, min($pageCourante, ceil($total / $parPage)));

    $error = $_SESSION['errors'] ?? [];
    $oldInputs = $_SESSION['old_inputs'] ?? [];
    unset($_SESSION['errors'], $_SESSION['old_inputs']);

    $debut = ($pageCourante - 1) * $parPage;
    $promos_pagination = array_slice($promotionsSansActive, $debut, $parPage);

    render("promo/promo", [
        "promotions" => $promos_pagination,
        "page" => $pageCourante,
        "total" => ceil($total / $parPage),
        "debut" => $debut,
        "parPage" => $parPage,
        "message" => $message,
        "errors" => $error,
        "referentiels" => $referentiels,
        "activePromo" => $activePromo, // Passer la promotion active à la vue
    ]);
}



function traiter_activation_promotion(): void {
    global $promos;

    if (isset($_GET['activer_promo'])) {
        $idPromo = (int) $_GET['activer_promo'];
        $cheminFichier = CheminPage::DATA_JSON->value;

        $promos[PROMOMETHODE::ACTIVER_PROMO->value]($idPromo, $cheminFichier);
    }
    redirect_to_route('index.php', ['page' => 'liste_promo']);
}

function traiter_activation_promotion_liste(): void {
    global $promos;

    if (isset($_GET['activer_promo_liste'])) {
        $idPromo = (int) $_GET['activer_promo_liste'];
        $cheminFichier = CheminPage::DATA_JSON->value;

        $promos[PROMOMETHODE::ACTIVER_PROMO->value]($idPromo, $cheminFichier);
    }
    redirect_to_route('index.php', ['page' => 'liste_table_promo']);
}

function ajouterpromo(): void {
    $referentiels = charger_referentiels_depuis_json(CheminPage::DATA_JSON->value);

    if (isset($_GET['annuler'])) {
        header('Location: index.php?page=liste_promo');
        exit;
    }

    $error = $_SESSION['errors'] ?? [];
    $oldInputs = $_SESSION['old_inputs'] ?? [];

    unset($_SESSION['errors'], $_SESSION['old_inputs']);

    render('promo/ajoutpromo', [
        'referentiels' => $referentiels,
        'errors' => $error,
        'oldInputs' => $oldInputs
    ], layout: 'base.layout');
}

function traiter_creation_promotion(): void {
    global $model_tab, $validator, $promos;

    $cheminFichier = CheminPage::DATA_JSON->value;
    $error = [];

    $donneesExistantes = charger_promotions_existantes($cheminFichier);
    $error = valider_donnees_promotion($_POST);

    if (!empty($error)) {
        stocker_session('errors', $error);
        stocker_session('old_inputs', $_POST);
        afficher_promotions();
        return;
    }

    $cheminPhoto = null;
    if (isset($_FILES['photo']) && is_array($_FILES['photo'])) {
        $cheminPhoto = gerer_upload_photo($_FILES['photo']);
    }

    if ($cheminPhoto === null) {
        $error[] = "Erreur lors du téléchargement de l'image. Vérifiez les permissions du dossier.";
        stocker_session('errors', $error);
        afficher_promotions(null, $error);
        return;
    }

    $nouvellePromotion = creer_donnees_promotion($_POST, $donneesExistantes, $cheminPhoto);

    $promos[PROMOMETHODE::AJOUTER_PROMO->value]($nouvellePromotion, $cheminFichier);

    afficher_promotions();
}

function charger_promotions_existantes(string $chemin): array {
    global $model_tab;
    return $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);
}

function valider_donnees_promotion(array $donnees): array {
    $error = [];
    
    if (empty(trim($donnees['nom_promo'] ?? ''))) {
        $error['nom'] = "Le nom de la promotion est requis.";
    } elseif (strlen(trim($donnees['nom_promo'])) < 3) {
        $error['nom'] = "Le nom doit contenir au moins 3 caractères.";
    }

    if (empty($donnees['date_debut']) || empty($donnees['date_fin'])) {
        $error['date_debut'] = "Les dates de début et de fin sont requises.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $donnees['date_debut'])) {
        $error['date_debut'] = "La date de début doit être au format AAAA-MM-JJ.";
    } elseif ($donnees['date_debut'] >= $donnees['date_fin']) {
        $error['date_debut'] = "La date de début doit être antérieure à la date de fin.";
    }

    if (empty($donnees['referenciel_id'] ?? [])) {
        $error['referenciel_id'] = "Sélectionnez au moins un référentiel.";
    }

    return $error;
}

function creer_donnees_promotion(array $post, array $donneesExistantes, string $cheminPhoto): array {
    $promotions = $donneesExistantes['promotions'] ?? [];
    $nouvelId = getNextPromoId($promotions);

    return [
        "id" => $nouvelId,
        "nom" => $post['nom_promo'],
        "dateDebut" => $post['date_debut'],
        "dateFin" => $post['date_fin'],
        "referenciels" => array_map('intval', $post['referenciel_id'] ?? []),
        "photo" => $cheminPhoto,
        "statut" => "Inactive",
        "nbrApprenant" => 0
    ];
}

function afficher_promotions_en_table(): void {
    global $promos;
    $liste_promos = $promos["get_all"]();
    render("promo/liste_promo", ["promotions" => $liste_promos]);
}

function charger_referentiels_depuis_json(string $chemin): array {
    if (!file_exists($chemin)) return [];
    $data = json_decode(file_get_contents($chemin), true);
    return $data['referenciel'] ?? [];
}

function safe_htmlspecialchars(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
