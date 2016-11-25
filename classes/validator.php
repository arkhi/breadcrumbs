<?php

class Validator
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
    static function canAdd( $point )
    {
        $config = new Config();
        $serial = self::getIds( $point );

        if ( in_array( $serial, $config->authorizedIds ) ) {
            return true;
        }

        return false;
    }

    /**
     * Test a value against a pattern.
     *
     * @param  mixed   $input      Whatever value or array of values
     * @param  string  $pattern    Keyword or regular expression
     * @param  array   $properties List of properties found in $input
     * @return boolean
     */
    public function isValidEntry( $input, $pattern, $properties = null )
    {
        if ( $properties !== null ) {
            $results = array();

            foreach ( $properties as $property ) {
                $results[ $property ] = $this->isValidEntry( $input[ $property ], $pattern );
            }

            return !in_array( false, $results );
        }

        if ( $pattern === 'isNumeric' ) {
            return is_numeric( $input );
        }

        if ( $pattern === 'isTime' ) {
            return strtotime( $input );
        }

        return preg_grep($pattern, $input);

        return false;
    }
}
