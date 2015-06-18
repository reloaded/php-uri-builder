<?php
/**
 * @author Reloaded <1337reloaded@gmail.com>
 * @since 6/18/2015 12:12 AM
 * @version 1.0.0
 */


namespace Reloaded\Uri;


/**
 * Exception thrown if the User Information component of a URI is invalid or contains a clear text password.
 *
 * Class UserInformationException
 * @package Reloaded\Uri
 */
class UserInformationException extends \InvalidArgumentException
{

}