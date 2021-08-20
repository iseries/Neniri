<?php
namespace Neniri\App\Controller\Backend;

/*
 * This file is part of the Neniri.App package.
 */

use Psr\Http\Message\UriFactoryInterface;
use Neos\Flow\Security\Authentication\Controller\AbstractAuthenticationController;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\ActionRequest;

class LoginController extends AbstractAuthenticationController
{
    /**
     * @Flow\Inject
     * @var UriFactoryInterface
     */
    protected $uriFactory;

    /**
     * Show the login form
     * @return void
     */
    public function indexAction()
    {

    }

    /**
     * Is called after a request has been authenticated.
     * @param \Neos\Flow\Mvc\ActionRequest $originalRequest
     * @return string
     */
    protected function onAuthenticationSuccess(ActionRequest $originalRequest = null)
    {
        if ($originalRequest !== null) {
            $this->redirectToRequest($originalRequest);
        }
        $this->redirect('index', 'Standard');
    }

    /**
     * Logs the user out and redirects the user to the login form
     * @return void
     */
    public function logoutAction() {
        parent::logoutAction();
        $this->addFlashMessage('Logout successful');
        $this->redirect('index');
    }

    /**
     * Disable the default error flash message
     * @return boolean
     */
    protected function getErrorFlashMessage()
    {
        return false;
    }
}
