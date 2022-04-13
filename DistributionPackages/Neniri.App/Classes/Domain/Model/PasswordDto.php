<?php
namespace Neniri\App\Domain\Model;

/*
 * This file is part of the Neniri.App package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Exception;
use Neos\Flow\Security\Cryptography\HashService;

/**
 * @author Rene Rehme <contact@renerehme.de>
 */
class PasswordDto
{
    /**
     * @Flow\Inject
     * @var HashService
     */
    protected $hashService;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $passwordConfirmation;

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param string $passwordConfirmation
     */
    public function setPasswordConfirmation($passwordConfirmation)
    {
        $this->passwordConfirmation = $passwordConfirmation;
    }

    /**
     * @return bool
     */
    public function isPasswordEqual()
    {
        if($this->password === $this->passwordConfirmation) {
            return true;
        }
        return false;
    }

    public function cryptPassword()
    {
        $encrypted = $this->hashService->hashPassword($this->password);
        $this->password = null;
        $this->passwordConfirmation = null;
        return $encrypted;
    }
}