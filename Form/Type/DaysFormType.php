<?php
namespace Millwright\RadBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Millwright\RadBundle\Form\AbstractType;
use Millwright\RadBundle\Form\DataTransformer\SecondsToDaysTransformer;

/**
 * Form type for days count input
 */
class DaysFormType extends AbstractType
{
    /**
     * @var SecondsToDaysTransformer
     */
    protected $transformer;

    /**
     * Construct
     *
     * @param $transformer
     */
    public function __construct(SecondsToDaysTransformer $transformer)
    {
        parent::__construct();

        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new SecondsToDaysTransformer(
            $options['precision'],
            $options['grouping'],
            $options['rounding_mode']
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            // default precision is locale specific (usually around 3)
            'precision'     => null,
            'grouping'      => false,
            // Integer cast rounds towards 0, so do the same when displaying fractions
            'rounding_mode' => \NumberFormatter::ROUND_DOWN,
            'compound'      => false,
        ));

        $resolver->setAllowedValues(array(
            'rounding_mode' => array(
                \NumberFormatter::ROUND_FLOOR,
                \NumberFormatter::ROUND_DOWN,
                \NumberFormatter::ROUND_HALFDOWN,
                \NumberFormatter::ROUND_HALFEVEN,
                \NumberFormatter::ROUND_HALFUP,
                \NumberFormatter::ROUND_UP,
                \NumberFormatter::ROUND_CEILING,
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'field';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'days';
    }
}
