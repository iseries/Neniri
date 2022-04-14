<?php
namespace Neniri\App\Controller\Backend;

/*
 * This file is part of the Neniri.App package.
 */

use Neniri\App\Domain\Model\RegistrationFlow;
use Neniri\App\Domain\Repository\RegistrationFlowRepository;
use Neos\FluidAdaptor\View\StandaloneView;
use Neniri\App\Domain\Service\MailerService;
use Neniri\App\Controller\Backend\AbstractBaseController;
use Neos\Flow\Annotations as Flow;

/**
 * @author Rene Rehme <contact@renerehme.de>
 */
class RegistrationController extends AbstractBaseController
{
    #[Flow\Inject]
    protected MailerService $mailerService;

    #[Flow\Inject]
    protected RegistrationFlowRepository $registrationFlowRepository;

    #[Flow\InjectConfiguration(path: 'mailer.from')]
    protected string $from;


    /**
     * Signup form
     *
     * @return void
     */
    public function indexAction()
    {

    }

    /**
     * Signup form send
     *
     * @return void
     */
    public function signupAction()
    {
        $email = $this->request->getArgument('email');

        // if email adress is not valid, we redirect to an failure page
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirect('failure', null, null, array('error' => 'NO_VALID_EMAIL'));
        }

        // remove already existing registration flows
        $alreadyExistingFlows = $this->registrationFlowRepository->findByEmail($email);
        if (count($alreadyExistingFlows) > 0) {
            foreach ($alreadyExistingFlows as $alreadyExistingFlow) {
                $this->registrationFlowRepository->remove($alreadyExistingFlow);
            }
        }

        // add registration flow
        $registrationFlow = new RegistrationFlow();
        $registrationFlow->setEmail($email);
        $this->registrationFlowRepository->add($registrationFlow);

        // create activation link
        $activationLink = $this->uriBuilder->reset()->setCreateAbsoluteUri(true)->uriFor('activateAccount', ['token' => $registrationFlow->getActivationToken()],'Registration');

        // create and send email
        $fluid = new StandaloneView();
        $fluid->setFormat('html');
        $fluid->setLayoutRootPath('resource://Neniri.App/Private/Templates/Mail/Layouts/');
        $fluid->setTemplatePathAndFilename('resource://Neniri.App/Private/Templates/Mail/Registration/ConfirmRegistration.html');
        $fluid->assign('activationLink', $activationLink);

        $mailerProperties = array(
           'from' => $this->from,
           'to' => $email,
           'replyTo' => '',
           'cc' => '',
           'bcc' => '',
           'subject' => '💡 Confirm your registration',
           'body' => $fluid->render(),
        );
        $this->mailerService->send($mailerProperties);

        $this->redirect('success');
    }

    /**
     * Success response
     *
     * @return void
     */
    public function successAction()
    {

    }

    /**
     * Failure response
     *
     * @param string $error
     * @return void
     */
    public function failureAction(string $error)
    {
        $this->view->assign('error', $error);
    }

    /**
     * Activate an account
     *
     * @param string $token
     */
    public function activateAccountAction($token)
    {
        \Neos\Flow\var_dump($token);
    }
}
