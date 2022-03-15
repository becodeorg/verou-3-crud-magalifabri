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

    <h1>Goodcard - track your collection of Pokémon cards</h1>

    <table class="pokemon-overview">
        <tr>
            <th></th>
            <th width="100">pokemon</th>
            <th width="100">name</th>
            <th>level</th>
        </tr>

        <?php foreach ($cards as $card) : ?>
            <tr>
                <!-- SHOW DETAILS BUTTON -->
                <td>
                    <form action="" method="POST">
                        <button type="submit" name="action" value="showDetails">𝐢</button>
                        <input type="hidden" name="id" value="<?= $card['id'] ?>">
                    </form>
                </td>

                <form action="" method="POST">
                    <td class="adapting-width">
                        <div><input type="text" name="pokemon" value="<?= $card['pokemon'] ?>"></div>
                    </td>
                    <td class="adapting-width">
                        <div><input type="text" name="name" value="<?= $card['name'] ?>"></div>
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
                    <div><input type="text" name="name" value=""></div>
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
        <h2><?= $details['pokemon'] ?></h2>
        <?php if (!empty($details['name'])) : ?>
            <p><i><?= $details['name'] ?></i></p>
        <?php endif ?>
        <p>level <?= $details['level'] ?></p>
        <?php if (!empty($details['description'])) : ?>
            <p><?= $details['description'] ?></p>
        <?php endif ?>
    <?php endif ?>

</body>

</html>