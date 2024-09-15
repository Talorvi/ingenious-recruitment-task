<?php

namespace Tests\Modules\Invoices\Domain\Entities;

use App\Modules\Invoices\Domain\Entities\Company;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    public function test_getters_return_correct_values()
    {
        $id = Uuid::uuid4();
        $name = 'Test Company';
        $street = '123 Test St';
        $city = 'Test City';
        $zip = '12345';
        $phone = '555-1234';
        $email = 'test@example.com';

        $company = new Company($id, $name, $street, $city, $zip, $phone, $email);

        $this->assertSame($id, $company->getId());
        $this->assertEquals($name, $company->getName());
        $this->assertEquals($street, $company->getStreet());
        $this->assertEquals($city, $company->getCity());
        $this->assertEquals($zip, $company->getZip());
        $this->assertEquals($phone, $company->getPhone());
        $this->assertEquals($email, $company->getEmail());
    }
}
