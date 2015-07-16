<?php
/**
 * @author Reloaded <1337reloaded@gmail.com>
 * @since 6/27/2015 3:36 AM
 * @version 1.0.0
 */


namespace Reloaded\Uri
{
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
            return $this->getSubDelims() + $this->getGenDelims();
        }

        /**
         * Returns an mapping of generic syntax delimiter characters and their percent-encoded equivalent values. Keys
         * are the raw characters and values are percent-encoded representations.
         *
         * @return string[]
         */
        protected function getGenDelims()
        {
            return [
                ":" => "%3A",
                "/" => "%2F",
                "?" => "%3F",
                "#" => "%23",
                "[" => "%5B",
                "]" => "%5D",
                "@" => "%40"
            ];
        }

        /**
         * Returns an mapping of subcomponent delimiter characters and their percent-encoded equivalent values. Keys
         * are the raw characters and values are percent-encoded representations.
         *
         * @return string[]
         */
        protected function getSubDelims()
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
                "=" => "%3D"
            ];
        }

        /**
         * Returns an array of unreserved characters that do not need to be percent-encoded. Array keys are the
         * raw characters and array values are the percent-encoded representation of the array key character.
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

        /**
         * Returns an array of characters that do not need be percent-encoded when building the path component.
         *
         * @return \string[]
         */
        public function getPathComponentChars()
        {
            return $this->getUnreservedChars() + [":" => "%3A", "@" => "%40"];
        }

        /**
         * Encodes the given path.
         *
         * @param string $path
         * @return string
         */
        private function encodePath($path)
        {
            return strtr(rawurlencode($path), array_flip($this->getPathComponentChars()));
        }

        /**
         * Returns an array of characters that do not need to be percent-encoded when building the query
         * component.
         *
         * @return \string[]
         */
        public function getQueryComponentChars()
        {
            return $this->getPathComponentChars() + ["/" => "%2F", "?" => "%3F"];
        }

        /**
         * Encodes the given query component.
         *
         * @param string $query
         * @return string
         */
        private function encodeQuery($query)
        {
            return strtr(rawurlencode($query), array_flip($this->getQueryComponentChars()));
        }

        /**
         * Encodes the given fragment.
         *
         * @param string $fragment
         * @return string
         */
        private function encodeFragment($fragment)
        {
            return strtr(rawurlencode($fragment), array_flip($this->getQueryComponentChars()));
        }
    }
}