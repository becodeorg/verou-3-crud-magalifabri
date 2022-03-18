<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/Classes/DatabaseManager.php';
require_once __DIR__ . '/Classes/CardRepository.php';

// create DatabaseManager
if (!empty(getenv("DATABASE_URL"))) {
    $databaseManager = new DatabaseManager('', '', '', '');
} else {
    require_once __DIR__ . '/config.php';

    $databaseManager = new DatabaseManager(
        $config['host'] ?? '',
        $config['user'] ?? '',
        $config['password'] ?? '',
        $config['dbname'] ?? ''
    );
}
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


function pokemonFoundInApi($pokemon)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://pokeapi.co/api/v2/pokemon/{$pokemon}");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $responseJSON = curl_exec($curl);
    curl_close($curl);
    $details = json_decode($responseJSON, true);

    if (empty($details)) {
        return false;
    }

    return true;
}

function validateCreateFormInput($pokemon, $level): string
{
    if (empty($pokemon)) {
        return 'no pokemon entered';
    } else if (!pokemonFoundInApi($pokemon)) {
        return 'pokemon not found in API';
    } else if (empty($level)) {
        return 'no level entered';
    } else if (!is_numeric($level)) {
        return 'level invalid';
    } else if ($level < 1 || $level > 100) {
        return 'level invalid';
    }

    return '';
}

function create($cardRepository)
{
    $pokemon = $_POST['pokemon'];
    $nickname = $_POST['nickname'];
    $level = $_POST['level'];

    $error = validateCreateFormInput($pokemon, $level);

    if (empty($error)) {
        $cardRepository->create(
            [
                'pokemon' => $pokemon,
                'nickname' => $nickname,
                'level' => $level
            ]
        );
    }

    $_SESSION['inputValidationError'] = $error;

    header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
    exit();
}

function validateUpdateFormInput($pokemon, $level, $id): string
{
    if (empty($pokemon)) {
        return 'no pokemon entered';
    } else if (!pokemonFoundInApi($pokemon)) {
        return 'pokemon not found in API';
    } else if (empty($level)) {
        return 'no level entered';
    } else if (!is_numeric($level)) {
        return 'level invalid';
    } else if ($level < 1 || $level > 100) {
        return 'level invalid';
    } else if (empty($id)) {
        return 'no id entered';
    } else if (!is_numeric($id)) {
        return 'invalid id';
    } else if ($id < 1) {
        return 'invalid id';
    }

    return '';
}

function update($cardRepository)
{
    $id = $_POST['id'];
    $pokemon = $_POST['pokemon'];
    $nickname = $_POST['nickname'];
    $level = $_POST['level'];

    $error = validateUpdateFormInput($pokemon, $level, $id);

    if (empty($error)) {
        $cardRepository->update(
            [
                'id' => $id,
                'pokemon' => $pokemon,
                'nickname' => $nickname,
                'level' => $level
            ]
        );
    }

    $_SESSION['inputValidationError'] = $error;

    header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
    exit();
}


function delete($cardRepository)
{
    $cardRepository->delete($_POST['id']);

    header("Location: {$_SERVER['REQUEST_URI']}", true, 303);
    exit();
}


function getFlavourText($details): string
{
    $speciesUrl = $details['species']['url'];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $speciesUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $responseJSON = curl_exec($curl);
    curl_close($curl);
    $details = json_decode($responseJSON, true);

    $i = 0;
    while (true) {
        $language = $details['flavor_text_entries'][$i]['language']['name'];
        if ($language === 'en') {
            return $details['flavor_text_entries'][$i]['flavor_text'];
        }
        $i++;
    }
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

    $details['last_update'] = $dbData['last_update'] ?? '';
    $details['nickname'] = $dbData['nickname'];
    $details['level'] = $dbData['level'];
    $details['flavourText'] = getFlavourText($details);

    return $details;
}
