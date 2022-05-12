<?php

/*
 * An exception to throw when there were errors in a SAML response
 */

declare(strict_types = 1);

namespace Programster\Saml\Exceptions;


final class ExceptionSamlErrors extends \Exception
{
    private array $m_errors;
    private string $m_lastErrorReason;


    public function __construct(\OneLogin\Saml2\Auth $auth)
    {
        $this->m_errors = $auth->getErrors();
        $this->m_lastErrorReason = $auth->getLastErrorReason();

        parent::__construct("There were errors in the SAML response: " . $this->m_lastErrorReason);
    }


    # Accessors
    public function getErrors() : array { return $this->m_errors; }
    public function getLastErrorREason() : string { return $this->m_lastErrorReason; }
}
