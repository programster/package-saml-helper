<?php


declare(strict_types = 1);

namespace Programster\Saml;


final class IdentityProviderConfig
{
    private string $m_entityId;
    private string $m_authUrl;
    private string $m_logoutUrl;
    private ?string $m_logoutResponseUrl;
    private string $m_publicSigningCertificate;
    private ?string $m_publicEncryptionCertificate;

    /**
     *
     * @param string $entityId - the entity ID of the identity provider.
     * E.g. "https://idp.mydomain.com/simplesaml/saml2/idp/metadata.php"
     * @param string $authUrl - the URL of the authentication endpoing on the SSO.
     * E.g. "https://idp.mydomain.com/simplesaml/saml2/idp/SSOService.php"
     * @param string $logoutUrl - the URL of where to send a logout request to the SSO.
     * E.g. "https://idp.mydomain.com/simplesaml/saml2/idp/SingleLogoutService.php
     * @param string|null $logoutResponseUrl -  URL location of the identity provider where we will send the logout
     * response. If set to null, the $logoutUrl will be used.
     * @param string $publicSigningCertificate - the public certificate of the identity provider that we shall use to
     * verify their signed messages
     * @param string|null $publicEncryptionCertificate - the public certificate of the identity provider that we shall
     * use to decrypt their messages. If set to null, then we will use the $publicSigningCertificate.
     */
    public function __construct(
        string $entityId,
        string $authUrl,
        string $logoutUrl,
        string $publicSigningCertificate,
        ?string $publicEncryptionCertificate = null,
        ?string $logoutResponseUrl = null
    )
    {
        $this->m_entityId = $entityId;
        $this->m_authUrl = $authUrl;
        $this->m_logoutUrl = $logoutUrl;
        $this->m_logoutResponseUrl = $logoutResponseUrl;
        $this->m_publicSigningCertificate = $publicSigningCertificate;
        $this->m_publicEncryptionCertificate = $publicEncryptionCertificate;
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
        $arrayForm['singleLogoutService'] = array (
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


        if ($this->m_publicEncryptionCertificate !== null)
        {
            $arrayForm['x509certMulti'] = array(
                'signing' => array(
                    0 => $this->m_publicSigningCertificate,
                ),
                'encryption' => array(
                    0 => $this->m_publicEncryptionCertificate
                ),
            );
        }
        else
        {
            // Public x509 certificate of the IdP
            $arrayForm['x509cert'] = $this->m_publicSigningCertificate;
        }

        return $arrayForm;
    }
}
