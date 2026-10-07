<?php
// funktioner.php

function createPage($db, $titel, $content, $lang) {
    $db->execute_query("INSERT INTO page (titel, tag_id, sprak) VALUES (?, 1, ?)", [$titel, $lang]);
    $newId = $db->insert_id;
    $db->execute_query("INSERT INTO page_content (page_id, content_json) VALUES (?, ?)", [$newId, $content]);
    return $newId;
}

function updatePage($db, $id, $titel, $content) {
    if ($titel) {
        $db->execute_query("UPDATE page SET titel = ? WHERE id = ?", [$titel, $id]);
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