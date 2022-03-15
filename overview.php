<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Goodcard - track your collection of Pokémon cards</title>
</head>

<body>

    <h1>Goodcard - track your collection of Pokémon cards</h1>

    <table>
        <tr>
            <th>name</th>
            <th>pokemon</th>
            <th>level</th>
        </tr>
        <?php foreach ($cards as $card) : ?>
            <tr>
                <td><?= $card['name'] ?></td>
                <td><?= $card['pokemon'] ?></td>
                <td><?= $card['level'] ?></td>
            </tr>
        <?php endforeach; ?>

    </table>

</body>

</html>