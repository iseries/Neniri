<?php
namespace Neniri\App\Domain\Model;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Exception;
use Neos\Flow\Security\Cryptography\HashService;

class PasswordDto
{
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