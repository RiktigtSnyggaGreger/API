<?php
header('Content-Type: application/json; charset=utf-8');

// Skickar ett JSON-svar och avslutar
function respond($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

//koppla till databasen
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/funktioner.php';

// Kollar ifall man skickar med ett ID
$action  = $_GET['action'] ?? null;
$id      = $_GET['id'] ?? null;
$tag     = $_GET['tag'] ?? null;
$lang    = $_GET['lang'] ?? 'sv';
$titel   = $_GET['titel'] ?? null;
$content = $_GET['content'] ?? null;

// Bara tillåtna språk, annars kan man skicka in egen SQL via lang
if (!in_array($lang, ['sv', 'en', 'de'], true)) {
    respond(['error' => 'Språket stöds inte'], 400);
}

// ID måste vara ett heltal
if ($id !== null && !ctype_digit($id)) {
    respond(['error' => 'ID måste vara ett nummer'], 400);
}


// Timo sa att switch va bra, så nu har jag switch
switch ($action) {
    case 'create':
        if (!$titel || !$content) {
            respond(['error' => 'Titel och content krävs'], 400);
        }
        if (!json_validate($content)) {
            respond(['error' => 'Content måste vara giltig JSON'], 400);
        }
        $newId = createPage($db, $titel, $content, $lang);
        respond(['success' => true, 'id' => $newId], 201);
    case 'update':
        if (!$id) {
            respond(['error' => 'ID krävs för att uppdatera'], 400);
        }
        if ($content && !json_validate($content)) {
            respond(['error' => 'Content måste vara giltig JSON'], 400);
        }
        updatePage($db, $id, $titel, $content);
        respond(['success' => true, 'message' => "Sida $id har uppdaterats"]);
    case 'delete':
        if (!$id) {
            respond(['error' => 'ID krävs för att radera'], 400);
        }
        if (!deletePage($db, $id)) {
            respond(['error' => 'Sidan hittades inte'], 404);
        }
        respond(['success' => true, 'message' => "Sida $id har raderats"]);
    case null:
        break; // ingen action = hämta sidor nedan
    default:
        respond(['error' => 'Okänd action'], 400);
}



// Hämtar sidan, taggen och JSON-innehållet på samma gång
// formatPage() packar upp JSON:en och väljer rätt språk
// I LOVE MARIADB!! <3
if ($id) {
    // 1. Hämta sida via ID
    $sql = "SELECT 
                p.id, 
                p.titel, 
                t.namn AS tag, 
                pc.content_json
            FROM page p
            LEFT JOIN tag t ON p.tag_id = t.id
            LEFT JOIN page_content pc ON p.id = pc.page_id
            WHERE p.id = ?";

    $data = $db->execute_query($sql, [$id])->fetch_assoc();
    $data = $data ? formatPage($data, $lang) : null;
} elseif ($tag) {
    // 2. Hämta FÖRSTA sidan som har denna tagg direkt
    $sql = "SELECT 
                p.id, 
                p.titel, 
                t.namn AS tag, 
                pc.content_json
            FROM page p
            INNER JOIN tag t ON p.tag_id = t.id
            LEFT JOIN page_content pc ON p.id = pc.page_id
            WHERE t.namn = ?";

    // fetch_assoc() ger ett platt enskilt objekt direkt
    $data = $db->execute_query($sql, [$tag])->fetch_assoc();
    $data = $data ? formatPage($data, $lang) : null;
} else {
    // 3. Hämta alla sidor som en lista
    $sql = "SELECT 
                p.id, 
                p.titel, 
                t.namn AS tag, 
                pc.content_json
            FROM page p
            LEFT JOIN tag t ON p.tag_id = t.id
            LEFT JOIN page_content pc ON p.id = pc.page_id";

    $data = $db->execute_query($sql)->fetch_all(MYSQLI_ASSOC);
    $data = array_map(fn($row) => formatPage($row, $lang), $data);
}

// Felhantering
if (!$data) {
    respond(['error' => 'Sidan hittades inte'], 404);
}

respond($data);