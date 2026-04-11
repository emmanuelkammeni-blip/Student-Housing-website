<?php
session_start();
session_destroy();
header('Location: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : getenv('APP_URL') . '/'));
exit;
