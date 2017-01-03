<?php

class Config
{
    static public $dbName = 'trailer.db';

    // List of md5 encrypted serial ID and passwords.
    public $authorizedIds = [
        '4bb5bb75608f468c0720d147962936c9',
    ];

    static public $requestLimit = 25;
    static public $requestOffset = 0;
}

?>
