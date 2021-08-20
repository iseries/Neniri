<?php
namespace Neniri\App\Domain\Service;

/*
 * This file is part of the Neniri.App package.
 */

use Neniri\App\Domain\Model\AcpUser;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\Policy\Role;
use Neniri\App\Domain\Repository\AcpUserRepository;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Annotations as Flow;

/**
 * The acp user creation service.
 * @Flow\Scope("singleton")
 * @api
 */
class AcpUserCreationService
{
    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject
     * @var AcpUserRepository
     */
    protected $acpUserRepository;

    /**
     * @Flow\InjectConfiguration(path="acpUserRole")
     */
    protected $acpUserRole;

    /**
     * Creates am Account and User
     * @param string $email
     * @param string $password
     * @return AcpUser
     */
    public function createAccountAndUser($email, $password)
    {
        // Create the account
        $account = new Account();
        $account->setAccountIdentifier($email);
        $account->setCredentialsSource($password);
        $account->setAuthenticationProviderName('Neniri.App:AcpUser');
        $account->addRole(new Role($this->acpUserRole));

        // Create the user
        $acpUser = new AcpUser();
        $acpUser->setAccount($account);

        // Persist user
        $this->acpUserRepository->add($acpUser);
        $this->persistenceManager->whitelistObject($acpUser);
        $this->persistenceManager->whitelistObject($account);

        return $acpUser;
    }
}