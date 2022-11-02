<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CompanySymbolsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompanySymbolsRepository::class)
 */
class CompanySymbols
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $company_name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private ?string $financial_status;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private ?string $market_category;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $round_lot_size;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $security_name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private ?string $symbol;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private ?string $test_issue;

    /**
     * @ORM\Column(type="string")
     */
    private ?string $last_modified;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(string $company_name): self
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getFinancialStatus(): ?string
    {
        return $this->financial_status;
    }

    public function setFinancialStatus(string $financial_status): self
    {
        $this->financial_status = $financial_status;

        return $this;
    }

    public function getMarketCategory(): ?string
    {
        return $this->market_category;
    }

    public function setMarketCategory(string $market_category): self
    {
        $this->market_category = $market_category;

        return $this;
    }

    public function getRoundLotSize(): ?float
    {
        return $this->round_lot_size;
    }

    public function setRoundLotSize(float $round_lot_size): self
    {
        $this->round_lot_size = $round_lot_size;

        return $this;
    }

    public function getSecurityName(): ?string
    {
        return $this->security_name;
    }

    public function setSecurityName(string $security_name): self
    {
        $this->security_name = $security_name;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getTestIssue(): ?string
    {
        return $this->test_issue;
    }

    public function setTestIssue(string $test_issue): self
    {
        $this->test_issue = $test_issue;

        return $this;
    }

    public function getLastModified(): ?string
    {
        return $this->last_modified;
    }

    public function setLastModified(string $lastModified): self
    {
        $this->last_modified = $lastModified;

        return $this;
    }
}
