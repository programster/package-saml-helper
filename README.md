# SAML Helper
A package to help with integrating with a SAML SSO.

## Example Usage
All of the examples require first creating the SAML "client" which has all of the settings. One must create it like so:

```php
$spConfig = new Programster\Saml\ServiceProviderConfig(
    entityId: APP_SERVICE_PROVIDER_IDENTITY,
    name: APP_SERVICE_PROVIDER_NAME,
    description: "A test service provider",
    loginHandlerUrl: APP_URL . "/auth/saml-login-handler",
    logoutHandlerUrl: APP_URL . "/auth/saml-logout-handler",
    publicCert: file_get_contents(SERVICE_PROVIDER_CERT_PATH),
    privateKey: file_get_contents(SERVICE_PROVIDER_PRIVATE_KEY_PATH)
);

$idpConfig = new \Programster\Saml\IdentityProviderConfig(
    entityId: IDENTITY_PROVIDER_IDENTITY_URI,
    authUrl: IDENTITY_PROVIDER_AUTH_URL,
    logoutUrl: IDENTITY_PROVIDER_LOGOUT_URL,
    publicSigningCertificates: [file_get_contents(IDENTITY_PROVIDER_PUBLIC_SIGNING_CERT)],
);

$samlConfig = new \Programster\Saml\SamlConfig($spConfig, $idpConfig);
$samlClient = new \Programster\Saml\SamlClient($samlConfig);
```

I know that seems a bit long-winded, but the client requires a lot of settings to be configured, and I prefer this over
expecting the developer to just pass in an array of key/value pairs and expecting them to know what keys they need to
set etc.


Once you have the SAML client, you can use it for handling all the requests/responses like so:

Send the user with a signed SAML request to the SSO to log in and then
get redirected back to our site with their information:

```PHP
$returnToURL = "https://localhost/saml-login-handler";
$samlClient->handleUserLoginRequest($returnToURL);
```

Then use the client to handle the response from the SSO logging in and
redirecting back to our site with the user's details:
```PHP
$response = $samlClient->handleSamlLoginResponse();
$userAttributes = $response->getUserAttributes();
$userEmail = $userAttributes['email'][0];
```

Use the client to send a logout request to the SSO:
```PHP
$returnToUrl = 'http://my.domain.com/auth/saml-logout-handler';
$samlClient->handleUserLogoutRequest($returnToUrl);
```

Handle the response from the SSO for that logout request:
```PHP
$ssoLoggedOutUrl = $samlClient->handleSamlLogoutResponse();
```
