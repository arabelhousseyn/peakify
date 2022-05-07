<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\{Category};
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $categories = Category::withoutTrashed()->latest('created_at')->paginate(15);
        return response($categories,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        if($request->validated())
        {
            Category::create($request->validated());
            return response(['message' => 'created!'],201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if($request->validated())
        {
            $category->update($request->validated());
            return response()->noContent();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if(!$category->trashed())
        {
            $category->delete();
            return response()->noContent();
        }
    }

    public function restore($category_id)
    {
        try {
            $category = Category::withTrashed()->findOrFail($category_id);
            if($category->trashed())
            {
                $category->restore();
                return response()->noContent();
            }
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('category not found');
        }
    }

    public function categoryDetails($category_id)
    {
        try {
            return new CategoryResource(Category::findOrFail($category_id));
        }catch (ModelNotFoundException $exception)
        {
            throw new ModelNotFoundException('category not found');
        }
    }

    public function filter($filter)
    {
        switch ($filter)
        {
            case 0 :
                $categories = Category::withTrashed()->latest('created_at')->paginate(15);
                return response($categories,200);
                break;
            case 1 :
                $categories = Category::onlyTrashed()->latest('created_at')->paginate(15);
                return response($categories,200);
                break;
        }
    }

    public function getAllCategories()
    {
        $categories = Category::all();
        return response(['data' => $categories],200);
    }
}
