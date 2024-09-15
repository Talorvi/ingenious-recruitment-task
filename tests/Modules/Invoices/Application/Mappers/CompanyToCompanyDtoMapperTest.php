<?php

namespace Tests\Modules\Invoices\Application\Mappers;

use Tests\TestCase;
use App\Modules\Invoices\Application\Mappers\CompanyToCompanyDtoMapper;
use App\Modules\Invoices\Domain\Entities\Company;
use Ramsey\Uuid\Uuid;

class CompanyToCompanyDtoMapperTest extends TestCase
{
    public function test_map_returns_correct_company_dto()
    {
        $company = new Company(
            Uuid::uuid4(),
            'Test Company',
            '123 Test St',
            'Test City',
            '12345',
            '555-1234',
            'test@example.com'
        );

        $companyDto = CompanyToCompanyDtoMapper::map($company);

        $this->assertEquals($company->getId()->toString(), $companyDto->id);
        $this->assertEquals($company->getName(), $companyDto->name);
        $this->assertEquals($company->getStreet(), $companyDto->street);
        $this->assertEquals($company->getCity(), $companyDto->city);
        $this->assertEquals($company->getZip(), $companyDto->zip);
        $this->assertEquals($company->getPhone(), $companyDto->phone);
        $this->assertEquals($company->getEmail(), $companyDto->email);
    }
}
