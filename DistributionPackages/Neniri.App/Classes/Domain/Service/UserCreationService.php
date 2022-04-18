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
 * User creation service.
 *
 * @author Rene Rehme <contact@renerehme.de>
 * @Flow\Scope("singleton")
 * @api
 */
class UserCreationService
{

    protected string $employeeAuthenticationProviderName = 'Neniri.App:User';

    protected array $additionalData = array(
        'firstname' => '',
        'lastname' => '',
        'company' => '',
        'phone' => ''
    );

    #[Flow\Inject]
    protected PersistenceManagerInterface $persistenceManager;

    #[Flow\Inject]
    protected UserRepository $userRepository;

    /**
     * Creates am Account and User
     * @param string $email
     * @param string $password
     * @param string $role
     * @param array $additionalData
     * @return User
     */
    public function createAccountAndUser(string $email, string $password, string $role, array $additionalData = array()): User
    {
        // Create the account
        $account = new Account();
        $account->setAccountIdentifier($email);
        $account->setCredentialsSource($password);
        $account->setAuthenticationProviderName($this->employeeAuthenticationProviderName);
        $account->addRole(new Role($role));

        // Create the user
        $user = new User();
        $user->setAccount($account);

        if(empty($additionalData)) {
            $additionalData = $this->additionalData;
        }
        $user->setFirstName(($additionalData['firstname'] ?? ''));
        $user->setLastName(($additionalData['lastname'] ?? ''));
        $user->setCompany(($additionalData['company'] ?? ''));
        $user->setPhone(($additionalData['phone'] ?? ''));

        // Add user
        $this->userRepository->add($user);

        return $user;
    }
}