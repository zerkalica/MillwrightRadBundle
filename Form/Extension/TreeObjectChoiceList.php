<?php
namespace Millwright\RadBundle\Form\Extension;

use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList as BaseChoiceList;

use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\Form\Exception\StringCastException;
use Symfony\Component\Form\Exception\InvalidPropertyException;

/**
 * A choice list for object choices.
 *
 * Supports generation of choice labels, choice groups and choice values
 * by calling getters of the object (or associated objects).
 *
 * <code>
 * $choices = array($user1, $user2);
 *
 * // call getName() to determine the choice labels
 * $choiceList = new ObjectChoiceList($choices, 'name');
 * </code>
 */
class TreeObjectChoiceList extends BaseChoiceList
{
    /**
     * The property path used to obtain the choice label.
     *
     * @var PropertyPath
     */
    protected $labelPath;

    /**
     * The property path used for object grouping.
     *
     * @var PropertyPath
     */
    protected $groupPath;

    /**
     * The property path used to obtain the choice value.
     *
     * @var PropertyPath
     */
    protected $valuePath;

    /**
     * @var array
     */
    protected $excludedChoices;

    protected $prefix_sign;

    /**
     * Creates a new object choice list.
     *
     * @param array|\Traversable $choices The array of choices. Choices may also be given
     *                                    as hierarchy of unlimited depth by creating nested
     *                                    arrays. The title of the sub-hierarchy can be
     *                                    stored in the array key pointing to the nested
     *                                    array. The topmost level of the hierarchy may also
     *                                    be a \Traversable.
     * @param string $labelPath A property path pointing to the property used
     *                          for the choice labels. The value is obtained
     *                          by calling the getter on the object. If the
     *                          path is NULL, the object's __toString() method
     *                          is used instead.
     * @param array $preferredChoices A flat array of choices that should be
     *                                presented to the user with priority.
     * @param array $excludedChioces A flat array of choices that should be
     *                               excluded from the list.
     * @param string $groupPath A property path pointing to the property used
     *                          to group the choices. Only allowed if
     *                          the choices are given as flat array.
     * @param string $valuePath A property path pointing to the property used
     *                          for the choice values. If not given, integers
     *                          are generated instead.
     * @param string $prefix_sign A prefix sign using for tree visualisation
     */
    public function __construct(
        $choices,
        $labelPath = null,
        array $preferredChoices = array(),
        array $excludedChioces = array(),
        $groupPath = null,
        $valuePath = null,
        $prefix_sign = '-'
    ) {
        $this->labelPath = null !== $labelPath ? new PropertyPath($labelPath) : null;
        $this->groupPath = null !== $groupPath ? new PropertyPath($groupPath) : null;
        $this->valuePath = null !== $valuePath ? new PropertyPath($valuePath) : null;

        $this->prefix_sign     = $prefix_sign;
        $this->excludedChoices = $excludedChioces;

        parent::__construct($choices, array(), $preferredChoices);
    }

    /**
     * Initializes the list with choices.
     *
     * Safe to be called multiple times. The list is cleared on every call.
     *
     * @param array|\Traversable $choices          The choices to write into the list.
     * @param array              $labels           Ignored.
     * @param array              $preferredChoices The choices to display with priority.
     *
     * @throws \InvalidArgumentException When passing a hierarchy of choices and using
     *                                   the "groupPath" option at the same time.
     */
    protected function initialize($choices, array $labels, array $preferredChoices)
    {
        $excludeModels = $this->excludedChoices;
        $labels = array();
        $values = array();

        $this->buildParentSelectOptions($choices, $this->excludedChoices, 0, $labels, $values);

        parent::initialize($values, $labels, $preferredChoices);
    }

    /**
     * Build select options
     *
     * @param array|Collection          $groups
     * @param UserModelInterface[]|null $excludedChoices
     * @param integer                   $depth
     *
     * @return array $options Options tree
     */
    protected function buildParentSelectOptions(
        $choices,
        array $excludedChoices = array(),
        $depth = 0,
        &$labels,
        &$values
    ) {
        $prefix = str_repeat($this->prefix_sign, $depth) . ' ';
        $depth++;

        /** @var $choice \Millwright\RadBundle\Model\TreeInterface */
        foreach ($choices as $choice) {
            if (in_array($choice, $excludedChoices)) {
                continue;
            }

            $values[$choice->getId()] = $choice;
            $labels[$choice->getId()] = $prefix . $this->labelPath->getValue($choice);

            $childModels = $choice->getChildren();

            if ($childModels) {
                $this->buildParentSelectOptions($childModels, $excludedChoices, $depth, $labels, $values);
            }
        }
    }

    /**
     * Creates a new unique value for this choice.
     *
     * If a property path for the value was given at object creation,
     * the getter behind that path is now called to obtain a new value.
     * Otherwise a new integer is generated.
     *
     * @param mixed $choice The choice to create a value for
     *
     * @return integer|string A unique value without character limitations.
     */
    protected function createValue($choice)
    {
        if ($this->valuePath) {
            return (string) $this->valuePath->getValue($choice);
        }

        return parent::createValue($choice);
    }

    protected function extractLabels($choices, array &$labels)
    {
        foreach ($choices as $i => $choice) {
            if (is_array($choice)) {
                $labels[$i] = array();
                $this->extractLabels($choice, $labels[$i]);
            } elseif ($this->labelPath) {
                $labels[$i] = $this->labelPath->getValue($choice);
            } elseif (method_exists($choice, '__toString')) {
                $labels[$i] = (string) $choice;
            } else {
                throw new StringCastException('A "__toString()" method was not found on the objects of type "' . get_class($choice) . '" passed to the choice field. To read a custom getter instead, set the argument $labelPath to the desired property path.');
            }
        }
    }
}
