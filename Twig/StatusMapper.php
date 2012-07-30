<?php
namespace Millwright\RadBundle\Twig;

use Symfony\Component\Translation\TranslatorInterface;

use Millwright\ConfigurationBundle\PhpUtil as Util;

/**
 * Status mapper interface
 */
class StatusMapper implements StatusMapperInterface
{
    protected $translator;
    protected $class;
    protected $prefix;
    protected $domain;
    protected $constPrefix;

    /**
     * Constructor
     *
     * @param string              $class  enum data class with constants
     * @param string              $prefix untranslated values prefix
     * @param string              $domain translation domain
     * @param TranslatorInterface $translator
     * @param string|null         $constPrefix enum class constant prefix
     */
    public function __construct($class, $prefix, $domain, TranslatorInterface $translator, $constPrefix = '')
    {
        $this->translator  = $translator;
        $this->class       = $class;
        $this->prefix      = $prefix;
        $this->domain      = $domain;
        $this->constPrefix = $constPrefix;
    }

    /**
     * {@inheritDoc}
     */
    public function all(array $items = array())
    {
        $map = Util::convertConstantsToOptions($this->class, $this->prefix, $this->constPrefix, '.');
        foreach ($map as & $item) {
            $item = $this->translator->trans($item, array(), $this->domain);
        }

        if ($items) {
            $map = array_intersect_key($map, array_flip($items));
        }

        return $map;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \InvalidArgumentException if no status found in the map
     */
    public function get($status)
    {
        $statuses = $this->all();

        if (!isset($statuses[$status])) {
            throw new \InvalidArgumentException(sprintf('Status "%s" does not exist', $status));
        }

        return $statuses[$status];
    }
}
