<?php
namespace Millwright\RadBundle\Form\Type;

use Millwright\RadBundle\Form\AbstractType;
use Millwright\RadBundle\Form\DataTransformer\ModelToDataTransformer;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * GroupSelect form type
 */
class EntitySelectFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ModelToDataTransformer($options['collection'], $options['labelPath'], $options['valuePath']);

        $builder->resetViewTransformers();
        $builder->addViewTransformer($transformer);
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
            'show_child_legend'  => false,
            'translation_domain' => null,
            'collection'         => array(),
            'labelPath'          => 'name',
            'valuePath'          => 'id'
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'entity_selector';
    }
}
