<?php
require_once __DIR__ . '/../enums/model.enum.php';
require_once __DIR__ . '/../enums/chemin_page.php';

use App\Models\APPMETHODE;
use App\Models\JSONMETHODE;
use App\Enums\CheminPage;

global $app_model;

$app_model = [
    // Récupérer tous les apprenants
    APPMETHODE::GET_ALL->value => function(): array {
        global $model_tab;
        $chemin = CheminPage::DATA_JSON->value;
        return $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin)['apprenants'] ?? [];
    },

    // Ajouter un apprenant
    APPMETHODE::AJOUTER->value => function(array $apprenant): bool {
        global $model_tab;
        $chemin = CheminPage::DATA_JSON->value;
        $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);
        
        if (!isset($data['apprenants'])) {
            $data['apprenants'] = [];
        }
        
        // Ajouter le nouvel apprenant
        $data['apprenants'][] = $apprenant;
        
        return $model_tab[JSONMETHODE::ARRAYTOJSON->value]($data, $chemin);
    },
];

function charger_apprenants(): array {
    global $model_tab;
    $chemin = CheminPage::DATA_JSON->value;

    // Vérifiez si la méthode JSONTOARRAY est définie et callable
    if (!isset($model_tab[JSONMETHODE::JSONTOARRAY->value]) || !is_callable($model_tab[JSONMETHODE::JSONTOARRAY->value])) {
        throw new Exception("La méthode JSONTOARRAY n'est pas définie ou n'est pas callable.");
    }

    return $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin, 'apprenants') ?? [];
}

function filtrer_apprenants_par_statut(string $statut): array {
    $apprenants = charger_apprenants();

    // Si le statut est "tous", on retourne tous les apprenants
    if ($statut === 'tous') {
        return $apprenants;
    }

    // Filtrer les apprenants par statut
    return array_filter($apprenants, function ($apprenant) use ($statut) {
        return strtolower($apprenant['statut']) === strtolower($statut);
    });
}

function filtrer_apprenants_par_referentiel(string $referentiel): array {
    $apprenants = charger_apprenants();

    // Si le référentiel est "tous", on retourne tous les apprenants
    if ($referentiel === 'tous') {
        return $apprenants;
    }

    // Filtrer les apprenants par référentiel
    return array_filter($apprenants, function ($apprenant) use ($referentiel) {
        return strtolower($apprenant['referentiel']) === strtolower($referentiel);
    });
}

function ajouterApprenantDansJson(array $apprenant): bool {
    $cheminFichier = __DIR__ . '/../data/data.json';

    // Lire le contenu actuel du fichier JSON
    if (!file_exists($cheminFichier)) {
        file_put_contents($cheminFichier, json_encode(['apprenants' => []]));
    }

    $contenu = json_decode(file_get_contents($cheminFichier), true);

    // Ajouter le nouvel apprenant
    $contenu['apprenants'][] = $apprenant;

    // Sauvegarder les modifications dans le fichier JSON
    return file_put_contents($cheminFichier, json_encode($contenu, JSON_PRETTY_PRINT)) !== false;
}

