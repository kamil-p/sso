<?php

namespace UserBundle\Document;

class Address
{
    /* @var array */
    private $address;

    public function __construct(
        String $companyName,
        String $street,
        String $postCode,
        String $phone,
        String $countryCode
    ) {
        $this->address = [
            'companyName' => $companyName,
            'street' => $street,
            'postCode' => $postCode,
            'phone' => $phone,
            'countryCode' => $countryCode
        ];
    }

    public function getCompanyName() : String
    {
        return $this->address['companyName'];
    }

    public function getStreet() : String
    {
        return $this->address['street'];
    }

    public function getPostCode() : String
    {
        return $this->address['postCode'];
    }

    public function getPhone() : String
    {
        return $this->address['phone'];
    }

    public function getCountryCode() : String
    {
        return $this->address['countryCode'];
    }

    public function toArray() : array
    {
        return $this->address;
    }
}