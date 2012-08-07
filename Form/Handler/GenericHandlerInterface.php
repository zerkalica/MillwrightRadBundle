<?php
namespace Millwright\RadBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;

/**
 * Generic form handler interface
 */
interface GenericHandlerInterface
{
    /**
     * Get form view to pass to template
     *
     * @return FormView
     */
    function getView(FormView $parent = null);
}
