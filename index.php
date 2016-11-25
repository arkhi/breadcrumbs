<?php

require('config/config.php');
require('classes/db.php');
require('classes/validator.php');
require('classes/point.php');

$pointParams = $_GET;
$canAdd      = Validator::canAdd( $pointParams );
$point       = new Point( $pointParams );

if ( $canAdd ) {
    $point->addPoint( $point->db, $point->data );
} else {
    $points = $point->getPoints( $point->db );
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Breadcrumbs</title>
</head>
<body>
    <h1>Breadcrumbs</h1>
    <p>Where am I?</p>

    <?php if ( $canAdd ) : ?>
        <pre><?php print_r( $pointParams ); ?></pre>
    <?php else : ?>
        <table>
            <tr>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Time</th>
            </tr>

            <?php foreach ($points as $key => $point) : ?>
                <tr>
                    <td><?= $point[ 'lat' ] ?></td>
                    <td><?= $point[ 'lon' ] ?></td>
                    <td><?= $point[ 'time' ] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
