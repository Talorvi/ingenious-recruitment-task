<?php

namespace App\Modules\Invoices\Api\Dto;

class CompanyDto
{
    public string $id;
    public string $name;
    public string $street;
    public string $city;
    public string $zip;
    public string $phone;
    public string $email;

    public function __construct(
        string $id,
        string $name,
        string $street,
        string $city,
        string $zip,
        string $phone,
        string $email
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->street = $street;
        $this->city = $city;
        $this->zip = $zip;
        $this->phone = $phone;
        $this->email = $email;
    }
}
