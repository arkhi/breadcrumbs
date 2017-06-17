<?php

class Config
{
    static public $dbName = 'NAME_OF_LOCAL_DB.db';

    // List of md5 encrypted serial ID and passwords.
    public $authorizedIds = [
        'DEVICE_MD5_HASH',
    ];

    // Number of points retrieved for each request.
    static public $requestLimit = 300;

    // At what point ID will the first request start?
    static public $requestOffset = 0;
}

?>
