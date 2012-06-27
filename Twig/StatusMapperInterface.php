<?php
namespace Millwright\RadBundle\Twig;

/**
 * Status mapper interface
 */
interface StatusMapperInterface
{
    /**
     * Get options map
     *
     * @param array $items include only this items in options map
     *
     * @return array
     */
    function all(array $items = array());

    /**
     * Convert single status value to text representation
     *
     * @param int $status
     *
     * @return string
     */
    function get($status);
}
