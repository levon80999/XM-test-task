<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\HistoricalDataRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoricalDataRepository::class)
 */
class HistoricalData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $date;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private $open = null;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private $high = null;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private $low = null;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private $close = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $volume = null;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private $adjclose = null;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $symbol;

    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(int $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getOpen()
    {
        return $this->open;
    }

    public function setOpen($open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getHigh()
    {
        return $this->high;
    }

    public function setHigh($high): self
    {
        $this->high = $high;

        return $this;
    }

    public function getLow()
    {
        return $this->low;
    }

    public function setLow($low): self
    {
        $this->low = $low;

        return $this;
    }

    public function getClose()
    {
        return $this->close;
    }

    public function setClose($close): self
    {
        $this->close = $close;

        return $this;
    }

    public function getVolume()
    {
        return $this->volume;
    }

    public function setVolume(?int $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getAdjclose()
    {
        return $this->adjclose;
    }

    public function setAdjclose($adjclose): self
    {
        $this->adjclose = $adjclose;

        return $this;
    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }
}
