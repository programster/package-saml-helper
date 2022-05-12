<?php

/*
 * An exception to throw if receiving a SAML response that the user is not authenticated.
 */

declare(strict_types = 1);

namespace Programster\Saml\Exceptions;


final class ExceptionSamlNotAuthenticated extends \Exception
{

}