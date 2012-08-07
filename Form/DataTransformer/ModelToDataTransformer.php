<?php
namespace Millwright\RadBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Util\PropertyPath;

/**
 * Transforms between a Model/s instance and a string/array.
 */
class ModelToDataTransformer implements DataTransformerInterface
{
    protected $collection;
    protected $labelPath;
    protected $valuePath;

    public function __construct($collection, $labelPath, $valuePath)
    {
        $this->collection = $collection;
        $this->labelPath  = null !== $labelPath ? new PropertyPath($labelPath) : null;
        $this->valuePath  = null !== $valuePath ? new PropertyPath($valuePath) : null;
    }

    /**
     * {@inheritDoc}
     */
    public function transform($data)
    {
        $options = array();

        foreach ($data as $model) {
            $id        = $this->valuePath->getValue($model);
            $options[] = (string) $id;
        }

        return $options;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($data)
    {
        $collection = new ArrayCollection();

        foreach ($data as $value) {
            foreach ($this->collection as $model) {
                if ($this->valuePath->getValue($model) == (int) $value) {
                    $collection->add($model);
                }
            }
        }

        return $collection;
    }
}
