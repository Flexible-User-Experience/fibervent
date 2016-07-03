<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ActionButtonFormType class
 *
 * @category FormType
 * @package  AppBundle\Form\Type
 * @author   David Romaní <david@flux.cat>
 */
class ActionButtonFormType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(
                array(
                    'text',
                    'url',
                    'css',
                    'icon',
                )
            )
            ->setDefaults(
                array(
                    'text' => 'hack',
                    'url'  => '#',
                    'css'  => 'btn btn-primary',
                    'icon' => 'fa fa-plus-square',
                )
            );
    }

    /**
     * Pass parameters to the view
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('text', $options) && array_key_exists('url', $options) && array_key_exists(
                'css',
                $options
            ) && array_key_exists('icon', $options)
        ) {
            $view->vars['text'] = $options['text'];
            $view->vars['url']  = $options['url'];
            $view->vars['css']  = $options['css'];
            $view->vars['icon'] = $options['icon'];
        }
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'form';
    }

    /**
     * Returns the blockname of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'action_button';
    }
}
