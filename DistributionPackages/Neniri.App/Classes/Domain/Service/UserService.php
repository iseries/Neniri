<?php
namespace Neniri\App\Domain\Service;

/*
 * This file is part of the Neniri.App package.
 */

use Neniri\App\Domain\Model\User;
use Neniri\App\Domain\Repository\UserRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Exception\InvalidQueryException;
use Neos\Flow\Persistence\QueryResultInterface;
use Neos\Flow\Security\AccountRepository;
use Neos\Neos\Utility\User as UserUtility;

/**
 * The user service provides general context information about the currently authenticated acp user.
 *
 * @author Rene Rehme <contact@renerehme.de>
 * @Flow\Scope("singleton")
 * @api
 */
class UserService
{
    #[Flow\Inject]
    protected UserRepository $userRepository;

    #[Flow\Inject]
    protected AccountRepository $accountRepository;

    protected string $employeeAuthenticationProviderName = 'Neniri.App:User';

    /**
     * Returns all existing users
     *
     * @return QueryResultInterface
     * @api
     */
    public function getUsers(): QueryResultInterface
    {
        return $this->userRepository->findAllOrderedByUsername();
    }

    /**
     * Search users by search term
     *
     * @param string $searchTerm
     * @return QueryResultInterface
     * @throws InvalidQueryException
     */
    public function searchUsers(string $searchTerm): QueryResultInterface
    {
        return $this->userRepository->findBySearchTerm($searchTerm);
    }

    /**
     * Retrieves an existing user by the given username
     *
     * @param string $email
     * @return User|null
     * @api
     */
    public function getUser(string $email): ?User
    {
        $account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($email, $this->employeeAuthenticationProviderName);
        if ($account === null) {
            return null;
        }

        return $this->userRepository->findOneByAccount($account);
    }

    /**
     * Removes an existing user
     *
     * @param string $user
     * @return boolean
     * @api
     */
    public function removeUser(string $email): bool
    {
        $user = $this->getUser($email);
        if($user) {
            $this->userRepository->remove($user);
            return true;
        }
        return false;
    }
}