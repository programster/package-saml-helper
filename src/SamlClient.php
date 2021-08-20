<?php

/*
 * A client for helping with SAML interfacing.
 */

declare(strict_types = 1);

namespace Programster\Saml;


class SamlClient
{
    private \OneLogin\Saml2\Auth $m_auth;


    public function __construct(SamlConfig $config)
    {
        $auth = new \OneLogin\Saml2\Auth($config->toArray());
        $this->m_auth = $auth;
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
    public function handleUserLoginRequest(string $returnToUrl) : void
    {
        if (filter_var($returnToUrl, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) === false)
        {
            throw new Exceptions\ExceptionInvalidUrl($returnToUrl);
        }

        $this->m_auth->login($returnToUrl);   // Method that sent the AuthNRequest
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
            null, // $cbDeleteSession
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

        $this->m_auth->logout($returnToURL);   // Method that sent the AuthNRequest
    }


    # Accessors
    public function getAuth() : \OneLogin\Saml2\Auth { return $this->m_auth; }
}