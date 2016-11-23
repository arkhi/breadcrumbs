<?php

class Auth
{
    /**
     * Get the serial number of a device based on the informations of a point.
     *
     * @param  array $point
     * @return mixed        md5 value of concatenated serial number and password | null.
     */
    static private function getIds( $point )
    {
        if ( empty( $point ) ) {
            return null;
        }

        if ( isset( $point[ 'ser' ] ) === false || isset( $point[ 'pwd' ] ) === false ) {
            return null;
        }

        return md5( $point[ 'ser' ] . ' ' . $point[ 'pwd' ] );
    }

    /**
     * Define if the ID provided in the URL is allowed to post an entry.
     *
     * @param  array   $point Parameters from URL. They should contain 'ser' and
     *                        'pwd' parameters that match one of the authorized
     *                        values in the config AUTHORIZED_IDS.
     * @return boolean
     */
    static function canPost( $point )
    {
        $config = new Config();
        $serial = self::getIds( $point );

        if ( in_array( $serial, $config->authorizedIds ) ) {
            return true;
        }

        echo 'Device NOT authorized to publish';

        return false;
    }
}
