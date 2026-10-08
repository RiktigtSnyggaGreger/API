<?php
$config = require __DIR__ . '/config.php';
$db = new mysqli($config['host'], $config['user'], $config['pass'], $config['name']);
$db->set_charset('utf8mb4');
