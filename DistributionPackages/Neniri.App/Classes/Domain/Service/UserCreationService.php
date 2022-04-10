<?php
namespace Neniri\App\Domain\Service;

/*
 * This file is part of the Neniri.App package.
 */

use Neniri\App\Domain\Model\User;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\Policy\Role;
use Neniri\App\Domain\Repository\UserRepository;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Annotations as Flow;

/**
 * The acp user creation service.
 * @Flow\Scope("singleton")
 * @api
 */
class UserCreationService
{
    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @Flow\InjectConfiguration(path="userRole")
     */
    protected $userRole;

    /**
     * Creates am Account and User
     * @param string $email
     * @param string $password
     * @return User
     */
    public function createAccountAndUser($email, $password)
    {
        // Create the account
        $account = new Account();
        $account->setAccountIdentifier($email);
        $account->setCredentialsSource($password);
        $account->setAuthenticationProviderName('Neniri.App:User');
        $account->addRole(new Role($this->userRole));

        // Create the user
        $user = new User();
        $user->setAccount($account);

        // Add user
        $this->userRepository->add($user);

        return $user;
    }
}