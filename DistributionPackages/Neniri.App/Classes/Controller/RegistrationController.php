<?php
namespace Neniri\App\Controller;

/*
 * This file is part of the Neniri.App package.
 */

use Neniri\App\Domain\Model\PasswordDto;
use Neniri\App\Domain\Model\RegistrationFlow;
use Neniri\App\Domain\Model\User;
use Neniri\App\Domain\Repository\RegistrationFlowRepository;
use Neniri\App\Domain\Service\UserCreationService;
use Neos\Error\Messages\Message;
use Neos\FluidAdaptor\View\StandaloneView;
use Neniri\App\Domain\Service\MailerService;
use Neniri\App\Controller\AbstractBaseController;
use Neos\Flow\Annotations as Flow;

/**
 * @author Rene Rehme <contact@renerehme.de>
 */
class RegistrationController extends AbstractBaseController
{
    #[Flow\Inject]
    protected MailerService $mailerService;

    #[Flow\Inject]
    protected UserCreationService $userCreationService;

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
    public function activateAccountAction(string $token)
    {
        // Find a registrationFlow by token
        $registrationFlow = $this->registrationFlowRepository->findOneByActivationToken($token);

        // Check if registrationFlow is given
        if(!$registrationFlow) {
            $this->addFlashMessage('Your activation token could not be found. Please sign up again to get a new activation token.', '', Message::SEVERITY_ERROR, array(), '1650090993');
            $this->redirect('index', 'Registration');
        }

        // Check if token is expired
        if(!$registrationFlow->hasValidActivationToken()) {
            $this->addFlashMessage('Your activation token is expired. Please sign up again to get a new activation token.', '', Message::SEVERITY_ERROR, array(), '1650091858');
            $this->redirect('index', 'Registration');
        }

        $this->view->assign('registrationFlow', $registrationFlow);
    }

    /**
     * Create a user and remove the given registrationFlow
     *
     * @param RegistrationFlow $registrationFlow
     */
    public function activateAccountProcessAction(RegistrationFlow $registrationFlow)
    {
        $passwordDto = new PasswordDto();
        $passwordDto->setPassword($this->request->getArgument('password'));
        $passwordDto->setPasswordConfirmation($this->request->getArgument('passwordRepeat'));

        if(!$passwordDto->isPasswordEqual()) {
            // password is not equal
            $this->addFlashMessage('Password and confirmation does not match.', '', Message::SEVERITY_ERROR, array(), '1650102970');
            $this->redirect('activateAccount', null, null, array('token' => $registrationFlow->getActivationToken()));
        }

        // additional data
        $additionalData = array(
            'firstname' => $this->request->getArgument('firstname'),
            'lastname' => $this->request->getArgument('lastname'),
        );

        // create user and remove registrationFlow
        $this->userCreationService->createAccountAndUser($registrationFlow->getEmail(), $passwordDto->cryptPassword(), 'Neniri.App:Customer', $additionalData);
        $this->registrationFlowRepository->remove($registrationFlow);
        $this->addFlashMessage('Your account was created and activated. You can log in now.', '', Message::SEVERITY_OK, array(), '1650109374');

        $this->redirect('index', 'Login');
    }
}
