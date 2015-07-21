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
    function __toString()
    {
        $uri = "";

        if($this->getScheme() === "")
        {
            throw new InvalidSchemeException("URI scheme is required.");
        }
        $uri .= $this->getScheme() . "://";

        if($this->getUserInfo() !== "")
        {
            $uri .= $this->getUserInfo() . "@";
        }

        $uri .= $this->getHost();

        if($this->hasPath())
        {
            $uri .= join("/", $this->getPath());
        }

        if($this->hasQuery())
        {
            $uri .= "?" . http_build_query($this->getQuery());
        }

        if($this->hasFragment())
        {
            $uri .= "#" . $this->getFragment();
        }

        return $uri;
    }
}
