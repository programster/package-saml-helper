<?php


declare(strict_types = 1);

namespace Programster\Saml;

class RequestedAttribute
{
    private string $m_name;
    private bool $m_isRequired;
    public ?AttributeNameFormat $m_nameFormat;
    public ?string $m_friendlyName;
    public ?array $m_attributeValue;


    /**
     *
     * @param bool $isRequired - whether the attribute is required or not.
     * @param string $name - the formal/computer name for the attribute. E.g. "urn:oid:1.3.6.1.4.1.1466.115.121.1.26"
     * These names are fairly standardised, and you will probably find the urn ID in the table at:
     * https://wiki.cac.washington.edu/display/infra/Guide+to+NameID+Formats+and+Attributes+Available+from+the+UW+IdP
     * @param string|null $friendlyName - the friendly name for the attribute. E.g. "mail" or "surname"
     * @param AttributeNameFormat|null $nameFormat - optionally set the format for the requested attribute
     * @param array|null $attributeValue - optionally set the attribute value
     */
    public function __construct(
        bool $isRequired,
        string $name,
        ?string $friendlyName = null,
        ?AttributeNameFormat $nameFormat = null,
        ?array $attributeValue
    )
    {
        $this->m_name = $name;
        $this->m_isRequired = $isRequired;
        $this->m_nameFormat = $nameFormat;
        $this->m_friendlyName = $friendlyName;
        $this->m_attributeValue = $attributeValue;
    }


    public function toArray()
    {
        $arrayFormat = [
            "isRequired" => $this->m_isRequired,
            "name" => $this->m_name,
        ];

        if ($this->m_friendlyName !== null)
        {
            $arrayFormat["friendlyName"] = $this->m_friendlyName;
        }

        if ($this->m_nameFormat !== null)
        {
            $arrayFormat["nameFormat"] = (string)$this->m_nameFormat;
        }

        if ($this->m_attributeValue !== null)
        {
            $arrayFormat["attributeValue"] = $this->m_attributeValue;
        }
    }
}