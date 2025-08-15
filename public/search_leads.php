<?php

declare(strict_types=1);

use App\Models\Lead;
use Prembly\Crm\DB\DB_Common_Functions;
use Prembly\Crm\Utils\Common_Utilities;
use App\Services\LeadSearchService;

require __DIR__ . '/../autoload.php';
$config = require __DIR__ . '/../config/db.php';

// CSRF session start
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token']; // Generate CSRF token to be used in forms

const PAGE_SIZE = 10;

$pdo = new PDO(
    $config['dsn'], 
    $config['user'], 
    $config['pass'], 
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$commFunc = new Lead($pdo);
$util = new Common_Utilities();

$service  = new LeadSearchService($commFunc, $util, PAGE_SIZE);

$agentId = $_SESSION['admin_id'] ?? 0;
$ownerId = $_SESSION['owner_id'] ?? 0;

$params = $_GET;
$result = $service->search($_REQUEST, $ownerId, $agentId);
$rows = $result['rows'];
$pagination = $result['pagination'];

$title = "Search Leads";

include_once __DIR__ . "/../app/views/layout.php";
