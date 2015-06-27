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
         * Sets the authority of the URI.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-3.2
         * @param string $authority
         * @return $this
         */
        public function setAuthority($authority);

        /**
         * Returns the authority of the URI.
         *
         * @return string
         */
        public function getAuthority();

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

        /**
         * Sets the path of the URI.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-3.3
         * @param \string[] $path
         * @return $this
         */
        public function setPath(array $path);

        /**
         * Returns the path of the URI.
         *
         * @return \string[]
         */
        public function getPath();

        /**
         * Sets the URI port.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-3.2.3
         * @param int|null $port
         * @return $this
         */
        public function setPort($port);

        /**
         * Returns the port of the URI.
         *
         * @return int|null
         */
        public function getPort();

        /**
         * Sets the URI user information.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-3.2.1
         * @param string|null $userInfo
         * @return $this
         */
        public function setUserInfo($userInfo);

        /**
         * @return string|null
         */
        public function getUserInfo();

        /**
         * Sets the host of the URI.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-3.2.2
         * @param string $host
         * @return $this
         */
        public function setHost($host);

        /**
         * Returns the host of the URI.
         *
         * @return string
         */
        public function getHost();

        /**
         * Set the URI query parameters.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-3.4
         * @param string[] $query
         * @return $this
         */
        public function setQuery(array $query);

        /**
         * Get the URI query parameters.
         *
         * @return string[]
         */
        public function getQuery();
    }
}
