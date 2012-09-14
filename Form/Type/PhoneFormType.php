<?php
namespace Millwright\RadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Partner ownership create form type
 */
class PhoneFormType extends AbstractType
{
    /**
     * @var DataTransformerInterface
     */
    protected $transformer;

    /**
     * Constructor
     *
     * @param DataTransformerInterface $transformer
     */
    public function __construct(DataTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this->transformer);
    }

    /**
     * @see Symfony\Component\Form\AbstractType::getParent()
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'phone';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'widget_addon' => array(
                'type' => 'prepend',
                'text' => '+',
            ),
        ));
    }
}
