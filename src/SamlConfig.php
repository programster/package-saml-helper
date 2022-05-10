<?php

declare(strict_types = 1);

namespace Programster\Saml;


final class SamlConfig
{
    private $m_strict;
    private $m_debug;

    private $m_serviceProviderConfig;
    private $m_identityProviderConfig;


    /**
     * Create the complete SAML configuration object for the client to use.
     * @param ServiceProviderConfig $spConfig - details about the service provider (this website/service)
     * @param IdentityProviderConfig $idpConfig - details about the identity provider (SAML SSO server).
     * @param bool $strict - if true, then will reject unsigned or unencrypted messages if it expects them to be
     * signed or encrypted. Also it will reject the messages if the SAML standard is not strictly followed:
     * Destination, NameId, Conditions ... are validated too.
     * @param bool $debug - if true, will print errors.
     */
    public function __construct(
        ServiceProviderConfig $spConfig,
        IdentityProviderConfig $idpConfig,
        bool $strict = true,
        bool $debug = false
    )
    {
        $this->m_strict = $strict;
        $this->m_debug = $debug;
        $this->m_identityProviderConfig = $idpConfig;
        $this->m_serviceProviderConfig = $spConfig;
    }


    public function toArray()
    {
        $arrayForm = [];

        $arrayForm['strict'] = $this->m_strict;
        $arrayForm['debug'] = $this->m_debug;

        // Set a BaseURL to be used instead of try to guess
        // the BaseURL of the view that process the SAML Message.
        // Ex http://sp.example.com/
        //    http://example.com/sp/
        $arrayForm['baseurl'] = null;

        $arrayForm['sp'] = $this->m_serviceProviderConfig->toArray();
        $arrayForm['idp'] = $this->m_identityProviderConfig->toArray();

        return $arrayForm;
    }


    # Accessors
    public function getServiceProviderConfig() : ServiceProviderConfig { return $this->m_serviceProviderConfig; }
    public function getIdpConfig() : IdentityProviderConfig { return $this->m_identityProviderConfig; }
}
