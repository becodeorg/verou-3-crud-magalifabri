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
            <th>pokemon</th>
            <th>name</th>
            <th>level</th>
        </tr>

        <?php foreach ($cards as $card) : ?>
            <tr>
                <td><?= $card['pokemon'] ?></td>
                <td><?= $card['name'] ?></td>
                <td><?= $card['level'] ?></td>
                <td>
                    <form action="./php/update.php" method="POST">
                        <button type="submit" name="update">✎</button>
                    </form>
                </td>
                <td>
                    <form action="./php/delete.php" method="POST">
                        <button type="submit" name="delete">✕</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

        <tr class="create-form">
            <form action="" method="POST">
                <td>
                    <div><input type="text" name="pokemon" value=""></div>
                </td>
                <td>
                    <div><input type="text" name="name" value=""></div>
                </td>
                <td>
                    <div><input type="number" name="level" value=""></div>
                </td>
                <td colspan="2">
                    <button class="submit" type="submit" name="action" value="create">submit</button>
                </td>
            </form>
        </tr>

    </table>
</body>

</html>