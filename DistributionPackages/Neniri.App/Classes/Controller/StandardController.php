<?php
namespace Neniri\App\Controller;

/*
 * This file is part of the Neniri.App package.
 */

use Neniri\App\Controller\AbstractBaseController;
use Neniri\App\Domain\Service\UserService;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Policy\Role;

/**
 * @author Rene Rehme <contact@renerehme.de>
 */
class StandardController extends AbstractBaseController
{
    #[Flow\Inject]
    protected UserService $userService;

    /**
     * @return void
     */
    public function indexAction()
    {
        $account = $this->securityContext->getAccount();
        if($account) {
            if($account->hasRole(new Role('Neniri.App:Employee'))) {
                $this->redirect('index', 'Backend\Standard');
            }
            $this->redirect('index', 'Frontend\Standard');
        }
        $this->redirect('index', 'Login');
    }
}
