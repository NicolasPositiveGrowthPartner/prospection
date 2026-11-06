<?php
/**
 * Relais API Sirene INSEE - la clé API reste sur le serveur.
 * Placez ce fichier dans le même répertoire que prospection.html.
 *
 * ETAPE OBLIGATOIRE : remplacez la clé ci-dessous par votre clé INSEE
 * (portail-api.insee.fr > Mes applications > Souscriptions).
 */
$API_KEY = '58d5ea3e-5be3-440b-95ea-3e5be3a40b34';

// --- Paramètres autorisés (liste blanche, pour éviter tout abus du relais) ---
$q      = isset($_GET['q'])      ? $_GET['q']      : '';
$nombre = isset($_GET['nombre']) ? intval($_GET['nombre']) : 20;
$page   = isset($_GET['page'])   ? intval($_GET['page'])   : 1;
$date   = (isset($_GET['date']) && $_GET['date'] === '2999-12-31') ? '2999-12-31' : '2999-12-31';

if ($nombre < 1 || $nombre > 100) { $nombre = 20; }
if ($page < 1) { $page = 1; }

// Champs strictement limités : pour garder le relais sûr, on ne transmet pas
// de paramètre champs libre ; l'API renvoie tous les champs standards de l'app.

$url = 'https://api.insee.fr/api-sirene/3.11/siret?'
     . 'nombre=' . $nombre . '&page=' . $page . '&date=' . $date;
if ($q !== '') { $url .= '&q=' . rawurlencode($q); }

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_HTTPHEADER     => [
        'Accept: application/json',
        'X-INSEE-Api-Key-Integration: ' . $API_KEY,
    ],
]);
$body     = curl_exec($ch);
$httpCode = curl_getResponseCodeSafe($ch);
$curlErr  = curl_error($ch);
curl_close($ch);

if ($body === false) {
    http_response_code(502);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['header' => ['statut' => 502, 'message' => 'Erreur relais INSEE : ' . $curlErr]]);
    exit;
}

http_response_code($httpCode ?: 200);
header('Content-Type: application/json; charset=utf-8');
echo $body;

function curl_getResponseCodeSafe($ch) {
    $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    return $code ? (int)$code : 0;
}
