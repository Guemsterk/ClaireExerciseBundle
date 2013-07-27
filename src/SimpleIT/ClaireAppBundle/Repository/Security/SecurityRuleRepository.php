<?php

namespace SimpleIT\ClaireAppBundle\Repository\Security;

use SimpleIT\ApiResourcesBundle\Security\RuleResource;
use SimpleIT\AppBundle\Repository\AppRepository;
use SimpleIT\Utils\Collection\CollectionInformation;

/**
 * Class SecurityRuleRepository
 *
 * @author Kévin Letord <kevin.letord@simple-it.fr>
 */
class SecurityRuleRepository extends AppRepository
{
    /**
     * @var string
     */
    protected $path = '/security-rules/{ruleId}';

    /**
     * @var  string
     */
    protected $resourceClass = 'SimpleIT\ApiResourcesBundle\Security\RuleResource';

    /**
     * Find a security rule
     *
     * @param int $ruleId Rule id
     *
     * @return RuleResource
     */
    public function find($ruleId)
    {
        return $this->findResource(array('ruleId' => $ruleId));
    }

    /**
     * Find a list of security rule
     *
     * @param CollectionInformation $collectionInformation Collection information
     *
     * @return \SimpleIT\Utils\Collection\PaginatedCollection
     */
    public function findAll(CollectionInformation $collectionInformation)
    {
        return parent::findAllResources(array($collectionInformation));
    }
}
