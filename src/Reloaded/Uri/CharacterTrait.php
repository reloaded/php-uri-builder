<?php
/**
 * @author Reloaded <1337reloaded@gmail.com>
 * @since 6/27/2015 3:36 AM
 * @version 1.0.0
 */


/**
 * Provides APIs for manipulating reserved and unreserved characters when generating URIs.
 *
 * Class CharacterTrait
 */
trait CharacterTrait 
{
    /**
     * Returns an array of reserved characters that need to be percent encoded. Array keys are the raw characters
     * and array values are the percent-encoded representation of the array key character.
     *
     * @return string[]
     */
    public function getReservedChars()
    {
        return [
            "!" => "%20",
            "$" => "%24",
            "&" => "%26",
            "'" => "%27",
            "(" => "%28",
            ")" => "%29",
            "*" => "%2A",
            "+" => "%2B",
            "," => "%2C",
            ";" => "%3B",
            "=" => "%3D",
            ":" => "%3A",
            "/" => "2F",
            "?" => "%3F",
            "#" => "%23",
            "[" => "%5B",
            "]" => "%5D",
            "@" => "%40"
        ];
    }

    /**
     * Returns an array of unreserved characters that do not need to be percent-encoded. Array keys are the raw characters
     * and array values are the percent-encoded representation of the array key character.
     *
     * This array does not contain a list of DIGITS or ALPHA characters.
     *
     * @return string[]
     */
    public function getUnreservedChars()
    {
        return [
            "-" => "%2D",
            "." => "%2E",
            "_" => "%5F",
            "~" => "%7E"
        ];
    }
}