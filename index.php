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
    $lastPoint = $points[ count( $points ) - 1 ];
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Breadcrumbs</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
    <script src="vendor/leaflet-providers.js"></script>

    <style>
        #map {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Breadcrumbs</h1>
        <p>Where am I?</p>
    </header>

    <section>
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

            <div id="map"></div>
        <?php endif; ?>
    </section>

    <script>
        var map = L.map( 'map' );

        // Define layers.
        var tiles = L.tileLayer.provider( 'OpenStreetMap.BlackAndWhite' );
        var latestLocation = L.circleMarker([
            <?= $lastPoint[ 'lat' ]; ?>,
            <?= $lastPoint[ 'lon' ]; ?>,
            {
                radius: 88
            }
        ]);
        var trail = L.polyline([
            <?php foreach ( $points as $key => $point ) : ?>
                [
                    <?= $point[ 'lat' ]; ?>,
                    <?= $point[ 'lon' ]; ?>
                ],
            <?php endforeach; ?>
        ]);

        // Add layers to map.
        tiles.addTo( map )
        trail.addTo( map );
        latestLocation.addTo( map );

        // Center map on the latest location.
        map.setView([
            <?= $lastPoint[ 'lat' ]; ?>,
            <?= $lastPoint[ 'lon' ]; ?>
        ], 14);
    </script>
</body>
</html>
