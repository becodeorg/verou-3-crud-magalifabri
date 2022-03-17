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
$cards = $cardRepository->get($_GET['from'] ?? 1, $_GET['to'] ?? 100);

// get action
$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($action) {
    case 'create':
        create($cardRepository);
        break;

    case 'update':
        update($cardRepository);
        break;

    case 'delete':
        delete($cardRepository);
        break;

    case 'showDetails':
        $details = getDetails($cardRepository);
        overview($cards, $details);
        break;

    default:
        overview($cards);
        break;
}


function overview($cards, $details = [])
{
    // Load your view
    // Tip: you can load this dynamically and based on a variable, if you want to load another view
    require 'overview.php';
}


function pokemonExists($pokemonName)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://pokeapi.co/api/v2/pokemon/{$_POST['pokemon']}");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $responseJSON = curl_exec($curl);
    curl_close($curl);
    $details = json_decode($responseJSON, true);

    if (empty($details)) {
        return false;
    }

    return true;
}

function validateCreateFormInput()
{
    if (empty($_POST['pokemon'])) {
        return false;
    } else if (!pokemonExists($_POST['pokemon'])) {
        return false;
    } else if (empty($_POST['level'])) {
        return false;
    } else if (!is_numeric($_POST['level'])) {
        return false;
    } else if ($_POST['level'] < 1 || $_POST['level'] > 100) {
        return false;
    }

    return true;
}

function create($cardRepository)
{
    if (validateCreateFormInput()) {
        $cardRepository->create(
            [
                'pokemon' => $_POST['pokemon'],
                'nickname' => $_POST['nickname'],
                'level' => $_POST['level']
            ]
        );
    }

    // prevent form resubmission on page reload
    header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
    exit();
}


function validateUpdateFormInput()
{
    if (empty($_POST['pokemon'])) {
        return false;
    } else if (!pokemonExists($_POST['pokemon'])) {
        return false;
    } else if (empty($_POST['level'])) {
        return false;
    } else if (!is_numeric($_POST['level'])) {
        return false;
    } else if ($_POST['level'] < 1 || $_POST['level'] > 100) {
        return false;
    } else if (empty($_POST['id'])) {
        return false;
    } else if (!is_numeric($_POST['id'])) {
        return false;
    } else if ($_POST['id'] < 1) {
        return false;
    }

    return true;
}

function update($cardRepository)
{
    if (validateUpdateFormInput()) {
        $cardRepository->update(
            [
                'id' => $_POST['id'],
                'pokemon' => $_POST['pokemon'],
                'nickname' => $_POST['nickname'],
                'level' => $_POST['level']
            ]
        );
    }

    // prevent form resubmission on page reload
    header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
    exit();
}


function delete($cardRepository)
{
    $cardRepository->delete($_POST['id']);

    // prevent form resubmission on page reload
    header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
    exit();
}


function getDetails($cardRepository): array
{
    $dbData = $cardRepository->find($_GET['id']);
    $pokemon = strtolower($dbData['pokemon']);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://pokeapi.co/api/v2/pokemon/{$pokemon}");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $responseJSON = curl_exec($curl);
    curl_close($curl);
    $details = json_decode($responseJSON, true);

    $details['lastUpdate'] = $dbData['lastUpdate'] ?? '';
    $details['nickname'] = $dbData['nickname'];
    $details['level'] = $dbData['level'];
    $details['description'] = $dbData['description'] ?? '';

    return $details;
}
