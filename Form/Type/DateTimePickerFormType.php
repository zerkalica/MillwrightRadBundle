<?php
namespace Millwright\RadBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Millwright\RadBundle\Form\AbstractType;

/**
 * Form type for days count input
 */
class DateTimePickerFormType extends DatePickerFormType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'time';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'date_time_picker';
    }
}
