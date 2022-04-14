<?php
namespace Neniri\App\Domain\Repository;

/*
 * This file is part of the Neniri.App package.
 */

use Neniri\App\Domain\Model\RegistrationFlow;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryResultInterface;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 * @method QueryResultInterface findByEmail(string $email)
 * @method RegistrationFlow findOneByEmail(string $email)
 * @method RegistrationFlow findOneByActivationToken(string $token)
 */
class RegistrationFlowRepository extends Repository
{

}