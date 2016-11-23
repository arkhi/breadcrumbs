<?php

require('config/config.php');
require('classes/auth.php');
require('classes/point.php');

$pointParams = $_GET;

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

    <?php if ( Auth::canPost( $pointParams ) ) : ?>
        <pre><?php print_r( $pointParams ); ?></pre>

        <?php
            $point = new Point( $pointParams );

            $point->add();
        ?>
    <?php else : ?>
        <p>A problem occured during authentification.</p>
    <?php endif; ?>
</body>
</html>
