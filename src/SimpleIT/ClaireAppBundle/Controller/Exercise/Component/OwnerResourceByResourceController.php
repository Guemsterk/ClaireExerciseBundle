<?php

namespace SimpleIT\ClaireAppBundle\Controller\Exercise\Component;

use SimpleIT\AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class OwnerResourceByResourceController
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class OwnerResourceByResourceController extends AppController
{
    /**
     * Add a resource to the personal space: create an owner resource
     *
     * @param $resourceId
     *
     * @return JsonResponse
     */
    public function addToPersoAction($resourceId)
    {
        $ownerResource = $this->get('simple_it.claire.exercise.owner_resource')->addToPerso(
            $resourceId
        );

        return new JsonResponse(array(
            "id"       => $ownerResource->getId(),
            "metadata" => $ownerResource->getMetadata(),
            "type"     => $ownerResource->getType()
        ));
    }
}
