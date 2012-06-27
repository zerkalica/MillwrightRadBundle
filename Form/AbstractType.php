<?php
namespace Millwright\RadBundle\Form;

use Symfony\Component\Form\AbstractType as BaseAbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * {@inheritDoc}
 */
abstract class AbstractType extends BaseAbstractType
{
    protected $class;

    /**
     * Constructor
     *
     * @param string      $class data model class name
     */
    public function __construct($class = null)
    {
        $this->class = $class;
    }

    /**
     * Get default form type label
     *
     * @return string
     */
    protected function getDefaultLabel()
    {
        return substr(str_replace('_', '.', strstr($this->getName(), '_')), 1);
    }

    /**
     * Get default form type name
     *
     * @return string
     */
    protected function getDefaultName()
    {
        $parts = explode('\\', get_class($this));
        $parts = array_splice($parts, 2);
        $name  = implode('_', $parts);
        $name  = str_replace(array('Bundle', '_Form', '_Type', 'FormType'), array(''), $name);

        return strtolower($name);
    }

    protected function getDefaultTranslationDomain()
    {
        $parts = explode('\\', get_class($this));

        return $parts[0] . $parts[1];
    }

    /**
     * Get default intention
     *
     * @return string
     */
    protected function getIntention()
    {
        return $this->getName();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->getDefaultName();
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'         => $this->class,
            'label'              => $this->getDefaultLabel(),
            'intention'          => $this->getIntention(),
            'translation_domain' => $this->getDefaultTranslationDomain(),
        ));
    }
}
