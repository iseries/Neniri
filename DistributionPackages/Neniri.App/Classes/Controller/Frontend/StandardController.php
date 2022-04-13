<?php
namespace Neniri\App\Controller\Frontend;

/*
 * This file is part of the Neniri.App package.
 */

use Neniri\App\Controller\Frontend\AbstractBaseController;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;

/**
 * @author Rene Rehme <contact@renerehme.de>
 */
class StandardController extends AbstractBaseController
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
