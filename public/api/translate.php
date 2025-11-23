<?php
/**
 * Translation API - Sistema multi-servicio gratuito
 * Endpoint: /api/translate.php
 */

// Permitir CORS para AJAX
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

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
function translateLibreTranslate($url, $text, $source, $target) {
    $data = [
        'q' => $text,
        'source' => $source,
        'target' => $target,
        'format' => 'text'
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($result && $httpCode === 200) {
        $response = json_decode($result, true);
        if (isset($response['translatedText'])) {
            return $response['translatedText'];
        }
    }
    
    return false;
}

// Función para traducir con MyMemory
function translateMyMemory($url, $text, $source, $target) {
    $langpair = ($source === 'auto' ? 'es' : $source) . '|' . $target;
    $query = $url . '?q=' . urlencode($text) . '&langpair=' . urlencode($langpair);
    
    $ch = curl_init($query);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($result && $httpCode === 200) {
        $response = json_decode($result, true);
        if (isset($response['responseData']['translatedText'])) {
            return $response['responseData']['translatedText'];
        }
    }
    
    return false;
}

// Intentar traducir con cada servicio hasta que uno funcione
$translatedText = null;
$usedService = null;

foreach ($TRANSLATION_SERVICES as $service) {
    if ($service['type'] === 'libretranslate') {
        $translatedText = translateLibreTranslate($service['url'], $text, $source, $target);
    } elseif ($service['type'] === 'mymemory') {
        $translatedText = translateMyMemory($service['url'], $text, $source, $target);
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
        'service' => $usedService
    ]);
} else {
    // Si todos los servicios fallan, devolver el texto original
    echo json_encode([
        'translatedText' => $text,
        'error' => 'All translation services unavailable',
        'detectedLanguage' => ['language' => $source],
    ]);
}
