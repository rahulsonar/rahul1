<?php

/*
 * ICCBOD Helper Class
 */

/**
 * Description of iccbodhelper
 *
 * @author kapil
 */
abstract class IccBodHelper {

    /**
     * Generate a random string.
     *
     * @param   mixed   $length  The length of the string needed.
     * @param   string  $charset        The characters from which the random string should be generated.
     *
     * @return  String  Returns returns a random string.
     *
     * @since   3.2
     */
    public static function randString($length, $charset = 'abcdefghijkmnopqrpdctvwxyz3456789') {
        $str = '';
        $count = strlen($charset);
        while ($length--) {
            $str .= $charset[mt_rand(0, $count - 1)];
        }
        return $str;
    }

}
