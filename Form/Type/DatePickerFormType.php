<?php
namespace Millwright\RadBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Millwright\RadBundle\Form\AbstractType;

/**
 * Form type for days count input
 */
class DatePickerFormType extends AbstractType
{
    protected $dateFormat;

    /**
     * @param null|string $dateFormat
     */
    public function __construct($dateFormat = 'dd-MM-yyyy')
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'single_text',
            'format' => $this->dateFormat,
            'attr'   => array('class' => 'date')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'date';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'date_picker';
    }
}
