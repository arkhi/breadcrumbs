<?php

class Point
{
    public function __construct( $point ) {
        self::createDB();

        $this->point = array(
            'lat'  => $point[ 'lat' ],
            'lon'  => $point[ 'lon' ],
            'alt'  => $point[ 'alt' ],
            'time' => $point[ 'time' ],
            'spd'  => $point[ 'spd' ],
            'dir'  => $point[ 'dir' ],
        );

        return $this->point;
    }

    private function createDB()
    {
        echo 'Creating DB if necessary…';

        $sql = new SQLite3('trailer.db');

        if ( $sql->exec('CREATE TABLE IF NOT EXISTS points (
                id   INTEGER PRIMARY KEY AUTOINCREMENT,
                lat  REAL DEFAULT NULL,
                lon  REAL DEFAULT NULL,
                alt  REAL DEFAULT NULL,
                time INTEGER  DEFAULT now  NOT NULL ,
                spd  REAL DEFAULT NULL,
                dir  TEXT DEFAULT NULL
             )') ){
            echo 'Table existing or created.';
        } else {
            echo 'Table creation failed.';
        }
    }

    public function add()
    {
        $sql = new SQLite3('trailer.db');
        $request = 'INSERT INTO points (
            lat, lon, alt, time, spd, dir
         ) VALUES (
            ' . $this->point[ 'lat' ] . '
            ,' . $this->point[ 'lon' ] . '
            ,' . $this->point[ 'alt' ] . '
            ,"' . $this->point[ 'time' ] . '"
            ,' . $this->point[ 'spd' ] . '
            ,"' . $this->point[ 'dir' ] . '")';

        echo $request;

        $sql->exec( $request );
    }
}

?>
