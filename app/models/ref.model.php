<?php
require_once __DIR__ . '/../enums/model.enum.php';
require_once __DIR__ . '/../enums/chemin_page.php';

use App\Models\REFMETHODE;
use App\Models\JSONMETHODE;
use App\Enums\CheminPage;

global $ref_model;

$ref_model = [
    REFMETHODE::GET_ALL->value => function(): array {
        global $model_tab;
        $chemin = CheminPage::DATA_JSON->value;
        return $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin)['referenciel'] ?? [];
    },
    
    REFMETHODE::AJOUTER->value => function(array $referenciel): bool {
        global $model_tab;
        $chemin = CheminPage::DATA_JSON->value;
        $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);
        
        if (!isset($data['referenciel'])) {
            $data['referenciel'] = [];
        }
        
        // Ajouter le nouveau référentiel
        $data['referenciel'][] = $referenciel;
        
        return $model_tab[JSONMETHODE::ARRAYTOJSON->value]($data, $chemin);
    },
    
    REFMETHODE::AFFECTER->value => function(int $ref_id, int $promo_id): bool {
        global $model_tab;
        $chemin = CheminPage::DATA_JSON->value;
        $data = $model_tab[JSONMETHODE::JSONTOARRAY->value]($chemin);
        
        // Vérifier si la promotion existe et mettre à jour son référentiel
        if (isset($data['promotions'])) {
            $data['promotions'] = array_map(function($promo) use ($ref_id, $promo_id) {
                if ($promo['id'] === $promo_id) {
                    $promo['referenciel_id'] = $ref_id;
                }
                return $promo;
            }, $data['promotions']);
            
            return $model_tab[JSONMETHODE::ARRAYTOJSON->value]($data, $chemin);
        }
        
        return false;
    }
];

