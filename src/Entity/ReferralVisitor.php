<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReferralVisitorRepository")
 */
class ReferralVisitor
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $referralHash;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $visitorIp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReferralHash(): ?string
    {
        return $this->referralHash;
    }

    public function setReferralHash(string $referralHash): self
    {
        $this->referralHash = $referralHash;

        return $this;
    }

    public function getVisitorIp(): ?string
    {
        return $this->visitorIp;
    }

    public function setVisitorIp(string $visitorIp): self
    {
        $this->visitorIp = $visitorIp;

        return $this;
    }
}
