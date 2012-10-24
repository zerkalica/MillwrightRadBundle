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
        return 'datetime';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'single_text',
            'format' => $this->dateFormat,
            'attr'   => array('class' => 'date-time-picker')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'date_time_picker';
    }
}
