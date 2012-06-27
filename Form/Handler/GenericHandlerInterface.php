<?php
namespace Millwright\RadBundle\Form\Handler;

use Symfony\Component\Form\Form;

/**
 * Generic form handler interface
 */
interface GenericHandlerInterface
{
    /**
     * Get associated with form handler form
     *
     * @return Form
     */
    function getForm();
}
