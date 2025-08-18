<?php

namespace App\Service;

use App\Traits\UploadFile;

class BaseService
{
    use UploadFile;

    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function index(array $filters, $relations = [], $paginated = true)
    {
        $query = $this->model::filters($filters)->with($relations);
        return $paginated ? $query->paginate() : $query->get();
    }

    public function show($id, $relations = [])
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    public function store($data)
    {
        return $this->model::create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->model::findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        $record = $this->model::findOrFail($id);
        $record->delete();
    }
}
