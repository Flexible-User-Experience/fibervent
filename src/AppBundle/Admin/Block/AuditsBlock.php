<?php

namespace AppBundle\Admin\Block;

use AppBundle\Enum\AuditStatusEnum;
use Doctrine\ORM\EntityManager;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AuditsBlock
 *
 * @category Block
 * @package  AppBundle\Admin\Block
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class AuditsBlock extends BaseBlockService
{
    /** @var EntityManager */
    private $em;

    /**
     * Constructor
     *
     * @param string          $name
     * @param EngineInterface $templating
     * @param EntityManager   $em
     */
    public function __construct($name, EngineInterface $templating, EntityManager $em)
    {
        parent::__construct($name, $templating);
        $this->em = $em;
    }

    /**
     * Execute
     *
     * @param BlockContextInterface $blockContext
     * @param Response              $response
     *
     * @return Response
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        return $this->renderResponse(
            $blockContext->getTemplate(),
            array(
                'block'          => $blockContext->getBlock(),
                'settings'       => $blockContext->getSettings(),
                'title'          => 'Estat Auditories',
                'doing_audits'   => $this->em->getRepository('AppBundle:Audit')->getDoingAuditsAmount(),
                'pending_audits' => $this->em->getRepository('AppBundle:Audit')->getPendingAuditsAmount(),
                'status_pending' => AuditStatusEnum::PENDING,
                'status_doing'   => AuditStatusEnum::DOING,
            ),
            $response
        );
    }

    /**
     * Get name
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
                'title'    => 'Resume',
                'content'  => 'Default content',
                'template' => '::Admin/Blocks/block_audits.html.twig',
            )
        );
    }
}
