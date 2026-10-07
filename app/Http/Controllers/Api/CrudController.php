<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class CrudController extends Controller
{
    protected string $model;
    protected bool $ownedByUser = true;

    abstract protected function rules(): array;

    protected function query(Request $request)
    {
        return $this->model::query()->latest();
    }

    public function index(Request $request)
    {
        return response()->json($this->query($request)->get());
    }

    public function show($id)
    {
        return response()->json($this->model::findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        if ($this->ownedByUser) {
            $data['user_id'] = $request->user()->id;
        }
        return response()->json($this->model::create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        $rules = collect($this->rules())->map(fn($r) => array_merge(['sometimes'], $r))->all();
        $item->update($request->validate($rules));
        return response()->json($item);
    }

    public function destroy($id)
    {
        $this->model::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
