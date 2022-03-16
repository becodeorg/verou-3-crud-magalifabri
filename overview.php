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

    <!-- <form action="" method="POST">
        from level <input type="number">
        to <input type="number">
        <button type="submit">filter</button>
    </form> -->
    <table class="pokemon-overview">
        <tr>
            <th></th>
            <th width="100">pokemon</th>
            <th width="100">nickname</th>
            <th>level</th>
        </tr>

        <?php foreach ($cards as $card) : ?>
            <tr>
                <!-- SHOW DETAILS BUTTON -->
                <td>
                    <form action="" method="GET">
                        <button type="submit" name="action" value="showDetails">𝐢</button>
                        <input type="hidden" name="id" value="<?= $card['id'] ?>">
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

        // echo '<pre>';
        // var_dump($details);
        // echo '</pre>';
        ?>

        <h2><?= $name ?></h2>

        <!-- DATABASE DATA -->
        <?php if (!empty($details['nickname'])) : ?>
            <p><i><?= $details['nickname'] ?></i></p>
        <?php endif ?>
        <p>level <?= $details['level'] ?></p>
        <?php if (!empty($details['description'])) : ?>
            <p><?= $details['description'] ?></p>
        <?php endif ?>

        <!-- API DATA -->
        <img src="<?= $sprite ?>" alt="">
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
    <?php endif ?>

</body>

</html>