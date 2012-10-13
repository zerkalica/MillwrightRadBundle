<?php
namespace Millwright\RadBundle\Form;

use Symfony\Component\Form\AbstractType as BaseAbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Util\ClassUtils;

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
     * Get default form type name
     *
     * @return string
     */
    protected function getDefaultName()
    {
        $parts = explode('\\', ClassUtils::getRealClass($this));
        $parts = array_splice($parts, -2);
        $name  = str_replace(array('Form', 'Type', 'FormType'), array(''), implode('_', $parts));

        return strtolower($name);
    }

    protected function getDefaultTranslationDomain()
    {
        $parts = explode('\\', ClassUtils::getRealClass($this));

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
            'label'              => null,
            'intention'          => $this->getIntention(),
            'translation_domain' => $this->getDefaultTranslationDomain(),
            'show_child_legend'  => true,
        ));
    }
}
