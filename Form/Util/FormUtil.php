<?php
namespace Millwright\RadBundle\Form\Util;

use Symfony\Component\Form\Util\FormUtil as BaseFormUtil;

/**
 * Form util classes
 */
class FormUtil extends BaseFormUtil
{
    const SHIFT_COUNT = 2;

    /**
     * Convert model constants to form select element options
     *
     * @param string $class          Class name with constants
     * @param string $prefix         array values prefix
     * @param string $separator      replace underscores to this separator
     * @param string $localPrefix    constant prefix, ex. STATUS_
     *
     * @return array
     */
    static public function convertConstantsToOptions($class, $prefix = '', $separator = '.', $localPrefix = '')
    {
        $choices = array();
        if ($localPrefix && !$localPrefix[(strlen($localPrefix) - 1)] != '_') {
            $localPrefix .= '_';
        }

        if (!$prefix) {
            //autodetect prefix from namespace and class name
            $prefixes = explode('\\', trim($class, '\\'));

            for ($i = 0; $i < self::SHIFT_COUNT; $i++) {
                array_shift($prefixes);
            }

            $prefix = implode($separator, $prefixes);
        }

        $prefix .= $separator;

        $reflection = new \ReflectionClass($class);
        foreach ($reflection->getConstants() as $name => $value) {
            if (!$localPrefix || $localPrefix === substr($name, 0, strlen($localPrefix))) {
                $choices[$value] = $prefix . strtolower(str_replace(array($localPrefix, '_'), array('', $separator),
                    $name));
            }
        }

        return $choices;
    }
}
