<?php

namespace App\Repositories;

use App\Models\Page;

class PageRepository
{
    public function all(array $filters)
    {
        $search = $this->data_filter($filters);
        return Page::where($search)->latest()->paginate(10);
    }


    public function data_filter($filters)
    {
        $search = [];
        if (!empty($filters['title'])) {
            $search[] = ['title', 'like', '%' . $filters['title'] . '%'];
        }
        if (!empty($filters['status'])) {
            $search[] = ['status', $filters['status']];
        }
        return $search;
    }

    public function create(array $data)
    {
        return Page::create($data);
    }


    public function find($id)
    {
        return Page::findOrFail($id);
    }

    public function where(array $conditions)
    {
        return Page::where($conditions);
    }
    public function update($id, array $data)
    {
        $book = $this->find($id);
        $book->update($data);
        return $book;
    }


    public function delete($id)
    {
        $page = $this->find($id);
        if ($page->deletable == 1){
            return $page->delete();
        }else{
            throw new \Exception("This page cannot be deleted.");
        }
    }
}
