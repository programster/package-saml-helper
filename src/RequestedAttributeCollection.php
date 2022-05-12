<?php

declare(strict_types = 1);

namespace Programster\Saml;


final class RequestedAttributeCollection extends \ArrayObject
{
    public function __construct(RequestedAttribute ...$requestedAttributes)
    {
        parent::__construct($requestedAttributes);
    }


    public function append($value)
    {
        if ($value instanceof RequestedAttribute)
        {
            parent::append($value);
        }
        else
        {
            throw new \Exception("Cannot append non RequestedAttribute to a " . __CLASS__);
        }
    }


    public function offsetSet($index, $newVal)
    {
        if ($newVal instanceof RequestedAttribute)
        {
            parent::offsetSet($index, $newVal);
        }
        else
        {
            throw new \Exception("Cannot add a non RequestedAttribute value to a " . __CLASS__);
        }
    }
}