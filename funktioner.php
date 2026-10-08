<?php
function createPage($db, $titel, $content, $lang, $tag) {
    $tagId = 1;
    if ($tag) {
        $rad = $db->execute_query("SELECT id FROM tag WHERE namn = ?", [$tag])->fetch_assoc();
        if ($rad) {
            $tagId = $rad['id'];
        }
    }
    $db->execute_query("INSERT INTO page (titel, tag_id, sprak) VALUES (?, ?, ?)", [$titel, $tagId, $lang]);
    $newId = $db->insert_id;
    $db->execute_query("INSERT INTO page_content (page_id, content_json) VALUES (?, ?)", [$newId, $content]);
    return $newId;
}

function updatePage($db, $id, $titel, $content, $tag) {
    if ($titel) {
        $db->execute_query("UPDATE page SET titel = ? WHERE id = ?", [$titel, $id]);
    }
    if ($tag) {
        // Okänd tagg = behåll den gamla
        $rad = $db->execute_query("SELECT id FROM tag WHERE namn = ?", [$tag])->fetch_assoc();
        if ($rad) {
            $db->execute_query("UPDATE page SET tag_id = ? WHERE id = ?", [$rad['id'], $id]);
        }
    }
    if ($content) {
        $db->execute_query("UPDATE page_content SET content_json = ? WHERE page_id = ?", [$content, $id]);
    }
}

function deletePage($db, $id) {
    $db->execute_query("DELETE FROM page_content WHERE page_id = ?", [$id]);
    $db->execute_query("DELETE FROM page WHERE id = ?", [$id]);
    // true om en sida faktiskt raderades
    return $db->affected_rows > 0;
}

// Byter ut alla {"sv": ..., "en": ..., "de": ...} mot texten på valt språk, även i listor
function translate($value, $lang) {
    if (!is_array($value)) {
        return $value;
    }
    // Det är ett språkobjekt om den inte är tom och alla nycklar är sv, en eller de
    $arLangObjekt = count($value) > 0;
    foreach ($value as $nyckel => $v) {
        if ($nyckel !== 'sv' && $nyckel !== 'en' && $nyckel !== 'de') {
            $arLangObjekt = false;
        }
    }
    if ($arLangObjekt) {
        if (isset($value[$lang])) {
            return $value[$lang];
        }
        return null;
    }
    // Annars: översätt allt som ligger inuti
    $resultat = [];
    foreach ($value as $nyckel => $v) {
        $resultat[$nyckel] = translate($v, $lang);
    }
    return $resultat;
}

// Lägger sidans JSON-innehåll (på valt språk) bredvid id, titel och tag
function formatPage($row, $lang) {
    $content = [];
    if ($row['content_json']) {
        $content = json_decode($row['content_json'], true);
    }
    unset($row['content_json']);

    $content = translate($content, $lang);
    foreach ($content as $nyckel => $v) {
        // id, titel och tag från databasen ska inte skrivas över
        if (!array_key_exists($nyckel, $row)) {
            $row[$nyckel] = $v;
        }
    }
    return $row;
}

// Lite gemini :( de va för svårt för lilla Edwin och tänka ut själv
function pageContains($page, $ord) {
    foreach ($page as $varde) {
        // Om värdet är en lista eller ett objekt: leta inuti det också
        if (is_array($varde) && pageContains($varde, $ord)) {
            return true;
        }
        if (is_string($varde) && mb_stripos($varde, $ord) !== false) {
            return true;
        }
    }
    return false;
}
// Skickar ett JSON-svar och avslutar
function respond($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}
