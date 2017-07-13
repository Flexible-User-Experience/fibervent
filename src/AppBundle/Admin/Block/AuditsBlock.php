<?php

namespace AppBundle\Admin\Block;

use AppBundle\Enum\AuditStatusEnum;
use AppBundle\Service\AuthCustomerService;
use Doctrine\ORM\EntityManager;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AuditsBlock.
 *
 * @category Block
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class AuditsBlock extends AbstractBlockService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var AuthCustomerService
     */
    private $acs;

    /**
     * Methods.
     */

    /**
     * Constructor.
     *
     * @param string              $name
     * @param EngineInterface     $templating
     * @param EntityManager       $em
     * @param AuthCustomerService $acs
     */
    public function __construct($name, EngineInterface $templating, EntityManager $em, AuthCustomerService $acs)
    {
        parent::__construct($name, $templating);
        $this->em = $em;
        $this->acs = $acs;
    }

    /**
     * Execute.
     *
     * @param BlockContextInterface $blockContext
     * @param Response              $response
     *
     * @return Response
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $doingAudits = array();
        $pendingAudits = array();

        if ($this->acs->isCustomerUser()) {
            $doingAudits = $this->em->getRepository('AppBundle:Audit')->getDoingAuditsByCustomerAmount($this->acs->getCustomer());
            $pendingAudits = $this->em->getRepository('AppBundle:Audit')->getPendingAuditsByCustomerAmount($this->acs->getCustomer());
        } else {
            $doingAudits = $this->em->getRepository('AppBundle:Audit')->getDoingAuditsAmount();
            $pendingAudits = $this->em->getRepository('AppBundle:Audit')->getPendingAuditsAmount();
        }

        return $this->renderResponse(
            $blockContext->getTemplate(),
            array(
                'block' => $blockContext->getBlock(),
                'settings' => $blockContext->getSettings(),
                'title' => 'Estat Auditories',
                'doing_audits' => $doingAudits,
                'pending_audits' => $pendingAudits,
                'status_pending' => AuditStatusEnum::PENDING,
                'status_doing' => AuditStatusEnum::DOING,
            ),
            $response
        );
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return 'done_audits';
    }

    /**
     * Define the default options for the block.
     *
     * @param OptionsResolver $resolver
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'title' => 'Resume',
                'content' => 'Default content',
                'template' => '::Admin/Blocks/block_audits.html.twig',
            )
        );
    }
}
