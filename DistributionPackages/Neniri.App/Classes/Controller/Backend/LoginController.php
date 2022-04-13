<?php
namespace Neniri\App\Controller\Backend;

/*
 * This file is part of the Neniri.App package.
 */

use Psr\Http\Message\UriFactoryInterface;
use Neos\Flow\Security\Exception\AuthenticationRequiredException;
use Neos\Flow\Security\Authentication\Controller\AbstractAuthenticationController;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Error\Messages\Message;

/**
 * @author Rene Rehme <contact@renerehme.de>
 */
class LoginController extends AbstractAuthenticationController
{
    #[Flow\Inject]
    protected UriFactoryInterface $uriFactory;


    /**
     * Show the login form
     * @return void
     */
    public function indexAction()
    {

    }

    /**
     * Is called if authentication succeed.
     * @param ActionRequest|null $originalRequest
     * @return void
     */
    protected function onAuthenticationSuccess(ActionRequest $originalRequest = null)
    {
        if ($originalRequest !== null) {
            $this->redirectToRequest($originalRequest);
        }
        $this->redirect('index', 'Backend\Standard');
    }

    /**
     * Is called if authentication failed.
     * @param AuthenticationRequiredException|null $exception
     * @return void
     */
    protected function onAuthenticationFailure(AuthenticationRequiredException $exception = null)
    {
        $this->addFlashMessage('Authentication failed.', '', Message::SEVERITY_ERROR);
        $this->redirect('index');
    }

    /**
     * Logs the user out and redirects the user to the login form
     * @return void
     */
    public function logoutAction()
    {
        parent::logoutAction();
        $this->addFlashMessage('Logout successful');
        $this->redirect('index');
    }

    /**
     * Disable the default error flash message
     * @return boolean
     */
    protected function getErrorFlashMessage(): bool
    {
        return false;
    }
}
