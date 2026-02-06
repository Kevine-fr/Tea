<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class DemoService
{
    /**
     * Peut réussir ou échouer aléatoirement
     */
    public function randomFail(): JsonResponse
    {
        // Liste de tous les status codes avec leurs messages
        $statusCodes = [
            // 2xx - Success
            200 => ['success' => true, 'message' => '✅ 200 OK - Succès standard'],
            201 => ['success' => true, 'message' => '✅ 201 Created - Ressource créée'],
            204 => ['success' => true, 'message' => '✅ 204 No Content - Succès sans contenu'],
            
            // 3xx - Redirections
            301 => ['success' => true, 'message' => '🔄 301 Moved Permanently - Redirection permanente'],
            302 => ['success' => true, 'message' => '🔄 302 Found - Redirection temporaire'],
            304 => ['success' => true, 'message' => '🔄 304 Not Modified - Contenu non modifié'],
            
            // 4xx - Client Errors
            400 => ['success' => false, 'message' => '❌ 400 Bad Request - Requête invalide'],
            401 => ['success' => false, 'message' => '🔒 401 Unauthorized - Non authentifié'],
            403 => ['success' => false, 'message' => '🚫 403 Forbidden - Accès interdit'],
            404 => ['success' => false, 'message' => '🔍 404 Not Found - Ressource introuvable'],
            409 => ['success' => false, 'message' => '⚠️ 409 Conflict - Conflit de ressource'],
            422 => ['success' => false, 'message' => '📝 422 Unprocessable Entity - Données invalides'],
            429 => ['success' => false, 'message' => '⏱️ 429 Too Many Requests - Trop de requêtes'],
            
            // 5xx - Server Errors
            500 => ['success' => false, 'message' => '💥 500 Internal Server Error - Erreur serveur'],
            501 => ['success' => false, 'message' => '🚧 501 Not Implemented - Non implémenté'],
            502 => ['success' => false, 'message' => '🔌 502 Bad Gateway - Passerelle invalide'],
            503 => ['success' => false, 'message' => '🔧 503 Service Unavailable - Service indisponible'],
            504 => ['success' => false, 'message' => '⏰ 504 Gateway Timeout - Délai dépassé'],
        ];
        
        // Choisir un status code aléatoire
        $randomStatus = array_rand($statusCodes);
        $response = $statusCodes[$randomStatus];
        
        return response()->json($response, $randomStatus);
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