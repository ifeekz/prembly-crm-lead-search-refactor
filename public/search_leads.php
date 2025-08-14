<?php

declare(strict_types=1);

use App\Service\LeadSearchService;

require __DIR__ . '/../vendor_autoload.php';
require __DIR__ . '/../config/db.php';

// CSRF session start
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

const PAGE_SIZE = 10; // Define a default page size

$pdo = new PDO($config['dsn'], $config['user'], $config['pass']);

// $pdo = new PDO(
//     "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
//     $dbUser,
//     $dbPass,
//     [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
// );

$service  = new LeadSearchService($commFunc, $util, PAGE_SIZE);

$agentId = $_SESSION['admin_id'];
$ownerId = $_SESSION['owner_id'];

$params = $_GET;
$result = $service->search($_REQUEST, $ownerId, $agentId);
$rows = $result['rows'];
// $pagination = $result['pagination'];
$total = $result['totalCount'];

$title = "Search Leads";

// include_once __DIR__ . "/../app/views/search-leads-view.php";
include_once __DIR__ . "/../app/views/layout.php";
