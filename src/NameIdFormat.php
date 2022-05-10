<?php

/*
 * An "enum" for the different name ID formats.
 */


declare(strict_types = 1);

namespace Programster\Saml;


final class NameIdFormat
{
    private $m_format;


    private function __construct(string $format)
    {
        $this->m_format = $format;
    }


    public static function createEmailAddress() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress'); }
    public static function createX509SubjectName() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:1.1:nameid-format:X509SubjectName'); }
    public static function createWIndowsDomainQualifiedName() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:1.1:nameid-format:WindowsDomainQualifiedName'); }
    public static function createUnspecified() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified'); }
    public static function createKerberos() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:2.0:nameid-format:kerberos'); }
    public static function createEntity() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:2.0:nameid-format:entity'); }
    public static function createTransient() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:2.0:nameid-format:transient'); }
    public static function createPersistent() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:2.0:nameid-format:persistent'); }
    public static function createEncrypted() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:2.0:nameid-format:encrypted'); }


    public function __toString()
    {
        return $this->m_format;
    }
}
