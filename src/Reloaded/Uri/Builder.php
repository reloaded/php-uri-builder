<?php
/**
 *
 * @author Reloaded <1337reloaded@gmail.com>
 * @since 6/12/15 11:53 AM
 * @version 1.0.0
 */

namespace Reloaded\Uri;

use LogicException;

class Builder extends AbstractBuilder
{
    /**
     * Constructs a new URI Builder object.
     *
     * @param string $uri An optional URI to parse that already has special characters encoded.
     * @throws LogicException
     */
    public function __construct($uri = "")
    {
        $matches = [];

        if(preg_match('/^(?:([^:\/?#]+):)?(\/\/([^\/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?$/', $uri, $matches) === false)
        {
            throw new LogicException("Invalid regular expression pattern.");
        }

        if($uri !== "")
        {
            $scheme = isset($matches[1]) ? $matches[1] : "";
            $authority = isset($matches[3]) ? $matches[3] : "";
            $path = isset($matches[4]) ? explode("/", trim($matches[4], "/")) : [];
            $query = isset($matches[5]) ? explode("&", trim($matches[5], "?")) : [];
            $fragment = isset($matches[8]) ? $matches[8] : "";

            /** @var string[] $querykeyValuePairs */
            $querykeyValuePairs = [];
            foreach($query as $keyValue)
            {
                $a = explode("=", $keyValue);
                $querykeyValuePairs[$a[0]] = $a[1];
            }

            $this
                ->setScheme($scheme)
                ->setAuthority($authority)
                ->setPath($path)
                ->setQuery($querykeyValuePairs)
                ->setFragment($fragment);
        }
    }

    /**
     * Returns a URI string of the current URI builder.
     *
     * @return string
     * @throws InvalidSchemeException
     */
    function __toString()
    {
        $uri = "";

        if($this->getScheme() === "")
        {
            throw new InvalidSchemeException("URI scheme is required.");
        }
        $uri .= $this->getScheme() . ":";

        if($this->getHost())
        {
            $uri .= "//";

            if($this->getUserInfo())
            {
                $uri .= $this->getUserInfo() . "@";
            }

            $uri .= $this->getHost();

            if($this->getPort())
            {
                $uri .= ":" . $this->getPort();
            }
        }

        if($this->hasPath())
        {
            if($this->getAuthority())
            {
                $uri .= "/";
            }

            $uri .= join("/", $this->getPath());
        }

        if($this->hasQuery())
        {
            $q = [];

            foreach($this->getQuery() as $k => $v)
            {
                $q[] = "{$k}={$v}";
            }

            $uri .= "?" . join("&", $q);
        }

        if($this->hasFragment())
        {
            $uri .= "#" . $this->getFragment();
        }

        return $uri;
    }
}
