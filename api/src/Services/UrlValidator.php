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

    
}