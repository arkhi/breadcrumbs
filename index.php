<?php

require('config/config.php');
require('classes/db.php');
require('classes/validator.php');
require('classes/point.php');
require('classes/points.php');

$pointParams = $_GET;
$canAdd      = Validator::canAdd( $pointParams );
$point       = new Point( $pointParams );
$points      = new Points();

if ( $canAdd ) {
    $point->addPoint( $point->db, $point->data );
} else {
    $points = $points->getPoints( $points->db );
    $lastPoint = $points[ 0 ];
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Breadcrumbs</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="vendor/leaflet-providers.js"></script>

    <style>
        #map {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .trail {
            stroke-dasharray: 1%;
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

                <?php $points = array_reverse($points); ?>
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
        (function() {
            var map = L.map( 'map', {
                minZoom: 3,
                maxZoom: 14
            });

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
            ], {
                className: 'trail'
            });

            var dbOffset = + <?= Config::$requestOffset; ?>;
            var previousPointsXhr;

            /**
             * Request previous points from the server.
             */
            function requestPreviousPoints(url, params) {
                previousPointsXhr = new XMLHttpRequest();

                if ( !previousPointsXhr ) {
                    return false;
                }

                previousPointsXhr.onreadystatechange = addPointsToTrail;

                previousPointsXhr.open( 'POST', url, true );
                previousPointsXhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                previousPointsXhr.send( params );
            }

            /**
             * Add more points to the trail.
             */
            function addPointsToTrail() {
                var response;

                if ( previousPointsXhr.onreadystatuschange = XMLHttpRequest.DONE ) {
                    if (previousPointsXhr.status === 200) {
                        response = JSON.parse(previousPointsXhr.responseText);

                        response.points.forEach(function( point ) {
                            trail.addLatLng( [ point.lat, point.lon ] );
                        });

                        if ( response.points.length === <?= Config::$requestLimit; ?> ) {
                            requestPreviousPoints(
                                '/get_points.php',
                                'dbOffset=' + ( + response.dbOffset + <?= Config::$requestLimit; ?> )
                            );
                        }
                    }
                }
            }

            // Add layers to map.
            tiles.addTo( map )
            trail.addTo( map );
            latestLocation.addTo( map );

            // Add previous points to polyline.
            requestPreviousPoints(
                '/get_points.php',
                'dbOffset=' + dbOffset
            );

            // Center map on the latest location.
            map.setView([
                <?= $lastPoint[ 'lat' ]; ?>,
                <?= $lastPoint[ 'lon' ]; ?>
            ], 12);
        })();
    </script>
</body>
</html>
