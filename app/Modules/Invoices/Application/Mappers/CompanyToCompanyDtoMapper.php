<?php

namespace App\Modules\Invoices\Application\Mappers;

use App\Modules\Invoices\Api\Dto\CompanyDto;
use App\Modules\Invoices\Domain\Entities\Company;

class CompanyToCompanyDtoMapper
{
    public static function map(Company $company): CompanyDto
    {
        return new CompanyDto(
            id: $company->getId()->toString(),
            name: $company->getName(),
            street: $company->getStreet(),
            city: $company->getCity(),
            zip: $company->getZip(),
            phone: $company->getPhone(),
            email: $company->getEmail()
        );
    }
}
