<?php

namespace SimpleIT\ClaireAppBundle\Requestors;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface UseCaseFactory
{
    /**
     * @return UseCase
     */
    public function make($useCaseName);
}
