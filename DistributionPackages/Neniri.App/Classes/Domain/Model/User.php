<?php
namespace Neniri\App\Domain\Model;

/*
 * This file is part of the Neniri.App package.
 */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\Cryptography\HashService;

/**
 * @author Rene Rehme <contact@renerehme.de>
 * @Flow\Entity
 * @ORM\InheritanceType("JOINED")
 */
class User
{
    /**
     * @var Account
     * @ORM\OneToOne(cascade={"persist", "remove"})
     */
    protected Account $account;

    /**
     * @var string
     */
    protected string $firstName;

    /**
     * @var string
     */
    protected string $lastName;

    /**
     * @var string
     */
    protected string $company = '';

    /**
     * @var string
     */
    protected string $phone = '';

    /**
     * @Flow\Inject
     * @Flow\Transient
     * @var HashService
     */
    protected HashService $hashService;


    /**
     * @return string
     */
    public function getAccountIdentifier(): string
    {
        return $this->account->getAccountIdentifier();
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return void
     */
    public function setFirstName(string $firstName) {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return void
     */
    public function setLastName(string $lastName) {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * @param string $company
     * @return void
     */
    public function setCompany(string $company) {
        $this->company = $company;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return void
     */
    public function setPhone(string $phone) {
        $this->phone = $phone;
    }
}