<?php

namespace App\Services;

use App\Repositories\SettingRepository;

class SettingService
{
    protected $repo;

    public function __construct(SettingRepository $repo)
    {
        $this->repo = $repo;
    }

    public function list()
    {
        return $this->repo->all();
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }
}