<?php

/*
 * An "enum" for the different name ID formats.
 */


declare(strict_types = 1);

namespace Programster\Saml;


final class NameIdFormat implements \Stringable
{
    private string $m_format;


    private function __construct(string $format)
    {
        $this->m_format = $format;
    }


    public function createEmailAddress() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress'); }
    public function createX509SubjectName() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:1.1:nameid-format:X509SubjectName'); }
    public function createWIndowsDomainQualifiedName() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:1.1:nameid-format:WindowsDomainQualifiedName'); }
    public function createUnspecified() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified'); }
    public function createKerberos() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:2.0:nameid-format:kerberos'); }
    public function createEntity() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:2.0:nameid-format:entity'); }
    public function createTransient() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:2.0:nameid-format:transient'); }
    public function createPersistent() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:2.0:nameid-format:persistent'); }
    public function createEncrypted() : NameIdFormat { return new NameIdFormat('urn:oasis:names:tc:SAML:2.0:nameid-format:encrypted'); }


    public function __toString()
    {
        return $this->m_format;
    }
}
