<?php

/*
 * An exception to throw if recieving a SAML response that the user is not authenticated.
 */

declare(strict_types = 1);

namespace Programster\Saml\Exceptions;


final class ExceptionInvalidUrl extends \Exception
{
    private string $m_url;


    public function __construct(string $passedUrl)
    {
        $this->m_url = $passedUrl;
        parent::__construct("Invalid URL passed: {$this->m_url}");
    }

    # Accessors
    public function getUrl() : string { return $this->m_url; }
}