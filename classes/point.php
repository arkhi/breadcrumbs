<?php

use Config as config;
use Db as db;
use Validator as validator;

class Point
{
    /**
     * Create DB or point if necessary.
     *
     * @param  array $params Data for current point passed by URL
     * @return point
     */
    public function __construct( $params ) {
        $this->db = new Db( config::$dbName );

        if ( empty( $params ) ) {
            return false;
        }

        $this->data = self::createPoint( $params );
    }

    /**
     * Make sure the data matches our expectations.
     *
     * @param  array   $data Data submitted through the browser request
     * @return boolean
     */
    private function isValidData( $data )
    {
        $validator = new Validator();
        $isNumeric = $validator->isValidEntry( $data, 'isNumeric', [ 'lat', 'lon', 'alt', 'spd', 'dir' ] );
        $isTime    = $validator->isValidEntry( $data[ 'time' ], 'isTime' );

        if ( $isNumeric && $isTime ) {
            return true;
        }

        return false;
    }

    /**
     * Create a point with the submitted data.
     *
     * @param  array $data Data submitted through the browser request
     * @return array       Array of data useful to the application.
     */
    private function createPoint( $data )
    {
        return [
            'lat'  => $data[ 'lat' ],
            'lon'  => $data[ 'lon' ],
            'alt'  => $data[ 'alt' ],
            'time' => $data[ 'time' ],
            'spd'  => $data[ 'spd' ],
            'dir'  => $data[ 'dir' ],
        ];
    }

    /**
     * Add point to the DB.
     *
     * @param Db    $db    Target DB
     * @param Point $point Point to add to the DB
     */
    public function addPoint( $db, $point )
    {
        if ( empty( $point ) || self::isValidData( $point ) === false ) {
            return false;
        }

        $request = 'INSERT INTO points
            ( `' . implode('`, `', array_keys( $point )) . '` )
            VALUES
            ( "' . implode('", "', $point) . '" )';

        echo $request;

        $db->exec( $request );
    }
}

?>
