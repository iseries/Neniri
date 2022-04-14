<?php
namespace Neniri\App\Domain\Model;

/*
 * This file is part of the Neniri.App package.
 */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Exception;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Utility\Algorithms;

/**
 * @author Rene Rehme <contact@renerehme.de>
 * @Flow\Entity
 */
class RegistrationFlow
{

    /**
     * @var string
     * @Flow\Transient
     * @Flow\InjectConfiguration(path="activationTokenTimeout")
     */
    protected $activationTokenTimeout;

    /**
     * @var string
     * @Flow\Validate(type="NotEmpty")
     * @Flow\Validate(type="EmailAddress")
     */
    protected string $email;

    /**
     * @var string
     * @ORM\Column(nullable=TRUE)
     */
    protected string $activationToken;

    /**
     * @var \DateTime
     * @ORM\Column(nullable=TRUE)
     */
    protected \DateTime $activationTokenValidUntil;

    /**
     * @var \DateTime
     */
    protected $creationDate;


    /**
     * Constructor
     */
    public function __construct() {
        $this->creationDate = new \DateTime();
    }

    /**
     * @param $cause int The cause of the object initialization.
     * @throws \Exception
     */
    public function initializeObject(int $cause)
    {
        if ($cause === ObjectManagerInterface::INITIALIZATIONCAUSE_CREATED) {
            $this->generateActivationToken();
        }
    }

    /**
     * Generate a new activation token
     *
     * @throws \Exception
     */
    public function generateActivationToken()
    {
        $this->activationToken = Algorithms::generateRandomString(23);
        $this->activationTokenValidUntil = (new \DateTime())->add(\DateInterval::createFromDateString($this->activationTokenTimeout));
    }

    /**
     * Check if the user has a valid activation token.
     *
     * @return bool
     */
    public function hasValidActivationToken(): bool
    {
        return $this->activationTokenValidUntil->getTimestamp() > time();
    }

    /**
     * @return string
     */
    public function getActivationToken(): string
    {
        return $this->activationToken;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @param \DateTime $creationDate
     */
    public function setCreationDate(\DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate() {
        return $this->creationDate;
    }
}