<?php
namespace Millwright\RadBundle\Model;

interface TreeInterface
{
    /**
     * Set parent Tree node of the Tree
     *
     * @param TreeInterface $parent
     *
     * @return TreeInterface
     */
    function setParent(TreeInterface $parent);

    /**
     * Get parent group
     *
     * @return TreeInterface
     */
    function getParent();

    /**
     * Get child groups
     *
     * @return TreeInterface[]|null
     */
    function getChildren();
}
