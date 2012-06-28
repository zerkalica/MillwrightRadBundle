<?php

namespace Millwright\RadBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

interface EntityToIdTransformerInterface extends DataTransformerInterface
{
    /**
     * @param string $class
     */
    function setClass($class);

    /**
     * @return string
     */
    function getClass();
}
