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

        
    }

    
}