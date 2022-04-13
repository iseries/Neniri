<?php
namespace Neniri\App\Domain\Repository;

/*
 * This file is part of the Neniri.App package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Exception\InvalidQueryException;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Persistence\QueryResultInterface;
use Neos\Flow\Persistence\Repository;

/**
 * @author Rene Rehme <contact@renerehme.de>
 * @Flow\Scope("singleton")
 */
class UserRepository extends Repository
{
    /**
     * @return QueryResultInterface
     */
    public function findAllOrderedByUsername(): QueryResultInterface
    {
        return $this->createQuery()
            ->setOrderings(['account.accountIdentifier' => QueryInterface::ORDER_ASCENDING])
            ->execute();
    }

    /**
     * @param string $searchTerm
     * @return QueryResultInterface
     * @throws InvalidQueryException
     */
    public function findBySearchTerm(string $searchTerm): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalOr(
                $query->like('accounts.accountIdentifier', '%'.$searchTerm.'%'),
                $query->like('firstname', '%'.$searchTerm.'%'),
                $query->like('lastname', '%'.$searchTerm.'%'),
                $query->like('company', '%'.$searchTerm.'%'),
                $query->like('phone', '%'.$searchTerm.'%'),
            )
        );
        return $query->setOrderings(['accounts.accountIdentifier' => QueryInterface::ORDER_ASCENDING])->execute();
    }
}
