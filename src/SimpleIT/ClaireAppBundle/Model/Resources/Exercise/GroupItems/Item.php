<?php

namespace SimpleIT\ApiResourcesBundle\Exercise\Exercise\GroupItems;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\Common\CommonItem;
use SimpleIT\ApiResourcesBundle\Exercise\Exercise\Common\Markable;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseObject\ExerciseObject;

/**
 * Class Exercise
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class Item extends CommonItem implements Markable
{
    /**
     * @const SHOW = "show"
     */
    const SHOW = "show";

    /**
     * @const HIDE = "hide"
     */
    const HIDE = "hide";

    /**
     * @const ASK = "ask"
     */
    const ASK = "ask";

    /**
     * @var string $groupNames Indicate what should be done with the names of
     *                         the groups. Take its int values in the constants
     *                         from GroupItems\Model
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    private $displayGroupNames;

    /**
     * @var array $objects An array of ExerciseObject
     * @Serializer\Type("array<SimpleIT\ApiResourcesBundle\Exercise\ExerciseObject\ExerciseObject>")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    private $objects = array();

    /**
     * @var array $groups An array of strings that contains the names of the
     *                    groups
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    private $groups = array();

    /**
     * @var array $solution An array which indicates the category of each object. $solution[4]
     * containt the index of the category of $object[4] in the groups array. Traps are put in the
     * category -1.
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "corrected", "not_corrected", "item_storage"})
     */
    private $solutions = array();

    /**
     * The learner's answers.
     *
     * @var array
     * @Serializer\Type("array")
     * @Serializer\Groups({"details", "corrected"})
     */
    private $answers = array();

    /**
     * @var float
     * @Serializer\Type("float")
     * @Serializer\Groups({"details", "corrected", "item"})
     */
    private $mark = null;

    /**
     * Add an object in the group.
     *
     * @param ExerciseObject $object
     * @param mixed          $groupName A string for a category name or false
     *                                  if this object is a trap
     */
    public function addObjectInGroup(ExerciseObject $object, $groupName)
    {
        // add to the objects list
        $objIndex = count($this->objects);
        $this->objects[$objIndex] = $object;

        // find the index of the group or create it
        $groupIndex = array_search($groupName, $this->groups);
        if ($groupIndex === false) {
            $groupIndex = count($this->groups);
            $this->groups[$groupIndex] = $groupName;
        }

        $this->solutions[$objIndex] = $groupIndex;
    }

    /**
     * Get the value of the parameters that indicates how to display group names
     *
     * @return string
     */
    public function getDisplayGroupNames()
    {
        return $this->displayGroupNames;
    }

    /**
     * Get objects
     *
     * @return array An array of ExerciseObject
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * Get groups
     *
     * @return array An array of Groups
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Get solution
     *
     * @return array An array which indicates the category of each object.
     *               $solution[i] containt the index of the category of
     *               $object[i] in the groups array. Traps are put in the
     *               category -1.
     */
    public function getSolutions()
    {
        return $this->solutions;
    }

    /**
     * Get the number of groups to be formed
     *
     * @return int
     */
    public function getNumberOfGroups()
    {
        return count($this->groups);
    }

    /**
     * Set answers
     *
     * @param array $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    /**
     * Get answers
     *
     * @return array
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set the value of the parameters that indicates how to display group names
     *
     * @param string $displayGroupNames
     */
    public function setDisplayGroupNames($displayGroupNames)
    {
        $this->displayGroupNames = $displayGroupNames;
    }

    /**
     * Shuffle the objects order and update the solution
     */
    public function shuffleObjects()
    {
        $objKeys = array();

        // create an array with the keys
        foreach (array_keys($this->objects) as $index) {
            $objKeys[] = $index;
        }

        // shuffle the array of keys
        shuffle($objKeys);

        // create new arrays
        $newObjects = array();
        $newSolution = array();

        // fill the new arrays with the shuffled keys
        foreach ($objKeys as $index => $key) {
            $newObjects[$index] = $this->objects[$key];
            $newSolution[$index] = $this->solutions[$key];
        }

        // Copy in the exercise object arrays
        $this->objects = $newObjects;
        $this->solutions = $newSolution;
    }

    /**
     * Set groups
     *
     * @param array $groups
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    /**
     * Set solutions
     *
     * @param array $solutions
     */
    public function setSolutions($solutions)
    {
        $this->solutions = $solutions;
    }

    /**
     * Set objects
     *
     * @param array $objects
     */
    public function setObjects($objects)
    {
        $this->objects = $objects;
    }

    /**
     * Check if the Markable has a mark
     *
     * @return boolean
     */
    public function isMarked()
    {
        return !is_null($this->mark);
    }

    /**
     * Get mark
     *
     * @return float
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * Set mark
     *
     * @param float $mark
     */
    public function setMark($mark)
    {
        $this->mark = $mark;
    }
}