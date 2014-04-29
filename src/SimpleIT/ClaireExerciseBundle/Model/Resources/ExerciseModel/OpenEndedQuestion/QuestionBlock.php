<?php

namespace SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\OpenEndedQuestion;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseModel\Common\ResourceBlock;

/**
 * Block of questions in a short answer question exercise model
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class QuestionBlock extends ResourceBlock
{
    /**
     * QuestionBlock constructor
     *
     * @param int $numberOfOccurrences
     *
     * @return QuestionBlock
     */
    function __construct(
        $numberOfOccurrences
    )
    {
        $this->numberOfOccurrences = $numberOfOccurrences;
    }
}
