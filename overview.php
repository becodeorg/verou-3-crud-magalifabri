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
            <th>#</th>
            <th>name</th>
            <th colspan="2">type(s)</th>
            <th>description</th>
        </tr>
        <?php foreach ($cards as $card) : ?>
            <tr>
                <td><?= $card['number'] ?></td>
                <td><?= $card['name'] ?></td>
                <td><?= $card['type1'] ?></td>
                <td><?= $card['type2'] ?></td>
                <td><?= $card['description'] ?></td>
            </tr>
        <?php endforeach; ?>

    </table>

</body>

</html>