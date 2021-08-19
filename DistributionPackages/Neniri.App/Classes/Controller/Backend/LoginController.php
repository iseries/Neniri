<?php
namespace Neniri\App\Controller\Backend;

/*
 * This file is part of the Neniri.App package.
 */

use Neniri\App\Controller\Backend\AbstractBaseController;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;

class LoginController extends AbstractBaseController
{
    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('foos', array(
            'bar', 'baz'
        ));
    }
}
