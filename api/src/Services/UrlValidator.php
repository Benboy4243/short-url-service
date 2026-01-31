<?php
class UrlValidator
{
    /**
     * Valider si un URL en fonction de son format
     * 
     * @param string
     * @param int
     * @return array
     */
    public static function validateUrl(string $url, int $timeout = 10): array
    {
        $url = self::normalizeUrl($url);
        
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return [
                'valid' => false,
                'statusCode' => null,
                'message' => 'Format Invalide'
            ];
        }

        $ch = curl_init($url);
        
        if ($ch === false) {
            return [
                'valid' => false,
                'statusCode' => null,
                'message' => 'URL Invalide'
            ];
        }

        //cURL
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_NOBODY => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'Short-URL-Validator/1.0'
        ]);

        curl_exec($ch);
        
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);

        if ($error) {
            return [
                'valid' => false,
                'statusCode' => $statusCode ?: null,
                'message' => 'URL Invalide'
            ];
        }

        // Fallback pour les sites qui bloquent HEAD 403 405
        if ($statusCode === 403 || $statusCode === 405) {
            $statusCode = self::tryGetRequest($url, $timeout);
        }

        $isValid = $statusCode >= 200 && $statusCode < 400;

        return [
            'valid' => $isValid,
            'statusCode' => $statusCode,
            'message' => self::getStatusMessage($statusCode)
        ];

    }
    /**
     * Retourne un message pour un code HTTP
     * 
     * @param int
     * @return string
     */
    private static function getStatusMessage(int $statusCode): string
    {
        return match(true) {
            $statusCode === 0 => 'Serveur injoignable',
            $statusCode >= 200 && $statusCode < 300 => 'URL valide et accessible',
            $statusCode >= 300 && $statusCode < 400 => 'URL valide (redirection)',
            $statusCode === 400 => 'Requête invalide',
            $statusCode === 401 => 'Authentification requise',
            $statusCode === 403 => 'Accès interdit',
            $statusCode === 404 => 'URL non trouvée',
            $statusCode >= 400 && $statusCode < 500 => 'Erreur client (code ' . $statusCode . ')',
            $statusCode >= 500 && $statusCode < 600 => 'Erreur serveur (code ' . $statusCode . ')',
            default => 'Code de statut inconnu: ' . $statusCode
        };
    }
    
}