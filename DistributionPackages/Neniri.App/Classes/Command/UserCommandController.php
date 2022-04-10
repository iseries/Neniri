<?php
namespace Neniri\App\Command;

/*
 * This file is part of the Neniri.App package.
 */

use Neniri\App\Domain\Service\UserCreationService;
use Neniri\App\Domain\Model\PasswordDto;
use Neos\Flow\Package\PackageManager;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Cli\ConsoleOutput;
use Neos\Flow\Cli\Request;
use Neos\Flow\Cli\Response;
use Neos\Flow\Persistence\PersistenceManagerInterface as PersistenceManagerInterface;
use Neos\Flow\Mvc\Routing\ObjectPathMappingRepository as ObjectPathMappingRepository;

class UserCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var UserCreationService
     */
    protected $userCreationService;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject
     * @var ConsoleOutput
     */
    protected $consoleOutput;

    /**
     * Create User on the Command Line
     *
     * @param string $email The email address, which also serves as the username.
     * @param string $password This user's password.
     * @param string $passwordRepeat This user's password again.
     */
    public function createCommand($email, $password, $passwordRepeat)
    {
        $passwordDto = new PasswordDto();
        $passwordDto->setPassword($password);
        $passwordDto->setPasswordConfirmation($passwordRepeat);

        if(!$passwordDto->isPasswordEqual()) {
            $this->outputLine('The passwords do not match.');
            $this->quit(0);
        }

        $this->userCreationService->createAccountAndUser($email, $passwordDto->cryptPassword());
        $this->persistenceManager->persistAll();

        $this->outputLine('The ACP User <b>"%s"</b> with password <b>"%s"</b> was added.', [$email, $password]);
    }
}