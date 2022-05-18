<?php


declare(strict_types = 1);

namespace Programster\Saml;


final class IdentityProviderConfig
{
    private string $m_entityId;
    private string $m_authUrl;
    private string $m_logoutUrl;
    private ?string $m_logoutResponseUrl;
    private array $m_publicSigningCertificates;
    private array $m_publicEncryptionCertificates;


    /**
     * Create the configuration for the identity provider.
     *
     * @param string $entityId - the entity ID of the identity provider.
     * E.g. "https://idp.mydomain.com/simplesaml/saml2/idp/metadata.php"
     *
     * @param string $authUrl - the URL of the authentication endpoint on the SSO.
     * E.g. "https://idp.mydomain.com/simplesaml/saml2/idp/SSOService.php"
     *
     * @param string $logoutUrl - the URL of where to send a logout request to the SSO.
     * E.g. https://idp.mydomain.com/simplesaml/saml2/idp/SingleLogoutService.php
     *
     * @param string|null $logoutResponseUrl -  URL location of the identity provider where we will send the logout
     * response. If set to null, the $logoutUrl will be used.
     *
     * @param array $publicSigningCertificates - a collection of strings, with each string representing a public
     * certificate of the identity provider that we shall use to verify their signed messages. Allowing multiple
     * certificates facilitates certificate rollover.
     * E.g. https://stackoverflow.com/questions/35909251/saml2-metadata-multiple-signing-certificates
     *
     * @param array $publicEncryptionCertificates - a collection of strings, with each string being the content of a
     * public certificate of the identity provider that we shall use to decrypt their messages. Messages from the
     * identity provider may not be encrypted, in which case this array can be empty.
     */
    public function __construct(
        string $entityId,
        string $authUrl,
        string $logoutUrl,
        array $publicSigningCertificates,
        array $publicEncryptionCertificates = array(),
        ?string $logoutResponseUrl = null
    )
    {
        $this->m_entityId = $entityId;
        $this->m_authUrl = $authUrl;
        $this->m_logoutUrl = $logoutUrl;
        $this->m_logoutResponseUrl = $logoutResponseUrl;
        $this->m_publicSigningCertificates = $publicSigningCertificates;
        $this->m_publicEncryptionCertificates = $publicEncryptionCertificates;
    }


    public function toArray()
    {
        // Identity Provider Data that we want connected with our SP.
        $arrayForm = array();

        // Identifier of the IdP entity  (must be a URI)
        $arrayForm['entityId'] = $this->m_entityId;

        // SSO endpoint info of the IdP. (Authentication Request protocol)
        $arrayForm['singleSignOnService'] = array(

            // URL Target of the IdP where the Authentication Request Message
            // will be sent.
            'url' => $this->m_authUrl,

            // SAML protocol binding to be used when returning the <Response>
            // message. OneLogin Toolkit supports the HTTP-Redirect binding
            // only for this endpoint.
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        );

        // SLO endpoint info of the IdP.
        $arrayForm['singleLogoutService'] = array(
            // URL Location of the IdP where SLO Request will be sent.
            'url' => $this->m_logoutUrl,

            // URL location of the IdP where the SP will send the SLO Response (ResponseLocation)
            // if not set, url for the SLO Request will be used
            'responseUrl' => $this->m_logoutResponseUrl,

            // SAML protocol binding to be used when returning the <Response>
            // message. OneLogin Toolkit supports the HTTP-Redirect binding
            // only for this endpoint.
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        );

        if
        (
               count($this->m_publicEncryptionCertificates) === 0
            && count($this->m_publicSigningCertificates)    === 1
        )
        {
            // Public x509 certificate of the IdP
            $arrayForm['x509cert'] = reset($this->m_publicSigningCertificates);
        }
        else
        {
            $arrayForm['x509certMulti'] = array(
                'signing' => array_values($this->m_publicSigningCertificates),
                'encryption' => array_values($this->m_publicEncryptionCertificates),
            );
        }

        return $arrayForm;
    }


    # Accessors
    public function getEntityId() : string { return $this->m_entityId; }
    public function getAuthUrl() : string { return $this->m_authUrl; }
    public function getLogoutUrl() : string { return $this->m_logoutUrl; }
    public function getLogoutResponseUrl() : ?string { return $this->m_logoutResponseUrl; }
    public function getPublicSigningCertificates() : array { return $this->m_publicSigningCertificates; }
    public function getPublicEncryptionCertificates() : array { return $this->m_publicEncryptionCertificates; }
}
