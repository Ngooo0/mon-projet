<?php
require_once __DIR__ . '/../enums/chemin_page.php';
use App\Enums\CheminPage;
require_once CheminPage::CONTROLLER->value;
require_once CheminPage::MODEL->value;
// Définir la page par défaut
$page = $_GET['page'] ?? 'login';
// Résolution des routes
match ($page) {
    'login', 'logout' => (function () {
        require_once CheminPage::AUTH_CONTROLLER->value;
        voir_page_login();
    })(),

    'resetPassword' => (function () {
        require_once CheminPage::AUTH_CONTROLLER->value;
    })(),

    'liste_promo', => (function () {
        require_once CheminPage::PROMO_CONTROLLER->value;
        //afficher_promotions();
        traiter_creation_promotion();
    })(),

    'liste_apprenant', => (function () {
        require_once CheminPage::APPRENANT_CONTROLLER->value;
        afficher_apprenants();
    })(),

    'liste_apprenant_excel' => (function () {
        require_once CheminPage::APPRENANT_CONTROLLER->value;
        exporter_apprenants_excel();
    })(),

    'liste_apprenant_pdf' => (function () {
        require_once CheminPage::APPRENANT_CONTROLLER->value;
        exporter_apprenants_pdf();
    })(),

    'ajoutpromo', => (function () {
        require_once CheminPage::PROMO_CONTROLLER->value;
        ajouterpromo();
    })(),

    'ajoutapprenant' => (function () {
        require_once CheminPage::APPRENANT_CONTROLLER->value;
        ajouter_apprenant();
    })(),

    'activer_promo', => (function () {
        require_once CheminPage::PROMO_CONTROLLER->value;
        traiter_activation_promotion();
    })(),

    'activer_promo_liste', => (function () {
        require_once CheminPage::PROMO_CONTROLLER->value;
        traiter_activation_promotion_liste();
    })(),

    'liste_table_promo' => (function () {
    require_once CheminPage::PROMO_CONTROLLER->value;
    afficher_promotions_en_table();
    })(),

    'layout' => (function () {
        require_once CheminPage::LAYOUT_CONTROLLER->value;
    })(),

    'referenciel' => (function() {
    require_once CheminPage::REFERENCIEL_CONTROLLER->value;
    afficher_referentiels();
    })(),

    'all_referenciel' => (function() {
        require_once CheminPage::REFERENCIEL_CONTROLLER->value;
        afficher_tous_referentiels();
    })(),

    'apprenant' => (function () {
        require_once CheminPage::APPRENANT_CONTROLLER->value;
        afficher_apprenants();
    })(),

    'ajoutapp' => (function () {
        require_once CheminPage::APPRENANT_CONTROLLER->value;
        afficher_formulaire_ajout_apprenant(); // Fonction pour afficher le formulaire
    })(),

    'error' => (function () {
        require_once __DIR__ . '/../controllers/error.controller.php';
        showError("Page introuvable");
    })(),

    default => (function () use ($page) {
        require_once __DIR__ . '/../controllers/error.controller.php';
        showError("404 - Page '$page' non reconnue");
    })()
};





