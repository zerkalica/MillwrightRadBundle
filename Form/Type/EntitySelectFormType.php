<?php
namespace Millwright\RadBundle\Form\Type;

use Millwright\RadBundle\Form\AbstractType;
use Millwright\RadBundle\Form\DataTransformer\EntityToIdTransformerInterface;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * GroupSelect form type
 */
class EntitySelectFormType extends AbstractType
{
    /**
     * @var EntityToIdTransformerInterface
     */
    protected $transformer;

    /**
     * Construct
     *
     * @param $transformer
     */
    public function __construct(EntityToIdTransformerInterface $transformer)
    {
        parent::__construct();

        $this->transformer = $transformer;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!isset($options['transform_class'])) {
            throw new \Symfony\Component\Serializer\Exception\InvalidArgumentException('Transform class option must be declared');
        }

        $this->transformer->setClass($options['transform_class']);

        $builder->addModelTransformer($this->transformer);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'show_child_legend' => false,
        ));
    }
}
