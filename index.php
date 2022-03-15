<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'config.php';
require_once 'classes/DatabaseManager.php';
require_once 'classes/CardRepository.php';

// create DatabaseManager
$databaseManager = new DatabaseManager(
    $config['host'],
    $config['user'],
    $config['password'],
    $config['dbname']
);
$databaseManager->connect();

// get cards
$cardRepository = new CardRepository($databaseManager);
$cards = $cardRepository->get();

// get action
$action = $_GET['action'] ?? null;

switch ($action) {
    case 'create':
        create();
        break;

    default:
        overview($cards);
        break;
}

function overview($cards)
{
    // Load your view
    // Tip: you can load this dynamically and based on a variable, if you want to load another view
    require 'overview.php';
}

function create()
{
    // TODO: provide the create logic
}
