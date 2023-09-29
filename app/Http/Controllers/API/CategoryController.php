<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Category::all();

        $result = CategoryResource::collection($data);

        // return CategoryResource::collection($data);

        // return response()->json([
        //     'status' => 'true',
        //     'message' => 'Data Ditemukan!',
        //     'data' => CategoryResource::collection($data)
        // ]);

        return $this->sendResponse($result, 'Data Ditemukan!');

        // return $this->sendError('Data Tidak Ditemukan!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = new CategoryResource(Category::create($request->validated()));

        return $this->sendResponse($data, 'Data Berhasil Ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $cek = Category::find($category->id);

        if(!$cek) {
            abort(404, 'Data Tidak Ditemukan!');
        }

        $data = new CategoryResource($cek);

        return $this->sendResponse($data, 'Data Ditemukan!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        $result = new CategoryResource($category);

        return $this->sendResponse($result, 'Data Berhasil Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // $cek = Category::find($category->id);

        // $cek = $category->delete();

        $cek = Category::find($category->id);

        if($cek == null) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak Ditemukan!'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil Dihapus!'
        ]);

        // $cekId = Category::find($category->id);

        // $data = new CategoryResource($cekId);

        // if(empty($data)) {
        //     return response()->json([
        //             'status' => false,
        //             'message' => 'Data tidak ditemukan!',
        //     ], 404);
        // }

        // $data = Category::find($category->id);
        // $data->delete();

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Data berhasil dihapus!'
        // ], 200);


        // return $this->sendResponse();
    }
}
