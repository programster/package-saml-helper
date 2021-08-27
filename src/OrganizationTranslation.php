<?php

/*
 * A class to represent a contact to go in the metadata file.
 */

declare(strict_types = 1);

namespace Programster\Saml;


final class OrganizationTranslation
{
    private string $m_langCode;
    private string $m_name; // OrganizationName
    private string $m_displayName; // OrganizationDisplayName
    private string $m_url; // OrganizationURL


    /**
     * An Organization for putting in the metadata XML file.
     * https://docs.oasis-open.org/security/saml/v2.0/saml-metadata-2.0-os.pdf
     * @param string $name - One or more language-qualified names that may or may not be suitable for human consumption.
     * @param string $displayName - One or more language-qualified names that are suitable for human consumption.
     * @param string $url
     * @param string $languageCode - the language code for this organization description. E.g.
     */
    public function __construct(string $name, string $displayName, string $url, string $languageCode = "en")
    {
        $this->m_name = $name;
        $this->m_displayName = $displayName;
        $this->m_url = $url;
        $this->m_langCode = $languageCode;
    }


    # Accessors
    public function getLanguageCode() : string { return $this->m_langCode; }
    public function getName() : string { return $this->m_name; }
    public function getDisplayname() : string { return $this->m_displayName; }
    public function getUrl() : string { return $this->m_url; }
}
