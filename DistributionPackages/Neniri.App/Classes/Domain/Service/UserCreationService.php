<?php
namespace Neniri\App\Domain\Service;

/*
 * This file is part of the Neniri.App package.
 */

use Neniri\App\Domain\Model\User;
use Neos\Flow\Persistence\Exception\IllegalObjectTypeException;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\Policy\Role;
use Neniri\App\Domain\Repository\UserRepository;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Annotations as Flow;

/**
 * The acp user creation service.
 *
 * @author Rene Rehme <contact@renerehme.de>
 * @Flow\Scope("singleton")
 * @api
 */
class UserCreationService
{
    #[Flow\Inject]
    protected PersistenceManagerInterface $persistenceManager;

    #[Flow\Inject]
    protected UserRepository $userRepository;

    /**
     * Creates am Account and User
     * @param string $email
     * @param string $password
     * @param string $role
     * @return User
     * @throws IllegalObjectTypeException
     */
    public function createAccountAndUser(string $email, string $password, string $role): User
    {
        // Create the account
        $account = new Account();
        $account->setAccountIdentifier($email);
        $account->setCredentialsSource($password);
        $account->setAuthenticationProviderName($role);
        $account->addRole(new Role($role));

        // Create the user
        $user = new User();
        $user->setAccount($account);

        // Add user
        $this->userRepository->add($user);

        return $user;
    }
}