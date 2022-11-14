<?php

namespace App\Repositories;

interface SilenceRepositoryInterface
{
    /**
     * @param string $identifier
     * @return array
     */
    public function load(string $identifier): array;
}
