<?php
namespace Millwright\RadBundle\Form\Type;

use Millwright\RadBundle\Form\DataTransformer\ModelToDataTransformer;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

use Millwright\RadBundle\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;

use Millwright\RadBundle\Form\DataTransformer\ChoicesToValuesTransformer;

use Symfony\Component\Form\Util\PropertyPath;
/**
 * GroupSelect form type
 */
class EntitySelectFormType extends AbstractType
{
    /**
     * Caches created choice lists.
     * @var array
     */
    private $choiceListCache = array();

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new ChoicesToValuesTransformer, true);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $choiceListCache =& $this->choiceListCache;

        $choiceList = function (Options $options) use (&$choiceListCache) {
            // Harden against NULL values (like in EntityType and ModelType)
            $choices = null !== $options['choices'] ? $options['choices'] : array();

            if (is_object($choices)) {
                $choicesKeys = spl_object_hash($choices);
            } else {
                $choicesKeys = array_map(function($choice) {
                    return $choice->getId();
                } , $choices);
            }

            // Reuse existing choice lists in order to increase performance
            $hash = md5(json_encode(array($choicesKeys, $options['preferred_choices'])));

            if (!isset($choiceListCache[$hash])) {
                $choiceListCache[$hash] = new ObjectChoiceList($choices, $options['labelPath'], $options['preferred_choices'], null, $options['valuePath']);
            }

            return $choiceListCache[$hash];
        };

        $emptyData = function (Options $options) {
            if ($options['multiple'] || $options['expanded']) {
                return array();
            }

            return '';
        };

        $emptyValue = function (Options $options) {
            return $options['required'] ? null : '';
        };

        $emptyValueNormalizer = function (Options $options, $emptyValue) {
            if ($options['multiple'] || $options['expanded']) {
                // never use an empty value for these cases
                return null;
            } elseif (false === $emptyValue) {
                // an empty value should be added but the user decided otherwise
                return null;
            }

            // empty value has been set explicitly
            return $emptyValue;
        };

        $compound = function (Options $options) {
            return $options['expanded'];
        };

        $resolver->setDefaults(array(
                'multiple'          => false,
                'expanded'          => false,
                'choice_list'       => $choiceList,
                'choices'           => array(),
                'preferred_choices' => array(),
                'empty_data'        => $emptyData,
                'empty_value'       => $emptyValue,
                'error_bubbling'    => false,
                'compound'          => $compound,

                'labelPath'          => 'name',
                'valuePath'          => 'id',
            ));

        $resolver->setNormalizers(array(
                'empty_value' => $emptyValueNormalizer,
            ));

        $resolver->setAllowedTypes(array(
                'choice_list' => array('null', 'Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface'),
            ));
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'entity_selector';
    }
}
