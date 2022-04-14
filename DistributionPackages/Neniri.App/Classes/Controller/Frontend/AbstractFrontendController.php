<?php
namespace Neniri\App\Controller\Frontend;

/*
 * This file is part of the Neniri.App package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\View\ViewInterface;
use Neos\Flow\Mvc\Controller\ControllerContext;
use Neos\Flow\Security\Context;

/**
 * @author Rene Rehme <contact@renerehme.de>
 */
abstract class AbstractFrontendController extends ActionController
{
    #[Flow\Inject]
    protected Context $securityContext;


    /**
     * Initializes the controller before invoking an action method.
     *
     * @return void
     */
    protected function initializeAction() {

    }

    /**
     * @return void
     */
    protected function initializeView(ViewInterface $view) {

    }
}