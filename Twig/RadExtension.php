<?php
namespace Millwright\RadBundle\Twig;

use Millwright\Util\NumberToString;

/**
 * Twig extension for Bootstrap helpers
 */
class RadExtension extends \Twig_Extension
{
    protected $dateFormat;
    protected $dateTimeFormat;

    /**
     * Constructor
     *
     * @param OptionRegistryInterface $options
     */
    public function __construct($dateFormat, $dateTimeFormat)
    {
        $this->dateFormat     = $dateFormat;
        $this->dateTimeFormat = $dateTimeFormat;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'date_format'      => new \Twig_Function_Method($this, 'getDateFormat'),
            'date_time_format' => new \Twig_Function_Method($this, 'getDateTomeFormat'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            'in_words' => new \Twig_Filter_Method($this, 'getInWords'),
        );
    }

    /**
     * Get date format string
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Get date time format string
     *
     * @return string
     */
    public function getTimeDateFormat()
    {
        return $this->dateTimeFormat;
    }

    /**
     * Converts a number to in_words value
     *
     * @param string $number
     *
     * @return string
     */
    public function getInWords($number)
    {
        return NumberToString::numberToString($number);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return 'millwright_rad';
    }
}
