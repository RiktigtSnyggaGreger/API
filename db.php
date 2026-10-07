<?php
// Om anslutningen misslyckas kastar mysqli ett undantag som fångas i api.php
$db = new mysqli("db", "db", "db", "min_api");
$db->set_charset('utf8mb4');
