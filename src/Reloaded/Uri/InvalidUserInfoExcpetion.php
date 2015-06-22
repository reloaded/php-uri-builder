<?php
/**
 * @author Reloaded <1337reloaded@gmail.com>
 * @since 6/22/2015 3:05 PM
 * @version 1.0.0
 */


namespace Reloaded\Uri;


/**
 * Exception thrown if the user info component of the URI is invalid or contains a password (inclusion of password
 * is deprecated in RFC 3986).
 *
 * Class InvalidUserInfoExcpetion
 * @package Reloaded\Uri
 */
class InvalidUserInfoExcpetion extends \Exception
{

}