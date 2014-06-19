<?php
namespace SimpleIT\ClaireExerciseBundle\Controller\Api\ExerciseResource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\DBALException;
use SimpleIT\ApiBundle\Exception\ApiBadRequestException;
use SimpleIT\ApiBundle\Exception\ApiConflictException;
use SimpleIT\ApiBundle\Exception\ApiNotFoundException;
use SimpleIT\ApiBundle\Model\ApiCreatedResponse;
use SimpleIT\ApiBundle\Model\ApiDeletedResponse;
use SimpleIT\ApiBundle\Model\ApiEditedResponse;
use SimpleIT\ApiBundle\Model\ApiGotResponse;
use SimpleIT\ClaireExerciseBundle\Controller\Api\ApiController;
use SimpleIT\ClaireExerciseBundle\Entity\ExerciseResource\Metadata;
use SimpleIT\ClaireExerciseBundle\Entity\ResourceMetadataFactory;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResource;
use SimpleIT\ClaireExerciseBundle\Model\Resources\MetadataResourceFactory;
use SimpleIT\Utils\Collection\CollectionInformation;
use Symfony\Component\HttpFoundation\Request;

/**
 * API MetadataByResource Controller
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class MetadataByResourceController extends ApiController
{
    /**
     * Get all metadata
     *
     * @param mixed                 $resourceId
     * @param CollectionInformation $collectionInformation
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function listAction(
        $resourceId,
        CollectionInformation $collectionInformation
    )
    {
        try {
            $metadatas = $this->get('simple_it.exercise.resource_metadata')->getAll(
                $collectionInformation,
                $resourceId,
                $this->getUserId()
            );

            $metadataResources = MetadataResourceFactory::createCollection($metadatas);

            return new ApiGotResponse($metadataResources);
        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(MetadataResource::RESOURCE_NAME);
        }
    }

    /**
     * Get a metadata
     *
     * @param mixed $resourceId
     * @param mixed $metadataKey
     *
     * @throws ApiNotFoundException
     * @return ApiGotResponse
     */
    public function viewAction($resourceId, $metadataKey)
    {
        try {
            $metadata = $this->get('simple_it.exercise.resource_metadata')->getByEntity(
                $resourceId,
                $metadataKey,
                $this->getUserId()
            );

            $metadataResource = MetadataResourceFactory::create($metadata);

            return new ApiGotResponse($metadataResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(MetadataResource::RESOURCE_NAME);
        }
    }

    /**
     * Create a metadata
     *
     * @param Request $request
     * @param mixed   $resourceId
     *
     * @throws ApiNotFoundException
     * @throws ApiConflictException;
     * @return ApiCreatedResponse
     */
    public function createAction(
        Request $request,
        $resourceId
    )
    {
        try {
            $metadata = $this->createMetadata($request);

            $metadata = $this->get('simple_it.exercise.resource_metadata')->addToEntity(
                $resourceId,
                $metadata,
                $this->getUserId()
            );

            $metadataResource = MetadataResourceFactory::create($metadata);

            return new ApiCreatedResponse($metadataResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException();
        } catch (DBALException $eoe) {
            throw new ApiConflictException();
        }
    }

    /**
     * Edit a metadata
     *
     * @param MetadataResource $metadata
     * @param mixed            $resourceId
     * @param string           $metadataKey
     *
     * @throws ApiNotFoundException
     * @return ApiEditedResponse
     */
    public function editAction(
        MetadataResource $metadata,
        $resourceId,
        $metadataKey
    )
    {
        try {
            $this->validateResource($metadata, array('edit'));

            $metadata = $this->get('simple_it.exercise.resource_metadata')
                ->saveFromEntity(
                    $resourceId,
                    $metadata,
                    $metadataKey,
                    $this->getUserId()
                );

            $metadataResource = MetadataResourceFactory::create($metadata);

            return new ApiEditedResponse($metadataResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(MetadataResource::RESOURCE_NAME);
        }
    }

    /**
     * Delete a metadata
     *
     * @param mixed $resourceId
     * @param mixed $metadataKey
     *
     * @throws ApiNotFoundException
     * @return ApiDeletedResponse
     */
    public function deleteAction($resourceId, $metadataKey)
    {
        try {
            $this->get('simple_it.exercise.resource_metadata')->removeFromEntity(
                $resourceId,
                $metadataKey,
                $this->getUserId()
            );

            return new ApiDeletedResponse();

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(MetadataResource::RESOURCE_NAME);
        }
    }

    /**
     * Edit the list of metadata for this resource
     *
     * @param ArrayCollection $metadatas
     * @param int             $resourceId
     *
     * @return ApiEditedResponse
     * @throws ApiNotFoundException
     * @throws ApiConflictException
     */
    public function editAllAction(ArrayCollection $metadatas, $resourceId)
    {
        try {
            $resources = $this->get('simple_it.exercise.exercise_resource')
                ->editMetadata
                (
                    $resourceId,
                    $metadatas,
                    $this->getUserId()
                );

            $resourceResource = MetadataResourceFactory::createCollection($resources);

            return new ApiEditedResponse($resourceResource);

        } catch (NonExistingObjectException $neoe) {
            throw new ApiNotFoundException(MetadataResource::RESOURCE_NAME);
        } catch (DBALException $eoe) {
            throw new ApiConflictException($eoe->getMessage());
        }
    }

    /**
     * Create a metadata from a request
     *
     * @param Request $request Request
     *
     * @return Metadata
     * @throws ApiBadRequestException
     */
    protected function createMetadata(Request $request)
    {
        $metadata = null;
        $content = json_decode($request->getContent(), true);
        if (!is_array($content) || count($content) != 1) {
            throw new ApiBadRequestException('Metadata format is invalid');
        }

        foreach ($content as $key => $value) {
            $metadata = ResourceMetadataFactory::create($key, $value);
        }

        return $metadata;
    }
}
