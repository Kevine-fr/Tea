<?php

namespace App\Services;

class DemoService
{
    /**
     * Peut réussir ou échouer aléatoirement
     */
    public function randomFail(): array
    {
        if (rand(0, 1) === 0) {
            return [
                'success' => false,
                'message' => 'Erreur générée volontairement'
            ];
        }

        return [
            'success' => true,
            'message' => 'Succès 🎉'
        ];
    }

    /**
     * Réussit toujours
     */
    public function alwaysOk(): array
    {
        return [
            'success' => true,
            'message' => 'Tout fonctionne parfaitement'
        ];
    }
}
