<?php

namespace App\Services;

use App\Repositories\PageRepository;

class PageService
{
    protected $repo;

    public function __construct(PageRepository $repo)
    {
        $this->repo = $repo;
    }

    public function list(array $filters)
    {
        return $this->repo->all($filters);
    }

    public function create(array $data)
    {
        $data = $this->refined($data);
        try {
            return $this->repo->create($data);
        }catch (\Exception $exception){
            throw new \Exception("Page creation failed: " . $exception->getMessage());
        }
    }


    public function update($id, array $data)
    {
        $data = $this->refined($data, 'update');
        try {
            return $this->repo->update($id, $data);
        }catch (\Exception $exception){
            throw new \Exception("Page update failed: " . $exception->getMessage());
        }
    }


    public function refined(array $data, $type = 'create')
    {
        if (empty($data['status'])) {
            $data['status'] = 'Active';
        }
        if ($type == 'create'){
            $data['slug'] = SlugService::generateUniqueSlugForPage($data['title']);
        }else{
            unset($data['slug']);
        }
        return $data;
    }


    public function delete($id)
    {
        try {
            return $this->repo->delete($id);
        }catch (\Exception $exception){
            throw new \Exception("Page deletion failed: " . $exception->getMessage());
        }

    }


    public function where(array $conditions)
    {
        return $this->repo->where($conditions);
    }
}
