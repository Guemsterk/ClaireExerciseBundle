<?php

namespace SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class LocalFormula
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class LocalFormula
{
    /**
     * @var string $equation
     * @Serializer\Type("string")
     * @Serializer\Groups({"details", "knowledge_storage", "resource_storage", "exercise_model_storage"})
     */
    private $equation;

    /**
     * @var int $formulaId
     * @Serializer\Type("integer")
     * @Serializer\Groups({"details", "knowledge_storage", "resource_storage", "exercise_model_storage"})
     */
    private $formulaId;

    /**
     * @var array $variables
     * @Serializer\Type("array<SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula\Variable>")
     * @Serializer\Groups({"details", "knowledge_storage", "resource_storage", "exercise_model_storage"})
     */
    private $variables = array();

    /**
     * @var Unknown $unknown
     * @Serializer\Type("SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula\Unknown")
     * @Serializer\Groups({"details", "knowledge_storage"})
     */
    private $unknown;

    /**
     * Set equation
     *
     * @param string $equation
     */
    public function setEquation($equation)
    {
        $this->equation = $equation;
    }

    /**
     * Get equation
     *
     * @return string
     */
    public function getEquation()
    {
        return $this->equation;
    }

    /**
     * Set formulaId
     *
     * @param int $formulaId
     */
    public function setFormulaId($formulaId)
    {
        $this->formulaId = $formulaId;
    }

    /**
     * Get formulaId
     *
     * @return int
     */
    public function getFormulaId()
    {
        return $this->formulaId;
    }

    /**
     * Set variables
     *
     * @param array $variables
     */
    public function setVariables($variables)
    {
        $this->variables = $variables;
    }

    /**
     * Get variables
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Set unknown
     *
     * @param \SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula\Unknown $unknown
     */
    public function setUnknown($unknown)
    {
        $this->unknown = $unknown;
    }

    /**
     * Get unknown
     *
     * @return \SimpleIT\ApiResourcesBundle\Exercise\DomainKnowledge\Formula\Unknown
     */
    public function getUnknown()
    {
        return $this->unknown;
    }
}
