<?php
namespace Millwright\RadBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

use Millwright\RadBundle\Form\AbstractType;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * GroupSelect form type
 */
class ChosenFormType extends AbstractType
{
    protected $noResultMessageTemplate = 'chosen.no_result';
    protected $translator;

    /**
     * Constructor
     *
     * @param TranslatorInterface $translator
     * @param string              $class data model class name
     */
    public function __construct(TranslatorInterface $translator, $class = null)
    {
        $this->translator = $translator;

        parent::__construct($class);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $translator = $this->translator;
        $callback = function(Options $options) use ($translator)
        {
            $domain  = $options->get('translation_domain');
            $message = $translator->trans($this->noResultMessageTemplate, array(), $domain);

            return array(
                'data-placeholder'     => $translator->trans($options->get('label'), array(), $domain),
                'data-no_results_text' => $message,
                'class'                => 'chosen',
            );
        };

        $resolver->setDefaults(array(
            'label_render'         => false,
            'widget_control_group' => false,
            'attr'                 => $callback,
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
        return 'chosen';
    }
}
