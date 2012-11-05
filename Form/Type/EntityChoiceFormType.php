<?php
namespace Millwright\RadBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

use Millwright\RadBundle\Form\DataTransformer\ModelToDataTransformer;
use Millwright\RadBundle\Form\DataTransformer\ChoicesToValuesTransformer;
use Millwright\RadBundle\Form\Extension\TreeObjectChoiceList;
use Millwright\RadBundle\Form\AbstractType;

use Millwright\RadBundle\Model\TreeInterface;

/**
 * GroupSelect form type
 */
class EntityChoiceFormType extends AbstractType
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

            if ($choices instanceof \Doctrine\Common\Collections\Collection) {
                $choices = $choices->toArray();
            }

            $choicesKeys = array_map(function($choice) {
                return $choice->getId();
            } , $choices);


            // Reuse existing choice lists in order to increase performance
            $hash = md5(json_encode(array($choicesKeys, $options['preferred_choices'])));

            if (!isset($choiceListCache[$hash])) {
                if ($options['is_tree']) {
                    $choiceListCache[$hash] = new TreeObjectChoiceList(
                        $choices,
                        $options['label_path'],
                        $options['preferred_choices'],
                        $options['excluded_choices'],
                        $options['group_path'],
                        $options['value_path'],
                        $options['prefix_sign']
                    );
                } else {
                    $choiceListCache[$hash] = new ObjectChoiceList(
                        $choices,
                        $options['label_path'],
                        $options['preferred_choices'],
                        $options['group_path'],
                        $options['value_path']
                    );
                }
            }

            return $choiceListCache[$hash];
        };

        $resolver->setDefaults(array(
            'choice_list'       => $choiceList,
            'choices'           => array(),
            'preferred_choices' => array(),
            'excluded_choices'  => array(),
            'label_path'        => 'name',
            'value_path'        => 'id',
            'group_path'        => null,
            'prefix_sign'       => '-',
            'is_tree'           => false
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'chosen';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'entity_choice';
    }
}
