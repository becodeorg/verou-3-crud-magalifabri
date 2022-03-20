<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/Classes/DatabaseManager.php';
require_once __DIR__ . '/Classes/CardRepository.php';

// get db config

// local db
// require_once __DIR__ . '/config_localhost.php';

// heroku db + heroku env vars
if (!empty(getenv("DATABASE_URL"))) {
    $dbParams = parse_url(getenv("DATABASE_URL"));

    $config = [
        'scheme' => 'pgsql',
        'host' => $dbParams['host'],
        'port' => $dbParams['port'],
        'user' => $dbParams['user'],
        'pass' => $dbParams['pass'],
        'dbname' => ltrim($dbParams["path"], "/"),
    ];
}
// heroku db + local env vars
else {
    require_once __DIR__ . '/config_heroku.php';
}

// create DatabaseManager
$databaseManager = new DatabaseManager(
    $config['scheme'],
    $config['host'],
    $config['port'],
    $config['user'],
    $config['pass'],
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
    require 'overview.php';
}


function getDataFromPokeApi($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $responseJSON = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($responseJSON, true);

    return $data;
}

function pokemonFoundInApi($pokemon)
{
    $data = getDataFromPokeApi("https://pokeapi.co/api/v2/pokemon/{$pokemon}");

    if (empty($data)) {
        return false;
    }

    return true;
}

function validateFormInput($pokemon, $level, $id = NULL): string
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
    } else if ($id) {
        if (empty($id)) {
            return 'no id entered';
        } else if (!is_numeric($id)) {
            return 'invalid id';
        } else if ($id < 1) {
            return 'invalid id';
        }
    }

    return '';
}

function create($cardRepository)
{
    $pokemon = $_POST['pokemon'];
    $nickname = $_POST['nickname'];
    $level = $_POST['level'];

    $error = validateFormInput($pokemon, $level);

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


function update($cardRepository)
{
    $id = $_POST['id'];
    $pokemon = $_POST['pokemon'];
    $nickname = $_POST['nickname'];
    $level = $_POST['level'];

    $error = validateFormInput($pokemon, $level, $id);

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


function getEvolutions(array $evolvesTo, string $dashes, string &$evolutions)
{
    $dashes .= '—';

    foreach ($evolvesTo as $evolution) {
        $matches = [];
        preg_match('/\/(\d+)\/$/', $evolution['species']['url'], $matches);
        $pokemonId =  $matches[1];

        $evolutions .= "<div class='evolution'><p>{$dashes}▹ {$evolution['species']['name']}</p><img src='https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$pokemonId}.png'></div>";
        if (!empty($evolution['evolves_to'])) {
            getEvolutions($evolution['evolves_to'], $dashes, $evolutions);
        }
    }
}

function getEvolutionChain($evolutionChainUrl): string
{
    $evolutionChainData = getDataFromPokeApi($evolutionChainUrl);

    if (empty($evolutionChainData['chain']['evolves_to'])) {
        return '';
    }

    $matches = [];
    preg_match('/\/(\d+)\/$/', $evolutionChainData['chain']['species']['url'], $matches);
    $pokemonId =  $matches[1];

    $evolutions = "
        <div class='evolution'>
            <p>▹ {$evolutionChainData['chain']['species']['name']}</p>
            <img src='https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$pokemonId}.png'>
        </div>";
    getEvolutions($evolutionChainData['chain']['evolves_to'], '', $evolutions);

    return $evolutions;
}

function getFlavourText($details): string
{
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

    $pokemonData = getDataFromPokeApi("https://pokeapi.co/api/v2/pokemon/{$pokemon}");

    $pokemonData['last_update'] = $dbData['last_update'] ?? '';
    $pokemonData['nickname'] = $dbData['nickname'];
    $pokemonData['level'] = $dbData['level'];

    $speciesData = getDataFromPokeApi($pokemonData['species']['url']);
    $pokemonData['flavourText'] = getFlavourText($speciesData);
    $pokemonData['evolutionTree'] = getEvolutionChain($speciesData['evolution_chain']['url']);

    return $pokemonData;
}
