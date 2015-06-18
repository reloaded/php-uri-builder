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


    }
}
