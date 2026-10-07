<?php
// funktioner.php

// Okänd eller ingen tagg = Nyheter (id 1)
function createPage($db, $titel, $content, $lang, $tag) {
    $db->execute_query("INSERT INTO page (titel, tag_id, sprak) VALUES (?, COALESCE((SELECT id FROM tag WHERE namn = ?), 1), ?)", [$titel, $tag, $lang]);
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
        $db->execute_query("UPDATE page SET tag_id = COALESCE((SELECT id FROM tag WHERE namn = ?), tag_id) WHERE id = ?", [$tag, $id]);
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
    $arLangObjekt = $value && !array_is_list($value) && !array_diff(array_keys($value), ['sv', 'en', 'de']);
    if ($arLangObjekt) {
        return $value[$lang] ?? null;
    }
    return array_map(fn($v) => translate($v, $lang), $value);
}

// Lägger sidans JSON-innehåll (på valt språk) bredvid id, titel och tag
function formatPage($row, $lang) {
    $content = json_decode($row['content_json'] ?? '', true) ?? [];
    unset($row['content_json']);
    return $row + translate($content, $lang);
}