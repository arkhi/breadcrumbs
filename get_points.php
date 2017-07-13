<?php

require('config/config.php');
require('classes/db.php');
require('classes/points.php');

$points = new Points();

$dbOffset = $_REQUEST[ 'dbOffset' ];

$response = [
    'userData' => $dbOffset,
    'points'   => $points->getPoints( $points->db, $dbOffset ),
    'dbOffset' => $dbOffset,
];

echo json_encode( $response );

?>
