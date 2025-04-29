<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BaseRepository
{
    protected $model;
    protected $connection = null;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function new($data = [])
    {
        return new $this->model($data);
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;
        return $this;
    }

    public function newModelInstance()
    {
        return $this->model::newModelInstance();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function count()
    {
        return $this->model->count();
    }

    public function first()
    {
        return $this->model->first();
    }

    public function latest($column = 'created_at')
    {
        return $this->model->latest($column)->first();
    }

    public function get($with = [])
    {
        return $this->model->with($with)->get();
    }

    public function find($id, $with = [], $select = '*', $withCount = [])
    {
        return $this->model->select($select)->withCount($withCount)->with($with)->find($id);
    }

    public function save(Model $model)
    {
        $model->save();

        return $model;
    }

    public function make(Model $model)
    {
        $model->make();

        return $model;
    }

    public function replicate(Model $model, $data = [])
    {
        $clone = $model->replicate();

        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $clone['key'] = $value;
            }
        }

        return $clone->save();
    }

    public function delete($id)
    {
        $model = $this->model->find($id);
        $model->delete();

        return $model;
    }

    public function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $this->model->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function changeState($id, $estado, $column = 'estado', $with = [])
    {
        $model = $this->model->find($id);
        $model[$column] = $estado;
        $model->save();

        return $model;
    }

    public function changeStateArray($ids, $estado, $column = 'estado', $with = [])
    {
        $model = $this->model->whereIn('id', $ids)->update([
            $column => $estado,
        ]);

        return $model;
    }

    public function pdf($vista, $data = [], $nombre = 'archivo', $is_stream = true, $landspace = false, $paper = 'legal')
    {
        $pdf = \PDF::loadview($vista, compact('data'));
        $pdf->setPaper($paper);
        if ($landspace == true) {
            $pdf->setPaper($paper, 'landscape');
        }
        $pdf->setOption('header-html', view('Pdfs.Quote.QuoteHeader', compact('data'))->render());

        $nombre = $nombre . '.pdf';
        if ($is_stream) {
            return $pdf->stream($nombre);
        } else {
            return $pdf->download($nombre);
        }
    }

    public function datos_activos($key = 'estado', $value = '1')
    {
        return $this->model->where($key, $value)->get();
    }

    public function clearNull($array)
    {
        foreach ($array as $clave => $valor) {
            if ($valor === 'null') {
                $array[$clave] = null;
            }
        }

        return $array;
    }

}
