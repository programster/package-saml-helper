<?php

/*
 * An "enum" for the different attribute name ID formats.
 */


declare(strict_types = 1);

namespace Programster\Saml;


final class AttributeNameFormat
{
    private string $m_format;


    private function __construct(string $format)
    {
        $this->m_format = $format;
    }


    public static function createUnspecified() : AttributeNameFormat { return new AttributeNameFormat('urn:oasis:names:tc:SAML:2.0:attrname-format:unspecified'); }
    public static function createUri() : AttributeNameFormat { return new AttributeNameFormat('urn:oasis:names:tc:SAML:2.0:attrname-format:uri'); }
    public static function createBasic() : AttributeNameFormat { return new AttributeNameFormat('urn:oasis:names:tc:SAML:2.0:attrname-format:basic'); }


    public function __toString() : string
    {
        return $this->m_format;
    }
}
