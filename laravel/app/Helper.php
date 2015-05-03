<?php
namespace app;

/**
 * Base Model for Helper Functions
 * File : /laravel/app/Helper.php
 *
 * PHP version 5.3
 *
 * @category Celania
 * @package  Celania
 * @author   Jason Turpin <jasonaturpin@gmail.com>
 */

/**
 * Helper - Base model for helper functions
 *
 * @category Celania
 * @package  Celania
 * @author   Jason Turpin <jasonaturpin@gmail.com>
 */
class Helper {
    /**
     * Builds a password hash
     *
     * @param string $pw Password
     *
     * @return string
     */
    public static function createPWHash($pw) {
        return hash("sha256", $pw.config('pwSalt'));
    }

    /**
     * Cleans a list of emails
     *
     * @param string $emails   The list of email addresses
     * @param bool   $validate Whether to validate each address
     *
     * @return string a string of unique email addresses
     */
    public static function email_unique($emails, $validate = false) {
        // normalize delimeter
        $emails = str_replace(array(';', "\r", "\n", ',,'), ',', $emails);
        // to array for further processing
        $emails = explode(',', $emails);
        // trim all addresses to remove edge whitespace
        $emails = array_map('trim', $emails);
        // remove duplicates to prevent double sending
        $emails = array_unique($emails);
        // validate each address
        if ($validate) {
            foreach ($emails as $k => $rawEmail) {
                // IF the email does not validate, remove it from the $emails array
                if (false === filter_var($rawEmail, FILTER_VALIDATE_EMAIL)) {
                    unset($emails[$k]);
                }
            }
        }
        // back to a comma delimeted string
        $emails = implode(',', $emails);
        // remove blanks
        $emails = str_replace(',,', ',', $emails);
        // trim edge commas
        $emails = trim($emails, ',');

        return $emails;
    }

    /**
     * Dumps a variable for debugging purposes
     *
     * @param mixed $item Item being debugged
     * @param bool  $die  Whether to die after item is dumped
     *
     * @return void
     */
    public static function debug($item, $die = false) {
        $traceInfo = debug_backtrace();
        echo '<pre class="debugItem">'
            .'<div class="G__croak_info"><b>'.__METHOD__.'()</b> called'
            .(isset($traceInfo[1])
                ? ' in <b>'.(isset($traceInfo[1]['class'])
                    ? $traceInfo[1]['class'].$traceInfo[1]['type']
                    : ''
                ).$traceInfo[1]['function'].'()</b>'
                : '')
            .' at <b>'.$traceInfo[0]['file'].':'.$traceInfo[0]['line'].'</b></div>'
            .'<hr><div class="G__croak_value">';
        var_dump($item);
        echo '</div></pre>';
        if ($die) {
            die;
        }
    }
}
