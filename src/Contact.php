<?php

/*
 * A class to represent a contact to go in the metadata file.
 */

declare(strict_types = 1);

namespace Programster\Saml;


final class Contact
{
    private $m_type;
    private $m_name;
    private $m_email;


    public function __construct(ContactType $type, string $name, string $email)
    {
        $this->m_type = $type;
        $this->m_name = $name;
        $this->m_email = $email;
    }
    

    # Accessors
    public function getType() : ContactType { return $this->m_type; }
    public function getName() : string { return $this->m_name; }
    public function getEmail() : string { return $this->m_email; }
}
