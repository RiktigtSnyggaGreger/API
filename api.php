<?php
header('Content-Type: application/json; charset=utf-8');
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

// Timo sa att switch va bra, så nu har jag switch
switch ($action) {
    case 'create':
        if (!$titel || !$content) {
            http_response_code(400);
            echo json_encode(['error' => 'Titel och content krävs']);
            exit;
        }
        $newId = createPage($db, $titel, $content, $lang);
        echo json_encode(['success' => true, 'id' => $newId]);
        exit;
    case 'update':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID krävs för att uppdatera']);
            exit;
        }
        updatePage($db, $id, $titel, $content);
        echo json_encode(['success' => true, 'message' => "Sida $id har uppdaterats"]);
        exit;
    case 'delete':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID krävs för att radera']);
            exit;
        }
        deletePage($db, $id);
        echo json_encode(['success' => true, 'message' => "Sida $id har raderats"]);
        exit;
}



// Hämtar sidan, taggen och JSON-innehållet på samma gång
// I LOVE MARIADB!! <3
if ($id) {
    // 1. Hämta sida via ID
    $sql = "SELECT 
                p.id, 
                p.titel, 
                t.namn AS tag, 
                JSON_VALUE(pc.content_json, '$.rubrik.$lang') AS rubrik,
                JSON_VALUE(pc.content_json, '$.hero_bild') AS hero_bild
            FROM page p
            LEFT JOIN tag t ON p.tag_id = t.id
            LEFT JOIN page_content pc ON p.id = pc.page_id
            WHERE p.id = ?";

    $data = $db->execute_query($sql, [$id])->fetch_assoc();
} elseif ($tag) {
    // 2. Hämta FÖRSTA sidan som har denna tagg direkt
    $sql = "SELECT 
                p.id, 
                p.titel, 
                t.namn AS tag, 
                JSON_VALUE(pc.content_json, '$.rubrik.$lang') AS rubrik,
                JSON_VALUE(pc.content_json, '$.hero_bild') AS hero_bild
            FROM page p
            INNER JOIN tag t ON p.tag_id = t.id
            LEFT JOIN page_content pc ON p.id = pc.page_id
            WHERE t.namn = ?";

    // fetch_assoc() ger ett platt enskilt objekt direkt
    $data = $db->execute_query($sql, [$tag])->fetch_assoc();
} else {
    // 3. Hämta alla sidor som en lista
    $sql = "SELECT 
                p.id, 
                p.titel, 
                t.namn AS tag, 
                JSON_VALUE(pc.content_json, '$.rubrik.$lang') AS rubrik,
                JSON_VALUE(pc.content_json, '$.hero_bild') AS hero_bild
            FROM page p
            LEFT JOIN tag t ON p.tag_id = t.id
            LEFT JOIN page_content pc ON p.id = pc.page_id";

    $data = $db->execute_query($sql)->fetch_all(MYSQLI_ASSOC);
}

// Felhantering
if (!$data) {
    http_response_code(404);
    echo json_encode(['error' => 'Sidan hittades inte']);
    exit;
}

// JSON_UNESCAPED_UNICODE = tillåter Å,Ä och Ö
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);