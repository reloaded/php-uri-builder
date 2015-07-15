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
        use CharacterTrait;

        /**
         * @var string|null
         */
        private $scheme;

        /**
         * @var string
         */
        private $host;

        /**
         * @var int|null
         */
        private $port;

        /**
         * @var string|null`
         */
        private $userInfo;

        /**
         * @var \string[]
         */
        private $path;

        /**
         * @var string[]
         */
        private $query;


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
         * Returns a boolean indicating if the scheme has been specified.
         *
         * @return bool
         */
        public function hasScheme()
        {
            return $this->scheme !== null && $this->scheme !== "";
        }

        /**
         * Sets the authority of the URI.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-3.2
         * @param string $authority
         * @return $this
         * @throws AuthorityParseException
         */
        public function setAuthority($authority)
        {
            $regex = "/^(?:{$this->getUserInfoRegex()}@)?{$this->getHostRegex()}{$this->getPortRegex()}?$/";
            $matches = [];

            if(preg_match($regex, $authority, $matches) === false)
            {
                throw new AuthorityParseException(
                    'URI authority must be in the form of [ userinfo "@" ] host [ ":" port ]'
                );
            }

            // Why the isset checks? PHP on Windows bug.
            // https://bugs.php.net/bug.php?id=50887
            $this
                ->setUserInfo(isset($matches[1]) && $matches[1] ? $matches[1] : null)
                ->setHost(isset($matches[2]) && $matches[2] ? $matches[2] : null)
                ->setPort(isset($matches[3]) && $matches[3] ? $matches[3] : null);

            return $this;
        }

        /**
         * Returns the URI authority.
         *
         * @return string
         */
        public function getAuthority()
        {
            $uri = "";

            if($this->hasUserInfo())
            {
                $uri .= $this->getUserInfo() . "@";
            }

            $uri .= $this->getHost();

            if($this->hasPort())
            {
                $uri .= ":" . $this->getPort();
            }

            return $uri;
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
         * Returns a boolean indicating if the host is specified.
         *
         * @return bool
         */
        public function hasHost()
        {
            return $this->host !== null && $this->host !== "";
        }

        /**
         * Returns a boolean indicating if the host value is a valid IPv4 or IPv6 or registered name.
         *
         * @param string $host
         * @return bool
         */
        protected function isHostValid($host)
        {
            return preg_match("/^{$this->getHostRegex()}$/", $host) > 0;
        }

        /**
         * Sets the URI port.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-3.2.3
         * @param int|null $port
         * @return $this
         * @throws InvalidPortException
         */
        public function setPort($port)
        {
            if($port && !$this->isPortValid($port))
            {
                throw new InvalidPortException("URI port must be a valid port between 1 and 65535.");
            }

            $this->port = $port ?: (int) $port;

            return $this;
        }

        /**
         * Returns the port of the URI.
         *
         * @return int|null
         */
        public function getPort()
        {
            return $this->port;
        }

        /**
         * Returns a boolean indicating if a port has been specified.
         *
         * @return bool
         */
        public function hasPort()
        {
            return $this->port && (int) $this->port > 0;
        }

        /**
         * @return string|null
         */
        public function getUserInfo()
        {
            return $this->userInfo;
        }

        /**
         * Sets the URI user information.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-3.2.1
         * @param string|null $userInfo
         * @return $this
         * @throws InvalidUserInfoException
         */
        public function setUserInfo($userInfo)
        {
            if($userInfo && !$this->isUserInfoValid($userInfo))
            {
                throw new InvalidUserInfoException(
                    'URI user information must be in the format of ( unreserved / pct-encoded / sub-delims / ":" ).'
                );
            }

            $matches = [];

            preg_match('/^([^@\s:]+)(?::([^@\s]+))?$/', $userInfo, $matches);

            if(isset($matches[2]) && $matches[2] !== "")
            {
                throw new InvalidUserInfoException(
                    "Including a user password in an URI is deprecated for security."
                );
            }

            $this->userInfo = $userInfo;

            return $this;
        }

        /**
         * Returns a boolean indicating if user information has been specified.
         *
         * @return bool
         */
        public function hasUserInfo()
        {
            return $this->userInfo !== null && $this->userInfo !== "";
        }

        /**
         * Returns a boolean indicating if the user info value is valid.
         *
         * @param string $userInfo
         * @return bool
         */
        protected function isUserInfoValid($userInfo)
        {
            return preg_match("/^{$this->getUserInfoRegex()}$/", $userInfo) > 0;
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

        /**
         * Returns a regular expression that can be used to match the authority host.
         *
         * @return string
         */
        protected function getHostRegex()
        {
            $ipv6Match = '(?:\[[^\]]+\])';
            $ipv4Match = '(?:(?:[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))';
            $regNameMatch = '(?:(?:[a-zA-Z]{1}[a-zA-Z0-9-]{0,61}[a-zA-Z0-9]{0,1}\.?)+)';

            return "({$ipv6Match}|{$ipv4Match}|{$regNameMatch})";
        }

        /**
         * Returns a regular expression that can be used to match the authority user information.
         *
         * @return string
         */
        protected function getUserInfoRegex()
        {
            return '([^@\s]+)';
        }

        /**
         * Returns a regular expression that can be used to match the authority port.
         *
         * @return string
         */
        protected function getPortRegex()
        {
            return '(?::(\d{1,5}))';
        }

        /**
         * @return \string[]
         */
        public function getPath()
        {
            return $this->path;
        }

        /**
         * Encodes each segment in $path and sets the path of the URI.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-3.3
         * @param \string[] $path
         * @return $this
         */
        public function setPath(array $path)
        {
            $this->path = [];

            foreach ($path as $value)
            {
                $this->appendPath($value);
            }

            return $this;
        }

        /**
         * Returns a boolean indicating if a path has been specified.
         *
         * @return bool
         */
        public function hasPath()
        {
            return count($this->path) > 0;
        }

        /**
         * Encodes and appends a segment to the path.
         *
         * @param string $path
         */
        public function appendPath($path)
        {
            $this->path[] = $this->encodePath($path);
        }

        /**
         * Encodes the segment and removes it from the path.
         *
         * @param string $path
         * @return $this
         */
        public function removePath($path)
        {
            $path = rawurlencode($path);

            $i = array_filter($this->path, function($p) use ($path) {
                return $p !== $path;
            });

            $this->path = array_values($i);
        }

        /**
         * Encodes the segment and returns a boolean indicating if it is present in the path.
         *
         * @param string $path
         * @return bool
         */
        public function pathExists($path)
        {
            return in_array($this->encodePath($path), $this->path);
        }

        /**
         * Set the URI query parameters.
         *
         * @link https://tools.ietf.org/html/rfc3986#section-3.4
         * @param string[] $query
         * @return $this
         */
        public function setQuery(array $query)
        {
            $this->query = [];

            foreach ($query as $key => $value)
            {
                $this->appendQuery($key, $value);
            }

            return $this;
        }

        /**
         * Get the URI query parameters.
         *
         * @return string[]
         */
        public function getQuery()
        {
            return $this->query;
        }

        /**
         * Returns a boolean indicating if a query has been specified.
         *
         * @return bool
         */
        public function hasQuery()
        {
            return count($this->query) > 0;
        }

        /**
         * @param string $key
         * @param string $value
         * @return $this
         * @throws InvalidQueryException
         */
        public function appendQuery($key, $value)
        {
            if(!is_string($key))
            {
                throw new InvalidQueryException("URI query key needs to be a string.");
            }

            $this->query[trim($key)] = strtr(rawurlencode($value), array_flip($this->getQueryComponentChars()));

            return $this;
        }

        /**
         * Checks to see if a query parameters exists with the given key.
         *
         * @param string $key
         * @return bool
         */
        public function queryExists($key)
        {
            return isset($this->query[$key]);
        }

        /**
         * Removes a query parameter with the given key.
         *
         * @param string $key
         * @return $this
         */
        public function removeQuery($key)
        {
            if($this->queryExists($key))
            {
                unset($this->query[$key]);
            }

            return $this;
        }
    }
}
