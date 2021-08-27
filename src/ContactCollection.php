<?php



declare(strict_types = 1);

namespace Programster\Saml;


final class ContactCollection extends \ArrayObject
{
    public function __construct(Contact ...$contacts)
    {
        parent::__construct($contacts);
    }


    public function append($value)
    {
        if ($value instanceof Contact)
        {
            parent::append($value);
        }
        else
        {
            throw new Exception("Cannot append non Contact to a " . __CLASS__);
        }
    }


    public function offsetSet($index, $newval)
    {
        if ($newval instanceof Contact)
        {
            parent::offsetSet($index, $newval);
        }
        else
        {
            throw new Exception("Cannot add a non Contact value to a " . __CLASS__);
        }
    }
}