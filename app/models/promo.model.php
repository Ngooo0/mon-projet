<?php
global $model_tab;
require_once __DIR__ . '/../enums/model.enum.php';
require_once __DIR__ . '/../enums/chemin_page.php';

use App\Enums\CheminPage;
use App\Models\JSONMETHODE;
use App\Models\PROMOMETHODE;

$json = CheminPage::DATA_JSON->value;

$jsontoarray = $model_tab[JSONMETHODE::JSONTOARRAY->value];
global $promos;

$promos = [
    // Récupérer toutes les promotions
    PROMOMETHODE::GET_ALL->value => function (?string $nomRecherche = null, ?string $statut = null) use ($jsontoarray, $json) {
    // Récupérer toutes les promotions
    $promotions = $jsontoarray($json, "promotions");

    // Filtrage des promotions par nom et statut
    $promotions = array_filter($promotions, function ($promo) use ($nomRecherche, $statut) {
        $matchNom = !$nomRecherche || str_contains(strtolower($promo['nom']), strtolower($nomRecherche));
        $matchStatut = !$statut || $statut === "tous" || strtolower($promo["statut"]) === strtolower($statut);
        return $matchNom && $matchStatut;
    });

    // Trouver la promotion active (si elle existe)
    $activePromo = null;
    $inactivePromos = [];

    foreach ($promotions as $promo) {
        if (strtolower($promo['statut']) === 'active') {
            // Si une promotion active existe, la garder
            $activePromo = $promo;
        } else {
            // Sinon, ajouter aux inactives
            $inactivePromos[] = $promo;
        }
    }

    // Si une promotion active existe, la mettre en première position
    if ($activePromo) {
        $promotions = array_merge([$activePromo], $inactivePromos);
    } else {
        $promotions = $inactivePromos;
    }

    return $promotions;
},

    

    // Activer une promotion et désactiver les autres
    PROMOMETHODE::ACTIVER_PROMO->value => function (int $idPromo, string $chemin): bool {
        global $model_tab;

        // Charger les données existantes depuis le fichier JSON
        $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);

        if (!isset($data['promotions'])) {
            return false; // Aucune promotion à activer
        }

        // Parcourir les promotions pour mettre à jour leur statut
        foreach ($data['promotions'] as &$promo) {
            $promo['statut'] = ($promo['id'] === $idPromo) ? 'Active' : 'Inactive';
        }

        // Sauvegarde des modifications dans le fichier JSON
        return $model_tab[JSONMETHODE::ARRAYTOJSON->value]($data, $chemin);
    }
];

