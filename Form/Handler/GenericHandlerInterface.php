<?php
namespace Millwright\RadBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;

use Millwright\ComponentFormBundle\Form\Handler\ProcessResult;

/**
 * Generic form handler interface
 */
interface GenericHandlerInterface
{
    /**
     * Process form
     *
     * @return ProcessResult
     */
    function process();
}
