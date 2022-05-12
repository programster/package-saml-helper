<?php

/*
 * An object to represent the response from the SAML identity provider when they are redirecting back after
 * user authentication.
 */

declare(strict_types = 1);

namespace Programster\Saml;


class SamlAuthResponse
{
    private array $m_userAttributes;
    private string $m_nameId;
    private string $m_nameIdFormat;
    private ?string $m_nameIdNameQualifier;
    private ?string $m_serviceProviderNameQualifier;
    private string $m_sessionIndex;


    /**
     * Create an object to handle a SAML login response using the OneLogin auth service. This assumes that you are trying
     * to create this object on the SAML login endpoint. E.g. there should be a $_POST field with the index
     * 'SAMLResponse' containing a response from the identity provider with the user information.
     * @param \OneLogin\Saml2\Auth $auth
     */
    public function __construct(\OneLogin\Saml2\Auth $auth)
    {
        $this->m_userAttributes = $auth->getAttributes();
        $this->m_sessionIndex = $auth->getSessionIndex();
        $this->m_nameId = $auth->getNameId();
        $this->m_nameIdFormat = $auth->getNameIdFormat();

        if ($auth->getNameIdNameQualifier() !== null)
        {
            $this->m_nameIdNameQualifier = $auth->getNameIdNameQualifier();
        }
        else
        {
            $this->m_nameIdNameQualifier = null;
        }

        if ($auth->getNameIdSPNameQualifier() !== null)
        {
            $this->m_serviceProviderNameQualifier = $auth->getNameIdSPNameQualifier();
        }
        else
        {
            $this->m_serviceProviderNameQualifier = null;
        }
    }


    # Accessors
    public function getUserAttributes() : array { return $this->m_userAttributes; }
    public function getNameId() : string  { return $this->m_nameId; }
    public function getNameIdFormat() : string { return $this->m_nameIdFormat; }
    public function getNameIdNameQualifier() : ?string { return $this->m_nameIdNameQualifier; }
    public function getServiceProviderNameQualifier() : ?string { return $this->m_serviceProviderNameQualifier; }
    public function getSessionIndex() : string { return $this->m_sessionIndex; }
}
