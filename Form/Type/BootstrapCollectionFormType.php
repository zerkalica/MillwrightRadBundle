<?php
namespace Millwright\RadBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

use Millwright\RadBundle\Form\AbstractType;

/**
 * ChosenCollection form type
 */
class BootstrapCollectionFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $closure = function(Options $options) {
            return array(
                'context' => $options->get('context'),
                'widget_control_group' => false,
                'widget_remove_btn'    => array('label' => ' ', 'attr' => array('class' => 'btn')),
            );
        };

        $resolver->setDefaults(array(
            'allow_add'      => true,
            'allow_delete'   => true,
            'prototype'      => true,
            'show_legend'    => false,
            'widget_add_btn' => array('label' => ' ', 'attr' => array('class' => 'btn')),
            'context'        => null,
            'options'        => $closure
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'collection';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'bootstrap_collection';
    }
}
