<?php

/**
 * Validates syntax of different fields.
 * Does not corroborate against database.
 */
class SyntaxValidator {
    /**
     * Validates a date range.
     * @param string $string Date range string separated by ' - '.
     * @param string $format Format of the date received, defaults to Y-m-d (e.g. 2018-05-27).
     * @return bool
     */
    public static function dateRange($string, $format = 'Y-m-d') {
        $array = explode(' - ', $string);
        if (count($array) != 2) {
            return false;
        }

        foreach ($array as $value) {
            $date = DateTime::createFromFormat($format, $value);
            // Check that the format is correct and if what we end up with, equals the
            // original date, else '2018-05-55' would pass as valid because we would
            // end up with something like '2018-06-24'.
            if ($date == false || $date->format($format) !== $value) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validates a room number.
     * @param $room
     * @return bool
     */
    public static function roomNumber($room) {
        if (!ctype_digit($room)) {
            return false;
        }
        if (!($room >= 1 && $room <= 10)) {
            return false;
        }
        return true;
    }

    /**
     * Validates a guest ID.
     * @param $guestId
     * @return bool
     */
    public static function guestId($guestId) {
        return ctype_digit($guestId);
    }

    /**
     * Validates first or last name.
     * @param string $name
     * @return bool
     */
    public static function name($name) {
        if (preg_match("/[a-z ]+$/i", $name)) {
            $l = strlen($name);
            if ($l >= 3 && $l <= 25) {
                return true;
            }
        }
        return false;
    }

    /**
     * TODO
     * Validates a phone number.
     * @param $phone
     * @return bool
     */
    public static function phone($phone) {
        return true;
    }

    /**
     * Validates an email address.
     * @param $email
     * @return bool
     */
    public static function email($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Validates a booking ID.
     * @param $guestId
     * @return bool
     */
    public static function bookingId($guestId) {
        return ctype_digit($guestId);
    }

}