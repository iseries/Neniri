<?php
namespace Neniri\App\Command;

/*
 * This file is part of the Neniri.App package.
 */

use Neniri\App\Domain\Service\UserCreationService;
use Neniri\App\Domain\Model\PasswordDto;
use Neos\Flow\Cli\Exception\StopCommandException;
use Neos\Flow\Package\PackageManager;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Cli\ConsoleOutput;
use Neos\Flow\Cli\Request;
use Neos\Flow\Cli\Response;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Mvc\Routing\ObjectPathMappingRepository;

class UserCommandController extends CommandController
{
    #[Flow\Inject]
    protected UserCreationService $userCreationService;

    #[Flow\Inject]
    protected PersistenceManagerInterface $persistenceManager;

    #[Flow\Inject]
    protected ConsoleOutput $consoleOutput;

    /**
     * Create a User
     *
     * @param string $email The email address, which also serves as the username.
     * @param string $password This user's password.
     * @param string $passwordRepeat This user's password again.
     * @param boolean $employee Is this user an employee?
     * @return void
     * @throws StopCommandException
     */
    public function createCommand(string $email, string $password, string $passwordRepeat, bool $employee = true): void
    {
        $passwordDto = new PasswordDto();
        $passwordDto->setPassword($password);
        $passwordDto->setPasswordConfirmation($passwordRepeat);

        if(!$passwordDto->isPasswordEqual()) {
            $this->outputLine('The passwords do not match.');
            $this->quit(0);
        }

        $role = 'Neniri.App:Customer';
        if($employee) {
            $role = 'Neniri.App:Employee';
        }

        $this->userCreationService->createAccountAndUser($email, $passwordDto->cryptPassword(), $role);
        $this->persistenceManager->persistAll();

        $this->outputLine('The ACP User <b>"%s"</b> with password <b>"%s"</b> was added.', [$email, $password]);
    }
}