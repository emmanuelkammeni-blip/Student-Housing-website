<?php
session_start();
session_destroy();
require_once __DIR__ . '/config/config.php';
header('Location: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : APP_URL . '/'));
exit;
