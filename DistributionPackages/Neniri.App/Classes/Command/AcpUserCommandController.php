<?php
namespace Neniri\App\Command;

/*
 * This file is part of the Neniri.App package.
 */

use Neos\Flow\Package\PackageManager;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Cli\ConsoleOutput;
use Neos\Flow\Cli\Request;
use Neos\Flow\Cli\Response;
use Neos\Flow\Persistence\PersistenceManagerInterface as PersistenceManagerInterface;
use Neos\Flow\Mvc\Routing\ObjectPathMappingRepository as ObjectPathMappingRepository;

class AcpUserCommandController extends CommandController
{
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

}