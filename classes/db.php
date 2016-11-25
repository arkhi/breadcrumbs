<?php

use Config as config;

class Db extends SQLite3
{
    public function __construct( $dbName )
    {
        $this->open( $dbName );
        $this->createTables();
    }

    /**
     * Create DB if necessary.
     */
    private function createTables()
    {
        if ( !$this->exec( 'CREATE TABLE IF NOT EXISTS points (
                id   INTEGER PRIMARY KEY AUTOINCREMENT,
                lat  REAL DEFAULT NULL,
                lon  REAL DEFAULT NULL,
                alt  REAL DEFAULT NULL,
                time INTEGER  DEFAULT now  NOT NULL ,
                spd  REAL DEFAULT NULL,
                dir  REAL DEFAULT NULL
             )' )
        ) {
            echo 'Table creation failed.';
        }
    }
}
