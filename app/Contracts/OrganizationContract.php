<?php

namespace App\Contracts;

interface OrganizationContract
{
    /**
     * Get the organization_id for the currently authenticated user.
     *
     * @return int|string|null
     */
    public function organizationId();
}
