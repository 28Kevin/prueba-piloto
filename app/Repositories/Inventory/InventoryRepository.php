<?php

namespace App\Repositories\Inventory;
use App\Repositories\BaseRepository;
use App\Models\Inventory;

class InventoryRepository extends BaseRepository
{

    public function __construct(Inventory $model)
    {
        parent::__construct($model);
    }

    public function list($request = [], $with = [], $select = ['*'])
    {
        $data = $this->model->select($select)
            ->with($with)
            ->where(function ($query) use ($request) {
                if (!empty ($request['product_id'])) {
                    $query->where('product_id', $request['product_id']);
                }
            });

        if (empty($request['typeData'])) {
            $data = $data->paginate($request['perPage'] ?? 10);
        } else {
            $data = $data->get();
        }

        return $data;
    }


    public function store($request)
    {
        if (!empty($request['id'])) {
            $data = $this->model->find($request['id']);
        } else {
            $data = $this->model::newModelInstance();
        }

        foreach ($request as $key => $value) {
            $data[$key] = is_array($request[$key]) ? $request[$key]['value'] : $request[$key];
        }

        $data->save();

        return $data;
    }


    public function updateProduct($id, $quantity)
    {
        $data = $this->model->where('product_id', $id)->first();
        if ($data) {
            $data->quantity = $data->quantity - $quantity;
            $data->save();
        }
        return $data;
    }

}
