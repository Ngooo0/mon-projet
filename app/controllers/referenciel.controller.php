<?php
require_once __DIR__ . '/../enums/chemin_page.php';
require_once __DIR__ . '/../models/ref.model.php';
require_once __DIR__ . '/../models/model.php';

use App\Enums\CheminPage;
use App\Models\REFMETHODE;

function afficher_referentiels(): void {
    global $ref_model;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter') {
        ajouter_referenciel();
    }
    
    if (!isset($ref_model) || !is_array($ref_model) || !isset($ref_model[REFMETHODE::GET_ALL->value])) {
        throw new Exception("Reference model not properly initialized");
    }
    
    $referentiels = $ref_model[REFMETHODE::GET_ALL->value]();
    
    $searchTerm = $_GET['search'] ?? '';
    if (!empty($searchTerm)) {
        $referentiels = array_filter($referentiels, function($ref) use ($searchTerm) {
            return stripos($ref['nom'], $searchTerm) !== false;
        });
    }
    
    
    
    render('referenciel/referenciel', ['referentiels' => $referentiels,]);
}

function afficher_tous_referentiels(): void {
    global $ref_model;
    
    $referentiels = $ref_model[REFMETHODE::GET_ALL->value]();
    
    $searchTerm = $_GET['search'] ?? '';
    if (!empty($searchTerm)) {
        $referentiels = array_filter($referentiels, function($ref) use ($searchTerm) {
            return stripos($ref['nom'], $searchTerm) !== false;
        });
    }
    
    render('referenciel/all_referenciel', ['referentiels' => $referentiels]);
}

function ajouter_referenciel(): void {
    global $ref_model;
    
    if (empty($_POST['nom']) || empty($_POST['capacite'])) {
        // Gérer l'erreur
        return;
    }
    


    $cheminPhoto = gerer_upload_photo($_FILES['photo']);

   
    $nouveau_ref = [
        'id' => time(), // Utilisation du timestamp comme ID
        'nom' => $_POST['nom'],
        'capacite' => (int)$_POST['capacite'],
        'photo' => $cheminPhoto,
        'modules' => 0,
        'apprenants' => 0
    ];
    
    // Ajout dans le JSON
    $ref_model[REFMETHODE::AJOUTER->value]($nouveau_ref);
    
    // Redirection
    header('Location: ?page=referenciel');
    exit;
}

function affecter_referenciel(): void {
    // Logique d'affectation
}