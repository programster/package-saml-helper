<?php


declare(strict_types = 1);

namespace Programster\Saml;


final class ServiceProviderConfig
{
    private string $m_entityId;
    private string $m_name;
    private string $m_description;
    private string $m_publicCert;
    private string $m_privateKey;
    private array $m_requestedAttributes;


    /**
     *
     * @param string $entityId - the entity ID of this service provider. E.g. https://app.mydomain.com
     * @param string $name - the name of this service provider. E.g. "Programster Forum"
     * @param string $description - the description of this service provider. E.g. "A forum for Programster".
     * @param string $loginHandlerUrl - the URL/endpoint of this service where we expect to handle the response from
     * the SSO for logging in. This is where we will retrieve the user's details and mark the user as logged in etc.
     * E.g. https://app.mydomain.com/saml-login-handler
     * @param string $logoutHandlerUrl - the URL/endpoint where we will handle the response from the SSO for logging
     * out. E.g. https://app.mydomain.com/saml-logout-handler
     * @param string $publicCert - the x509 public certificate that belongs with our private key (a key-pair).
     * @param string $privateKey - the private key that is used for signing/encrypting requests that are sent to the
     * SSO.
     * @param RequestedAttribute $requestedAttributes - any number of attributes we are requesting from the SSO. One
     * does not necessarily need to provide any.
     */
    public function __construct(
        string $entityId,
        string $name,
        string $description,
        string $loginHandlerUrl,
        string $logoutHandlerUrl,
        string $publicCert,
        string $privateKey,
        RequestedAttribute ...$requestedAttributes
    )
    {
        $this->m_name = $name;
        $this->m_description = $description;
        $this->m_entityId = $entityId;
        $this->m_publicCert = $publicCert;
        $this->m_privateKey = $privateKey;
        $this->m_requestedAttributes = $requestedAttributes;
        $this->m_loginHandlerUrl = $loginHandlerUrl;
        $this->m_logoutHandlerUrl = $logoutHandlerUrl;
    }


    public function toArray() : array
    {


        // Identity Provider Data that we want connected with our SP.
        $arrayForm = array();

        // Identifier of the SP entity  (must be a URI)
        $arrayForm['entityId'] = $this->m_entityId;

        // Specifies info about where and how the <AuthnResponse> message MUST be
        // returned to the requester, in this case our SP.
        $arrayForm['assertionConsumerService'] = array(

            // URL Location where the <Response> from the IdP will be returned
            'url' => $this->m_loginHandlerUrl,

            // SAML protocol binding to be used when returning the <Response>
            // message. OneLogin Toolkit supports this endpoint for the
            // HTTP-POST binding only.
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
        );

        // If you need to specify requested attributes, set a
        // attributeConsumingService. nameFormat, attributeValue and
        // friendlyName can be omitted
        if ($this->m_requestedAttributes !== null && count($this->m_requestedAttributes) > 0)
        {
            // we only create the $attributeConsumingService if we are requesting attributes.
            $attributeConsumingService = array(
                "serviceName" => $this->m_name,
                "serviceDescription" => $this->m_description,
            );

            $attributeConsumingService["requestedAttributes"] = array();

            foreach ($this->m_requestedAttributes as $requestedAttribute)
            {
                /* @var $requestedAttribute RequestedAttribute */
                $attributeConsumingService[] = $requestedAttribute->toArray();
            }

            $arrayForm["attributeConsumingService"] = $attributeConsumingService;
        }

        // Specifies info about where and how the <Logout Response> message MUST be
        // returned to the requester, in this case our SP.
        $arrayForm['singleLogoutService'] = array(

            // URL Location where the <Response> from the IdP will be returned
            'url' => $this->m_logoutHandlerUrl,

            // SAML protocol binding to be used when returning the <Response>
            // message. OneLogin Toolkit supports the HTTP-Redirect binding
            // only for this endpoint.
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        );

        // Specifies the constraints on the name identifier to be used to
        // represent the requested subject.
        // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported.
        $arrayForm['NameIDFormat'] = 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress';

        // Usually x509cert and privateKey of the SP are provided by files placed at
        // the certs folder. But we can also provide them with the following parameters
        $arrayForm['x509cert'] = $this->m_publicCert;
        $arrayForm['privateKey'] = $this->m_privateKey;

        /*
         * Key rollover
         * If you plan to update the SP x509cert and privateKey
         * you can define here the new x509cert and it will be
         * published on the SP metadata so Identity Providers can
         * read them and get ready for rollover.
         */
        // 'x509certNew' => '',


        return $arrayForm;
    }


    # Accessors
    public function getEntityId() : string { return $this->m_entityId; }
    public function getName() : string { return $this->m_name; }
    public function getDescription() : string { return $this->m_description; }
    public function getPublicCert() : string { return $this->m_publicCert; }
    public function getPrivateKey() : string { return $this->m_privateKey; }
    public function getRequestedAttributes() : string { return $this->m_requestedAttributes; }
}
