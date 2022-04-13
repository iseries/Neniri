<?php
namespace Neniri\App\Command;

/*
 * This file is part of the Neniri.App package.
 */

use Neniri\App\Domain\Model\User;
use Neniri\App\Domain\Repository\UserRepository;
use Neniri\App\Domain\Service\UserCreationService;
use Neniri\App\Domain\Model\PasswordDto;
use Neniri\App\Domain\Service\UserService;
use Neos\Flow\Cli\Exception\StopCommandException;
use Neos\Flow\Package\PackageManager;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Cli\ConsoleOutput;
use Neos\Flow\Cli\Request;
use Neos\Flow\Cli\Response;
use Neos\Flow\Persistence\Exception\IllegalObjectTypeException;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Mvc\Routing\ObjectPathMappingRepository;
use Neos\Flow\Security\Account;

class UserCommandController extends CommandController
{
    #[Flow\Inject]
    protected UserCreationService $userCreationService;

    #[Flow\Inject]
    protected UserService $userService;

    #[Flow\Inject]
    protected PersistenceManagerInterface $persistenceManager;

    #[Flow\Inject]
    protected ConsoleOutput $consoleOutput;


    /**
     * Command to create a User
     *
     * Create a employee user:
     * ./flow user:create
     *
     * Create a customer user:
     * ./flow user:create --customer
     *
     * @param string $email The email address, which also serves as the username.
     * @param string $password This user's password.
     * @param string $passwordRepeat This user's password again.
     * @param boolean $customer Is this user a customer? (true/false)
     * @return void
     * @throws StopCommandException|IllegalObjectTypeException
     */
    public function createCommand(string $email, string $password, string $passwordRepeat, bool $customer = false): void
    {
        $passwordDto = new PasswordDto();
        $passwordDto->setPassword($password);
        $passwordDto->setPasswordConfirmation($passwordRepeat);

        if(!$passwordDto->isPasswordEqual()) {
            $this->outputLine('The passwords do not match.');
            $this->quit(0);
        }

        $role = 'Neniri.App:Employee';
        if($customer) {
            $role = 'Neniri.App:Customer';
        }

        $this->userCreationService->createAccountAndUser($email, $passwordDto->cryptPassword(), $role);
        $this->persistenceManager->persistAll();

        $this->outputLine('The User <b>"%s"</b> with password <b>"%s"</b> and role <b>"%s"</b> was added.', [$email, $password, $role]);
    }

    /**
     * Remove a User
     *
     * @param string $email
     * @return void
     * @throws IllegalObjectTypeException
     */
    public function removeCommand(string $email)
    {
        $removed = $this->userService->removeUser($email);
        if($removed) {
            $this->outputLine('The User <b>"%s"</b> was removed.', [$email]);
        } else {
            $this->outputLine('The User <b>"%s"</b> was not found.', [$email]);
        }
    }

    /**
     * List all users
     *
     * @return void
     */
    public function listCommand()
    {
        $users = $this->userService->getUsers();

        $tableRows = [];
        $headerRow = ['Name', 'Email', 'Account(s)', 'Role(s)', 'Active'];

        foreach ($users as $user) {
            $tableRows[] = $this->getTableRowForUser($user);
        }

        $this->output->outputTable($tableRows, $headerRow);
    }

    /**
     * Prepares a table row for output with data of the given User
     *
     * @param User $user The user
     * @return array
     */
    protected function getTableRowForUser(User $user): array
    {
        $roleNames = [];
        $accountIdentifiers = [];
        $account = $user->getAccount();

        foreach ($account->getRoles() as $role) {
            /** @var Role $role */
            $roleNames[] = $role->getIdentifier();
        }
        return [$user->getFullname(), $account->getAccountIdentifier(), $account->getAuthenticationProviderName(), implode(', ', $roleNames), 'yes'];
    }
}