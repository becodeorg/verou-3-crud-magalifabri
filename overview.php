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

    <form class="filter" action="" method="GET">
        <button type="submit" name="filterByLevel" value="1">show</button>
        from level <input type="number" name="from" value="<?= $_GET['from'] ?? 1 ?>">
        to <input type="number" name="to" value="<?= $_GET['to'] ?? 100 ?>">
    </form>

    <table class="pokemon-overview">
        <tr>
            <th></th>
            <th width="125">pokemon</th>
            <th width="125">nickname</th>
            <th width="75">level</th>
        </tr>

        <?php foreach ($cards as $card) : ?>
            <tr>
                <!-- SHOW DETAILS BUTTON -->
                <td>
                    <form action="" method="GET">
                        <a href="#details"><button type="submit" name="action" value="showDetails">𝐢</button></a>
                        <input type="hidden" name="id" value="<?= $card['id'] ?>">

                        <?php if (!empty($_GET['filterByLevel'])) : ?>
                            <input type="hidden" name="filterByLevel" value="1">
                            <input type="hidden" name="from" value="<?= $_GET['from'] ?? 1 ?>">
                            <input type="hidden" name="to" value="<?= $_GET['to'] ?? 100 ?>">
                        <?php endif ?>
                    </form>
                </td>

                <form action="" method="POST">
                    <td class="adapting-width">
                        <div><input type="text" name="pokemon" value="<?= $card['pokemon'] ?>"></div>
                    </td>
                    <td class="adapting-width">
                        <div><input type="text" name="nickname" value="<?= $card['nickname'] ?>"></div>
                    </td>
                    <td class="adapting-width">
                        <div><input type="number" name="level" value="<?= $card['level'] ?>"></div>
                    </td>

                    <!-- UPDATE BUTTON -->
                    <td>
                        <button type="submit" name="action" value="update">✎</button>
                        <input type="hidden" name="id" value="<?= $card['id'] ?>">
                    </td>
                </form>

                <!-- DELETE BUTTON -->
                <td>
                    <form action="" method="POST">
                        <button type="submit" name="action" value="delete">✕</button>
                        <input type="hidden" name="id" value="<?= $card['id'] ?>">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

        <!-- CREATE FORM -->
        <tr>
            <td></td>
            <form action="" method="POST">
                <td class="adapting-width">
                    <div><input type="text" name="pokemon" value=""></div>
                </td>
                <td class="adapting-width">
                    <div><input type="text" name="nickname" value=""></div>
                </td>
                <td class="adapting-width">
                    <div><input type="number" name="level" value=""></div>
                </td>
                <td colspan="2">
                    <button class="submit" type="submit" name="action" value="create">submit</button>
                </td>
            </form>
        </tr>

    </table>

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

</body>

</html>