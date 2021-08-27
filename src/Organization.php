<?php

/*
 * A class to represent an Organization to go in the metadata file.
 * An organization consists of one or more descriptions in different languages. E.g. typically at least the details in
 * the "en" locale.
 */

declare(strict_types = 1);

namespace Programster\Saml;


final class Organization
{
    private array $m_organizationTranslations;

    /**
     * Create an organization. An organization consists of one or more descriptions in different languages.
     * E.g. typically at least the details in the "en" locale.
     * @param OrganizationTranslation $organizationTranslations - the description of the organization in different
     * languages.
     * @throws \Exception - if no translations/descriptions were provided.
     */
    public function __construct(OrganizationTranslation ...$organizationTranslations)
    {
        if (count($organizationTranslations) === 0)
        {
            throw new \Exception("One must provide at least one OrganizationTranslation if creating an Organization object.");
        }

        $this->m_organizationTranslations = $organizationTranslations;
    }


    # Accessors
    public function getTranslations() : array { return $this->m_organizationTranslations; }
}
