<?php
/**
 * Translation API - Sistema multi-servicio gratuito
 * Endpoint: /api/translate.php
 */

// Manejo de errores robusto: SIEMPRE devolver JSON
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    echo json_encode([
        'error' => 'PHP Error',
        'message' => $errstr,
        'file' => basename($errfile),
        'line' => $errline
    ]);
    exit;
});

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Fatal Error',
            'message' => $error['message'],
            'file' => basename($error['file']),
            'line' => $error['line']
        ]);
    }
});

// Permitir CORS para AJAX
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

// Detectar si cURL está disponible
$useCurl = function_exists('curl_init');

// Servicios de traducción gratuitos (en orden de preferencia)
$TRANSLATION_SERVICES = [
    [
        'name' => 'MyMemory',
        'url' => 'https://api.mymemory.translated.net/get',
        'type' => 'mymemory'
    ],
    [
        'name' => 'LibreTranslate.de',
        'url' => 'https://libretranslate.de/translate',
        'type' => 'libretranslate'
    ],
    [
        'name' => 'ArgoOpenTech',
        'url' => 'https://translate.argosopentech.com/translate',
        'type' => 'libretranslate'
    ]
];

// Obtener datos de la petición
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['text']) || !isset($input['target'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$text = trim($input['text']);
$source = $input['source'] ?? 'auto';
$target = $input['target'];

// Validar idioma objetivo
$validLanguages = ['es', 'en', 'fr', 'de', 'it', 'pt', 'ca', 'ru', 'ja', 'zh'];
if (!in_array($target, $validLanguages)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid target language']);
    exit;
}

// Si el texto es muy corto o vacío, devolverlo sin traducir
if (strlen($text) < 2) {
    echo json_encode([
        'translatedText' => $text,
        'detectedLanguage' => ['language' => $source],
    ]);
    exit;
}

// Si el idioma fuente y destino son iguales, no traducir
if ($source === $target) {
    echo json_encode([
        'translatedText' => $text,
        'detectedLanguage' => ['language' => $source],
    ]);
    exit;
}

// Función para traducir con LibreTranslate
function translateLibreTranslate($url, $text, $source, $target, $useCurl = true) {
    try {
        $data = json_encode([
            'q' => $text,
            'source' => $source,
            'target' => $target,
            'format' => 'text'
        ]);
        
        if ($useCurl) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Para evitar problemas con SSL
            
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        } else {
            // Usar file_get_contents como alternativa
            $options = [
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/json',
                    'content' => $data,
                    'timeout' => 5,
                    'ignore_errors' => true
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ];
            $context = stream_context_create($options);
            $result = @file_get_contents($url, false, $context);
            $httpCode = 200; // Asumimos 200 si no hay error
            if ($result === false) {
                return false;
            }
        }
        
        if ($result && $httpCode === 200) {
            $response = json_decode($result, true);
            if (isset($response['translatedText'])) {
                return $response['translatedText'];
            }
        }
    } catch (Exception $e) {
        // Silenciar errores y continuar con el siguiente servicio
    }
    
    return false;
}

// Función para traducir con MyMemory
function translateMyMemory($url, $text, $source, $target, $useCurl = true) {
    try {
        $langpair = ($source === 'auto' ? 'es' : $source) . '|' . $target;
        $query = $url . '?q=' . urlencode($text) . '&langpair=' . urlencode($langpair);
        
        if ($useCurl) {
            $ch = curl_init($query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        } else {
            // Usar file_get_contents como alternativa
            $options = [
                'http' => [
                    'method' => 'GET',
                    'timeout' => 5,
                    'ignore_errors' => true
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ];
            $context = stream_context_create($options);
            $result = @file_get_contents($query, false, $context);
            $httpCode = 200;
            if ($result === false) {
                return false;
            }
        }
        
        if ($result && $httpCode === 200) {
            $response = json_decode($result, true);
            
            // Verificar responseStatus de MyMemory
            if (isset($response['responseStatus']) && $response['responseStatus'] === 200) {
                if (isset($response['responseData']['translatedText'])) {
                    return $response['responseData']['translatedText'];
                }
            }
        }
    } catch (Exception $e) {
        // Silenciar errores y continuar con el siguiente servicio
    }
    
    return false;
}

// Intentar traducir con cada servicio hasta que uno funcione
$translatedText = null;
$usedService = null;

foreach ($TRANSLATION_SERVICES as $service) {
    if ($service['type'] === 'libretranslate') {
        $translatedText = translateLibreTranslate($service['url'], $text, $source, $target, $useCurl);
    } elseif ($service['type'] === 'mymemory') {
        $translatedText = translateMyMemory($service['url'], $text, $source, $target, $useCurl);
    }
    
    if ($translatedText) {
        $usedService = $service['name'];
        break;
    }
}

// Devolver resultado
if ($translatedText) {
    echo json_encode([
        'translatedText' => $translatedText,
        'detectedLanguage' => ['language' => $source],
        'service' => $usedService,
        'method' => $useCurl ? 'cURL' : 'file_get_contents'
    ]);
} else {
    // Si todos los servicios fallan, devolver el texto original pero marcar error
    echo json_encode([
        'translatedText' => $text,
        'error' => 'All translation services unavailable',
        'detectedLanguage' => ['language' => $source],
    ]);
}
