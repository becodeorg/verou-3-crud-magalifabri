<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="./style.css">

    <title>Goodcard - track your collection of Pokémon cards</title>
</head>

<body>

    <!-- <h1>Goodcard - track your collection of Pokémon cards</h1> -->

    <h1 class="title"><a href="index.php">my pokemon</a></h1>

    <div class="overview-and-details-wrapper">

        <section class="overview">

            <!-- FILTER FORM -->
            <form class="filter" action="" method="GET">
                <button type="submit" name="filterByLevel" value="1">show</button>
                from level <input type="number" name="from" value="<?= $_GET['from'] ?? 1 ?>">
                to <input type="number" name="to" value="<?= $_GET['to'] ?? 100 ?>">
            </form>

            <!-- ERROR MESSAGE -->
            <?php if (!empty($_SESSION['inputValidationError'])) : ?>
                <div class="img-and-p-wrapper">
                    <img src="./images/error-white.png" alt="">
                    <p class="error-message"><?= $_SESSION['inputValidationError'] ?></p>
                </div>
                <?php $_SESSION['inputValidationError'] = '' ?>
            <?php endif ?>

            <!-- TABLE -->
            <table class="pokemon-overview">
                <tr class="header-row">
                    <th></th>
                    <th>pokemon</th>
                    <th>nickname</th>
                    <th>level</th>
                </tr>

                <?php foreach ($cards as $card) : ?>
                    <tr>
                        <!-- SHOW DETAILS BUTTON -->
                        <form action="" method="GET">
                            <td>
                                <!-- <a href="#details"> -->
                                <button type="submit" name="action" value="showDetails" class="info-button">&nbsp;</button>
                                <!-- </a> -->
                                <input type="hidden" name="id" value="<?= $card['id'] ?>">

                                <?php if (!empty($_GET['filterByLevel'])) : ?>
                                    <input type="hidden" name="filterByLevel" value="1">
                                    <input type="hidden" name="from" value="<?= $_GET['from'] ?? 1 ?>">
                                    <input type="hidden" name="to" value="<?= $_GET['to'] ?? 100 ?>">
                                <?php endif ?>
                            </td>
                        </form>

                        <!-- INFO -->
                        <form action="" method="POST">
                            <td><input type="text" name="pokemon" value="<?= $card['pokemon'] ?>"></td>
                            <td><input type="text" name="nickname" value="<?= $card['nickname'] ?>"></td>
                            <td><input type="number" name="level" value="<?= $card['level'] ?>"></td>

                            <!-- UPDATE BUTTON -->
                            <td><button type="submit" name="action" value="update" class="edit-button">&nbsp;</button></td>

                            <input type="hidden" name="id" value="<?= $card['id'] ?>">
                        </form>

                        <!-- DELETE BUTTON -->
                        <form action="" method="POST">
                            <td><button type="submit" name="action" value="delete" class="delete-button">✕</button></td>

                            <input type="hidden" name="id" value="<?= $card['id'] ?>">
                        </form>
                    </tr>
                <?php endforeach; ?>

                <!-- CREATE FORM -->
                <tr>
                    <td></td>
                    <form action="" method="POST">
                        <td><input type="text" name="pokemon" value=""></td>
                        <td><input type="text" name="nickname" value=""></td>
                        <td><input type="number" name="level" value=""></td>
                        <td><button type="submit" name="action" value="create" class="create-button">+</button></td>
                    </form>
                </tr>

            </table>

        </section>

        <!-- SHOW DETAILS -->
        <?php if (!empty($details)) : ?>

            <?php
            $types = $details['types'];
            $abilities = $details['abilities'];
            $sprite = $details['sprites']['front_default'];
            $name = $details['forms'][0]['name'];
            ?>

            <section id="details" class="details">

                <!-- DATABASE DATA -->
                <div class="title-and-img-wrapper">
                    <h2><?= $name ?></h2>
                    <img src="<?= $sprite ?>" alt="">
                </div>

                <?php if (!empty($details['lastUpdate'])) : ?>
                    <p><small>updated on: <?= $details['lastUpdate'] ?></small></p>
                <?php endif ?>
                <div class="nickname-and-level-wrapper">
                    <?php if (!empty($details['nickname'])) : ?>
                        <p><b>Nickname</b>: <i><?= $details['nickname'] ?></i></p>
                    <?php endif ?>
                    <p><b>Level</b> <?= $details['level'] ?></p>
                </div>
                <?php if (!empty($details['description'])) : ?>
                    <p><b>Description</b>: <i>"<?= $details['description'] ?>"</i></p>
                <?php endif ?>

                <!-- API DATA -->
                <h3>Types</h3>
                <ul>
                    <?php foreach ($types as $type) : ?>
                        <li><?= $type['type']['name'] ?></li>
                    <?php endforeach ?>
                </ul>
                <h3>Abilities</h3>
                <ul>
                    <?php foreach ($abilities as $ability) : ?>
                        <li><?= $ability['ability']['name'] ?></li>
                    <?php endforeach ?>
                </ul>
            </section>
        <?php endif ?>

    </div>

</body>

</html>