<?php
namespace Neniri\App\Domain\Model;

/*
 * This file is part of the Neniri.App package.
 */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Security\Cryptography\HashService;

/**
 * @Flow\Entity
 * @ORM\InheritanceType("JOINED")
 */
class AcpUser
{
    /**
     * @var \Neos\Flow\Security\Account
     * @ORM\OneToOne(cascade={"persist", "remove"})
     */
    protected $account;

    /**
     * @Flow\Inject
     * @Flow\Transient
     * @var HashService
     */
    protected $hashService;

    /**
     * @return string
     */
    public function getAccountName()
    {
        return $this->account->getAccountIdentifier();
    }

    /**
     * @return \Neos\Flow\Security\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param \Neos\Flow\Security\Account $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }
}