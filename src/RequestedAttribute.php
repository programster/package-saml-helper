<?php


declare(strict_types = 1);

namespace Programster\Saml;

class RequestedAttribute
{
    private string $m_name;
    private bool $m_isRequired;
    public string $m_nameFormat;
    public string $m_friendlyName;
    public array $m_attributeValue;


    public function __construct(
        string $name,
        bool $isRequired,
        string $nameFormat,
        string $friendlyName,
        array $attributeValue
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
        return [
            "name" => $this->m_name,
            "isRequired" => $this->m_isRequired,
            "nameFormat" => $this->m_nameFormat,
            "friendlyName" => $this->m_friendlyName,
            "attributeValue" => $this->m_attributeValue,
        ];
    }
}