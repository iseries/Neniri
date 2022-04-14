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
    #[Flow\Inject]
    protected HashService $hashService;

    /**
     * @var string
     */
    protected string $password;

    /**
     * @var string
     */
    protected string $passwordConfirmation;

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @param string $passwordConfirmation
     */
    public function setPasswordConfirmation(string $passwordConfirmation)
    {
        $this->passwordConfirmation = $passwordConfirmation;
    }

    /**
     * @return bool
     */
    public function isPasswordEqual(): bool
    {
        if($this->password === $this->passwordConfirmation) {
            return true;
        }
        return false;
    }

    public function cryptPassword(): string
    {
        $encrypted = $this->hashService->hashPassword($this->password);
        $this->password = '';
        $this->passwordConfirmation = '';
        return $encrypted;
    }
}