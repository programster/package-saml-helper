<?php

/*
 * A class that acts as an enum (because PHP doesnt have enums yet), for the allowed contact types.
 * More info: https://saml2.sustainsys.com/en/stable/config-elements/contact-person.html
 */

declare(strict_types = 1);

namespace Programster\Saml;


final class ContactType implements \Stringable
{
    private string $m_type;


    private function __construct(string $type)
    {
        $this->m_type = $type;
    }


    public static function createTechnical() : ContactType { return new ContactType("technical"); }
    public static function createSupport() : ContactType { return new ContactType("support"); }
    public static function createAdministrative() : ContactType { return new ContactType("administrative"); }
    public static function createBilling() : ContactType { return new ContactType("billing"); }
    public static function createOther() : ContactType { return new ContactType("other"); }


    public function __toString()
    {
        return $this->m_type;
    }
}
