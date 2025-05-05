<?php
global $promos;
require_once __DIR__ . '/../enums/chemin_page.php';
use App\Enums\CheminPage;
require_once CheminPage::MESSAGE_ENUM->value;
require_once CheminPage::ERREUR_ENUM->value;
require_once CheminPage::SESSION_SERVICE->value;
require_once CheminPage::VALIDATOR_SERVICE->value;

use App\ENUM\ERREUR\ErreurEnum;
use App\Models\APPMETHODE;
use App\Models\JSONMETHODE;
use App\ENUM\MESSAGE\MSGENUM;
use App\ENUM\VALIDATOR\VALIDATORMETHODE;
require_once CheminPage::APPRENANT_MODEL->value;
require_once __DIR__ . '/../models/apprenant.model.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Affiche la liste des apprenants
 */
function afficher_apprenants(): void {
    $statut = $_GET['filtre'] ?? 'tous'; // Récupérer le filtre par statut
    $referentiel = $_GET['referentiel'] ?? 'tous'; // Récupérer le filtre par référentiel
    $searchTerm = $_GET['search'] ?? ''; // Récupérer le terme de recherche

    // Charger les apprenants filtrés par statut
    $apprenants = filtrer_apprenants_par_statut($statut);

    // Filtrer par référentiel
    $apprenants = filtrer_apprenants_par_referentiel($referentiel);

    // Appliquer la recherche par nom ou matricule
    if (!empty($searchTerm)) {
        $apprenants = array_filter($apprenants, function ($apprenant) use ($searchTerm) {
            return stripos($apprenant['nom'], $searchTerm) !== false || 
                   stripos($apprenant['matricule'], $searchTerm) !== false;
        });
    }

    // Appeler la vue avec les apprenants filtrés
    render('promo/apprenant', [
        'apprenants' => $apprenants
    ], layout: 'base.layout');
}

/**
 * Affiche tous les apprenants avec une option de recherche
 */
function afficher_tous_apprenants(): void {
    global $app_model;

    $apprenants = $app_model[APPMETHODE::GET_ALL->value]();

    // Recherche par nom
    $searchTerm = $_GET['search'] ?? '';
    if (!empty($searchTerm)) {
        $apprenants = array_filter($apprenants, function ($apprenant) use ($searchTerm) {
            return stripos($apprenant['nom'], $searchTerm) !== false;
        });
    }

    render('promo/all_apprenant', [
        'apprenants' => $apprenants
    ], layout: 'base.layout');
}

/**
 * Ajoute un nouvel apprenant
 */
function ajouter_apprenant(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Générer un matricule unique
        $matricule = 'APP-' . strtoupper(uniqid());

        // Récupérer les données du formulaire
        $apprenant = [
            'matricule' => $matricule,
            'prenom' => $_POST['prenom'],
            'nom' => $_POST['nom'],
            'date_naissance' => $_POST['date_naissance'],
            'lieu_naissance' => $_POST['lieu_naissance'],
            'adresse' => $_POST['adresse'],
            'email' => $_POST['email'],
            'telephone' => $_POST['telephone'],
            'photo' => null, // Par défaut, aucune photo
            'nom_tuteur' => $_POST['nom_tuteur'],
            'lien_parente' => $_POST['lien_parente'],
            'adresse_tuteur' => $_POST['adresse_tuteur'],
            'telephone_tuteur' => $_POST['telephone_tuteur']
        ];

        // Gérer l'upload de la photo
        if (!empty($_FILES['photo']['name'])) {
            $dossierCible = __DIR__ . '/../../public/uploads/';
            if (!is_dir($dossierCible)) {
                mkdir($dossierCible, 0777, true);
            }
            $cheminPhoto = $dossierCible . basename($_FILES['photo']['name']);
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $cheminPhoto)) {
                $apprenant['photo'] = '/uploads/' . basename($_FILES['photo']['name']);
            }
        }

        // Ajouter l'apprenant dans le fichier JSON
        if (ajouterApprenantDansJson($apprenant)) {
            // Rediriger vers la liste des apprenants après l'ajout
            header('Location: index.php?page=liste_apprenant');
            exit;
        } else {
            echo "Erreur lors de l'ajout de l'apprenant.";
        }
    }
}

/**
 * Affiche le formulaire d'ajout d'un apprenant
 */
function afficher_formulaire_ajout_apprenant(): void {
    render('promo/ajoutapp', [], layout: 'base.layout');
}

/**
 * Importer des apprenants depuis un fichier Excel
 */
function importer_apprenants_excel(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['import_excel']) && !empty($_FILES['import_excel']['tmp_name'])) {
        $file = $_FILES['import_excel']['tmp_name'];

        try {
            $spreadsheet = IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            foreach ($rows as $index => $row) {
                if ($index === 0) {
                    // Ignorer la première ligne (en-têtes)
                    continue;
                }

                $apprenant = [
                    'matricule' => 'APP-' . strtoupper(uniqid()),
                    'prenom' => $row[0] ?? '',
                    'nom' => $row[1] ?? '',
                    'date_naissance' => $row[2] ?? '',
                    'lieu_naissance' => $row[3] ?? '',
                    'adresse' => $row[4] ?? '',
                    'email' => $row[5] ?? '',
                    'telephone' => $row[6] ?? '',
                    'referentiel' => $row[7] ?? '',
                    'statut' => $row[8] ?? 'inactif',
                ];

                // Ajouter l'apprenant dans le fichier JSON ou la base de données
                ajouterApprenantDansJson($apprenant);
            }

            // Rediriger après l'importation
            header('Location: index.php?page=liste_apprenant');
            exit;
        } catch (Exception $e) {
            echo "Erreur lors de l'importation : " . $e->getMessage();
        }
    }
}

/**
 * Gestion de l'ajout ou de l'importation des apprenants
 */
if (isset($_FILES['import_excel']) && !empty($_FILES['import_excel']['tmp_name'])) {
    importer_apprenants_excel();
} else {
    ajouter_apprenant(); // Fonction existante pour ajouter un apprenant manuellement
}



