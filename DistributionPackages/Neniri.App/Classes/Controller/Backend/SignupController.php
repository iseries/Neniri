<?php
namespace Neniri\App\Controller\Backend;

/*
 * This file is part of the Neniri.App package.
 */

use Neos\FluidAdaptor\View\StandaloneView;
use Neniri\App\Domain\Service\MailerService;
use Neniri\App\Controller\Backend\AbstractBaseController;
use Neos\Flow\Annotations as Flow;

class SignupController extends AbstractBaseController
{
    #[Flow\Inject]
    protected MailerService $mailerService;

    /**
     * @Flow\InjectConfiguration(path="mailer.from")
     * @var string
     */
    protected string $from;

    /**
     * Signup form
     * @return void
     */
    public function indexAction()
    {

    }

    /**
     * Signup form send
     * @return void
     */
    public function signupAction()
    {
        $email = $this->request->getArgument('email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirect('failure', null, null, array('error' => 'NO_VALID_EMAIL'));
        }

        $fluid = new StandaloneView();
        $fluid->setFormat('html');
        $fluid->setLayoutRootPath('resource://Neniri.App/Private/Templates/Mail/Layouts/');
        $fluid->setTemplatePathAndFilename('resource://Neniri.App/Private/Templates/Mail/Signup/ConfirmRegistration.html');
        $fluid->assign('link', true);

        $mailerProperties = array(
           'from' => $this->from,
           'to' => $email,
           'replyTo' => '',
           'cc' => '',
           'bcc' => '',
           'subject' => 'Confirm your registration',
           'body' => $fluid->render(),
        );
        $this->mailerService->send($mailerProperties);

        $this->redirect('success');
    }

    /**
     * Success response
     * @return void
     */
    public function successAction()
    {

    }

    /**
     * Failure response
     * @param string $error
     * @return void
     */
    public function failureAction(string $error)
    {
        $this->view->assign('error', $error);
    }
}
