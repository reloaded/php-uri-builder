<?php
/**
 *
 * @author Reloaded <1337reloaded@gmail.com>
 * @since 6/12/15 12:15 PM
 * @version 1.0.0
 */

namespace Reloaded\Uri
{
    interface BuilderInterface
    {
        /**
         * Builds and returns the URI.
         *
         * @return string
         */
        public function build();

        /**
         * Returns an array of reserved characters that must be replaced with percent-encoded octets.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-2.2
         * @return string[]
         */
        public function getReservedChars();

        /**
         * Returns an array of unreserved characters that are allowed in the URI but do not have a
         * reserved purpose. These characters do not get percent-encoded octet replacements.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-2.3
         * @return string[]
         */
        public function getUnreservedChars();

        /**
         * Sets the scheme of the URI.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-3.1
         * @param string $scheme
         * @return $this
         */
        public function setScheme($scheme);

        /**
         * Returns the scheme of the URI.
         *
         * @return string
         */
        public function getScheme();

        /**
         * Sets the fragment of the URI.
         *
         * @return string
         */
        public function setFragment();

        /**
         * Returns the fragment of the URI.
         *
         * @return string
         */
        public function getFragment();


    }
}
