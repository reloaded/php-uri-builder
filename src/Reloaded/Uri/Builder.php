<?php
/**
 *
 * @author Reloaded <1337reloaded@gmail.com>
 * @since 6/12/15 11:53 AM
 * @version 1.0.0
 */

namespace Reloaded\Uri;

class Builder extends AbstractBuilder
{
    /**
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
