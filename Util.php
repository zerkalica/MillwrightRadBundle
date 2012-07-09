<?php
namespace Millwright\RadBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Rad static utils class
 */
final class Util
{
    /**
     * Get definitions by tag, sort, assiciate with keys and injects to specified service argument
     *
     * @param string           $serviceName
     * @param string           $tag
     * @param ContainerBuilder $container
     * @param int              $arg
     * @param boolean          $aggregate if true - result is array of definitions arrays, aggregated by type key
     *
     * @return \Symfony\Component\DependencyInjection\Definition
     */
    public static function addDefinitionsToService($tag, $serviceName, $arg, ContainerBuilder $container, $aggregate = false)
    {
        $definitions = self::getDefinitionsByTag($tag, $container, $aggregate);

        return $container->getDefinition($serviceName)->replaceArgument($arg, $definitions);
    }

    /**
     * Get service definitions from container by tag
     * Sort by priority and associate with keys
     *
     * @param string           $tag
     * @param ContainerBuilder $container
     * @param boolean          $aggregate if true - result is array of definitions arrays, aggregated by type key
     *
     * @return Definition[]
     */
    public static function getDefinitionsByTag($tag, ContainerBuilder $container, $aggregate = false)
    {
        $containers = new \SplPriorityQueue();
        foreach ($container->findTaggedServiceIds($tag) as $id => $tags) {
            $definition = $container->getDefinition($id);
            $attributes = $definition->getTag($tag);
            $priority   = isset($attributes[0]['order']) ? $attributes[0]['order'] : 0;

            $containers->insert($definition, $priority);
        }

        $containers = iterator_to_array($containers);
        ksort($containers);

        $definitions = array();
        foreach ($containers as $key => $definition) {
            $attributes         = $definition->getTag($tag);
            $type               = isset($attributes[0]['type']) ? $attributes[0]['type'] : $key;
            if ($aggregate) {
                if (!isset($definitions[$type])) {
                    $definitions[$type] = array();
                }
                $definitions[$type][] = $definition;
            } else {
                $definitions[$type] = $definition;
            }
        }

        return $definitions;
    }

    /**
     * Smart merge
     *
     * array_merge rewrite all elements in second level
     * array_merge_recursive adds elements and makes arrays from strings
     *
     * @example
     * <code>
     *     $this->merge(array(
     *         'level1' => array('param1' => '1', 'params2' => array('a' => 'b'))
     *     ), array(
     *         'level1' => array('param1' => '2', 'params2' => array('a' => 'c', 'd' => 'e'))
     *     ));
     *
     *     //result:
     *     array(
     *         'level1' => array('param1' => '2', 'params2' => array('a' => 'c'), 'd' => 'e')
     *     );
     * </code>
     *
     * @param  array $to
     * @param  array $from
     * @return array
     */
    public static function merge($to, $from)
    {
        foreach ($from as $key => $value) {
            if (!is_array($value)) {
                if (is_int($key)) {
                    $to[] = $value;
                } else {
                    $to[$key] = $value;
                }
            } else {
                if (!isset($to[$key])) {
                    $to[$key] = array();
                }

                $to[$key] = self::merge($to[$key], $value);
            }
        }

        return $to;
    }
}
