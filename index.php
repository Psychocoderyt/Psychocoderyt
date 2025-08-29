<?php
session_start();

// Simple routing
$page = $_GET['page'] ?? 'phone';

switch($page) {
    case 'login':
        include 'views/login.php';
        break;
    case 'phone':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?page=login');
            exit;
        }
        include 'views/phone.php';
        break;
    case 'contacts':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?page=login');
            exit;
        }
        include 'views/contacts.php';
        break;
    case 'history':
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?page=login');
            exit;
        }
        include 'views/history.php';
        break;
    default:
        include 'views/phone.php';
        break;
}
?>