<?php
namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use JMS\Serializer\Handler\HandlerRegistry;
use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;
use SimpleIT\ClaireExerciseBundle\Model\Resources\KnowledgeResource;

/**
 * Class KnowledgeResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class KnowledgeResourceFactory extends SharedResourceFactory
{

    /**
     * Create an KnowledgeResource collection
     *
     * @param array $knowledges
     *
     * @return array
     */
    public static function createCollection(array $knowledges)
    {
        $knowledgeResources = array();
        foreach ($knowledges as $knowledge) {
            $knowledgeResources[] = self::create($knowledge);
        }

        return $knowledgeResources;
    }

    /**
     * Create a KnowledgeResource
     *
     * @param Knowledge $knowledge
     *
     * @return KnowledgeResource
     */
    public static function create(Knowledge $knowledge)
    {
        $knowledgeResource = new KnowledgeResource();
        parent::fill(
            $knowledgeResource,
            $knowledge
        );

        // knowledge requirements
        $requirements = array();
        foreach ($knowledge->getRequiredKnowledges() as $req) {
            /** @var Knowledge $req */
            $requirements[] = $req->getId();
        }
        $knowledgeResource->setRequiredKnowledges($requirements);

        return $knowledgeResource;
    }
}
