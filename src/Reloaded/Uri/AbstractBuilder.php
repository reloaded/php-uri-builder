<?php
/**
 *
 * @author Reloaded <1337reloaded@gmail.com>
 * @since 6/12/15 1:49 PM
 * @version 1.0.0
 */

namespace Reloaded\Uri
{
    abstract class AbstractBuilder implements BuilderInterface
    {
        /**
         * @var string|null
         */
        private $scheme;

        /**
         * @var string
         */
        private $host;

        /**
         * @var int
         */
        private $port;


        /**
         * Sets the scheme of the URI.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-3.1
         * @param string $scheme
         * @return $this
         * @throws InvalidSchemeException
         */
        public function setScheme($scheme)
        {
            if(!preg_match('/^[a-z]{1}[a-z0-9\+\-\.]*$/i', $scheme))
            {
                throw new InvalidSchemeException("Invalid URI scheme provided: {$scheme}");
            }

            $this->scheme = strtolower($scheme);

            return $this;
        }

        /**
         * Returns the scheme of the URI.
         *
         * @return string|null
         */
        public function getScheme()
        {
            return $this->scheme;
        }

        /**
         * Sets the host of the URI.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-3.2.2
         * @param string $host
         * @return $this
         * @throws InvalidHostException
         */
        public function setHost($host)
        {
            if(!$this->isHostValid($host))
            {
                throw new InvalidHostException("URI host format must be in IPv4, IPv6 or registered name.");
            }

            $this->host = $host;

            return $this;
        }

        /**
         * Returns the host of the URI.
         *
         * @return string
         */
        public function getHost()
        {
            return $this->host;
        }

        /**
         * Returns a boolean indicating if the host value is a valid IPv4 or IPv6 or registered name.
         *
         * @param string $host
         * @return bool
         */
        protected function isHostValid($host)
        {
            $ipv6Match = '(?:\[[^\]]+\])';
            $ipv4Match = '(?:(?:[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))';
            $regNameMatch = '(?:(?:[a-zA-Z]{1}[a-zA-Z0-9-]{0,61}[a-zA-Z0-9]{0,1}\.?)+)';

            return preg_match("/^({$ipv6Match}|{$ipv4Match}|{$regNameMatch})$/", $host) > 0;
        }

        /**
         * Sets the URI port.
         *
         * @param int $port
         * @return $this
         * @throws InvalidPortException
         */
        public function setPort($port)
        {
            if(!$this->isPortValid($port))
            {
                throw new InvalidPortException("URI port must be a valid port between 1 and 65535.");
            }

            $this->port = (int) $port;

            return $this;
        }

        /**
         * Returns the port of the URI.
         *
         * @return int
         */
        public function getPort()
        {
            return $this->port;
        }

        /**
         * Returns a boolean indicating if the port is valid.
         *
         * @param int $port
         * @return bool
         */
        protected function isPortValid($port)
        {
            return is_numeric($port) && (int) $port >= 1 && (int) $port <= 65535;
        }

    }
}
