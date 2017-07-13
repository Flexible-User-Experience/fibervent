<?php

namespace AppBundle\Service;

use AppBundle\Entity\Audit;
use AppBundle\Entity\User;
use AppBundle\Enum\UserRolesEnum;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * Class AuthCustomerService.
 *
 * @category Service
 *
 * @author   David Romaní <david@flux.cat>
 */
class AuthCustomerService
{
    /**
     * @var AuthorizationChecker
     */
    private $acs;

    /**
     * @var TokenStorage
     */
    private $tss;

    /**
     * Methods.
     */

    /**
     * AuthCustomerService constructor.
     *
     * @param AuthorizationChecker $acs
     * @param TokenStorage         $tss
     */
    public function __construct(AuthorizationChecker $acs, TokenStorage $tss)
    {
        $this->acs = $acs;
        $this->tss = $tss;
    }

    /**
     * @param Audit $audit
     *
     * @return bool
     */
    public function isAuditOwnResource(Audit $audit)
    {
        if ($this->acs->isGranted(UserRolesEnum::ROLE_CUSTOMER) && $audit->getCustomer()->getId() == $this->getUser()->getCustomer()->getId()) {
            return true;
        }

        return false;
    }

    /**
     * @return User
     */
    private function getUser()
    {
        return $this->tss->getToken()->getUser();
    }
}
