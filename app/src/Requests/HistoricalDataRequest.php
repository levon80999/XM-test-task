<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

class HistoricalDataRequest extends BaseRequest
{
    /**
     * @Assert\NotBlank
     * @AcmeAssert\Exists(repository="App\Repository\CompanySymbolsRepository",column="symbol")
     */
    public $companySymbol;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public $email;

    /**
     * @Assert\NotBlank
     * @Assert\Date
     * @Assert\LessThanOrEqual(propertyPath="endDate")
     */
    public $startDate;

    /**
     * @Assert\NotBlank
     * @Assert\Date
     * @Assert\GreaterThanOrEqual(propertyPath="startDate")
     */
    public $endDate;
}