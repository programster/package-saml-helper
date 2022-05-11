<?php

/*
 * A client for helping with SAML interfacing.
 */

declare(strict_types = 1);

namespace Programster\Saml;


class SamlClient
{
    private $m_auth;
    private $m_config;

    public function __construct(SamlConfig $config, $useProxy = false)
    {
        $this->m_config = $config;
        $auth = new \OneLogin\Saml2\Auth($config->toArray());
        $this->m_auth = $auth;

        \OneLogin\Saml2\Utils::setProxyVars($useProxy);
    }


    /**
     * Retrieves the metadata of the service provider based on the settings.
     * Spec doc: https://docs.oasis-open.org/security/saml/v2.0/saml-metadata-2.0-os.pdf
     * Validation tool: https://validator.safire.ac.za/
     *
     * @param bool $signMetadata - whether you want the generated metadata xml to be signed. This will use your
     * service provider certificate to sign the XML.
     *
     * @param bool $authnsign - authnRequestsSigned attribute - Optional attribute that indicates whether the
     * <samlp:AuthnRequest> messages sent by this service provider will be signed.
     *
     * @param bool $wantAssertionsSigned - Optional attribute that indicates a requirement for the <saml:Assertion>
     * elements received by this service provider to be signed. If omitted, the value is assumed to be false. This
     * requirement is in addition to any requirement for signing derived from the use of a particular profile/binding
     * combination.
     *
     * @param int|null $validUntil - the unix timestamp the metadata is valid until. If not provided (null value), then
     * this tool will automatically set it to 2 days from now.
     *
     * @param int|null $cacheDuration - Duration of the cache in seconds
     *
     * @param ContactCollection|null $contacts - any details of contacts you wish to provide. Unfortuantely due to
     * how the underlying package works, you can only have one contact per contact type. E.g. only one "billing"
     * contact.
     *
     * @param Organization|null $organization - optionally provide details of the organization behind the service
     * provider
     *
     * @return string - the generated SAML Metadata XML
     */
    public function getServiceProviderMetadata(
        bool $signMetadata = true,
        bool $authnsign = false,
        bool $wantAssertionsSigned = false,
        ?int $validUntil = null,
        ?int $cacheDuration = null,
        ?ContactCollection $contacts = null,
        ?Organization $organization = null
    ) : string
    {
        $settings = $this->getAuth()->getSettings();

        // if user provided contacts, convert it to the array format the underlying package is expecting.
        if ($contacts !== null && count($contacts) > 0)
        {
            $contactsArray = [];

            foreach ($contacts as $contact)
            {
                /* @var $contact \Programster\Saml\Contact */
                $contactsArray[(string)$contact->getType()] = [
                    'givenName' => $contact->getName(),
                    'emailAddress' => $contact->getEmail()
                ];
            }
        }
        else
        {
            $contactsArray = [];
        }

        if ($organization !== null)
        {
            $organizationArray = array();

            foreach ($organization->getTranslations() as $organizationTranslation)
            {
                /* @var $organizationTranslation OrganizationTranslation */
                $organizationArray[$organizationTranslation->getLanguageCode()] = [
                    'name' => $organizationTranslation->getName(),
                    'displayname' => $organizationTranslation->getDisplayname(),
                    'url' => $organizationTranslation->getUrl(),
                ];
            }
        }
        else
        {
            $organizationArray = array();
        }

        $xmlString = \OneLogin\Saml2\Metadata::builder(
            $settings->getSPData(),
            $authnsign = false,
            $wantAssertionsSigned = false,
            $validUntil = null,
            $cacheDuration = null,
            $contactsArray,
            $organizationArray
        );

        $xmlString = \OneLogin\Saml2\Metadata::addX509KeyDescriptors(
            $xmlString,
            $this->m_config->getServiceProviderConfig()->getPublicCert()
        );

        if ($signMetadata)
        {
            $this->m_config->getServiceProviderConfig();

            $xmlString = \OneLogin\Saml2\Metadata::signMetadata(
                $xmlString,
                $this->m_config->getServiceProviderConfig()->getPrivateKey(),
                $this->m_config->getServiceProviderConfig()->getPublicCert(),
            );
        }

        // format the xml
        $simpleXml = simplexml_load_string($xmlString);
        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($simpleXml->asXML());
        $simpleXmlElement = new \SimpleXMLElement($dom->saveXML());
        //$formatxml->saveXML("testF.xml"); // save as file
        $formattedXml = $simpleXmlElement->saveXML();

        return $formattedXml;
    }


    /**
     * Handle a user requesting to log in, but through the SSO. This will redirect the user's browser to
     * the SAML SSO where they will be prompted to log in if they arent already, and then redirected back with the SAML
     * signed response containing user information and session information etc.
     * @param string $returnToUrl - the URL that the SAML SSO should redirect back to with the user info.
     * This should be the URL/endpoint where we have our SAML authentication handler that processes such a
     * response. E.g. calls the SamlHelper's handleSamlLoginResponse method.
     * @throws Exceptions\ExceptionInvalidUrl - if the provided URL is not a URL.
     * @return void - this redirects the user.
     */
    public function handleUserLoginRequest(string $returnToUrl, $stay = false)
    {
        if (filter_var($returnToUrl, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) === false)
        {
            throw new Exceptions\ExceptionInvalidUrl($returnToUrl);
        }

        return $this->m_auth->login($returnToUrl, [], false, false, $stay);   // Method that sent the AuthNRequest
    }


    /**
     * Handle a SAML login/authenticate response.
     * @return SamlAuthResponse - the information in the SAML response. Crucially this contains the method
     * getUserAttributes() for getting the attributes of the logged in user.
     * It is still up to you to implement the necessary logic for your site to consider the user logged in.
     * E.g. get the user info from the response object and set the session user ID etc.
     * @throws Exceptions\ExceptionSamlErrors - if there were errors
     * @throws Exceptions\ExceptionSamlNotAuthenticated - if SAML response returned stating the user was not authenticated.
     */
    public function handleSamlLoginResponse() : SamlAuthResponse
    {
        $this->m_auth->processResponse();
        $errors = $this->m_auth->getErrors();

        if (count($errors) > 0)
        {
            throw new Exceptions\ExceptionSamlErrors($this->m_auth);
        }

        if ($this->m_auth->isAuthenticated() === false)
        {
            throw new Exceptions\ExceptionSamlNotAuthenticated();
        }

        return new SamlAuthResponse($this->m_auth);
    }


    /**
     * Handles the response from the SSO for logging out.
     * It is up to you to manually implement logic to mark the user as logged out, such as by destroying the session
     * etc.
     * @return string - the URL of the SSO logged out URL. You may wish to redirect to there to show the user
     * the SSO logged out message, or not, and show the user a message on your site saying they are logged out.
     */
    public function handleSamlLogoutResponse() : string
    {
        $ssoUrl = $this->m_auth->processSLO(
            true, // keepLocalSession
            null, // requestId
            false, // retrieveParametersFromServer
            null, // cbDeleteSession
            true // stay
        );

        $ssoUrl = $ssoUrl ?? "";
        $errors = $this->m_auth->getErrors();

        if (count($this->m_auth->getErrors()) > 0)
        {
            throw new Exceptions\ExceptionSamlErrors($this->m_auth);
        }

        return $ssoUrl;
    }


    /**
     * Handle a user requesting to log out.
     * This will redirect the user to the SSO logout endpoint with the appropriate SAML request for triggering a
     * logout.
     * @param string $returnToUrl - the URL to return to here. This should be the endpoint that handles the response
     * from the SSO.
     * @throws Exceptions\ExceptionInvalidUrl - if the passed $returnToUrl was an invalid URL.
     */
    public function handleUserLogoutRequest(string $returnToUrl)
    {
        if (filter_var($returnToUrl, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) === false)
        {
            throw new Exceptions\ExceptionInvalidUrl("You need to pass a valid URL to return to.");
        }

        $this->m_auth->logout($returnToUrl);   // Method that sent the AuthNRequest
    }


    # Accessors
    public function getAuth() : \OneLogin\Saml2\Auth { return $this->m_auth; }
}
