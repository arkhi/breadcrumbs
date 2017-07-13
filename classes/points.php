<?php

use Config as config;
use Db as db;

class Points
{
    /**
     * Create DB or point if necessary.
     *
     * @param  array $params Data for current point passed by URL
     * @return point
     */
    public function __construct() {
        $this->db = new Db( config::$dbName );
    }

    /**
     * Get a certain number of points from the DB.
     *
     * @param  Db $db          Source DB
     * @param  integer $offset Where the query start from in the DB
     * @param  integer $limit  Number of rows to select
     * @return array           List of points
     */
    public function getPoints( $db, $offset = null, $limit = null )
    {
        $offset  = $offset ?: config::$requestOffset;
        $limit   = $limit  ?: config::$requestLimit;
        $request = 'SELECT * FROM points ORDER BY time DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
        $result  = $db->query( $request );
        $points  = [];

        while ( $point = $result->fetchArray( SQLITE3_ASSOC ) ) {
            $points[] = $point;
        }

        return $points;
    }
}

?>
