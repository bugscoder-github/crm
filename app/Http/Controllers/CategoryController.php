<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return Inertia::render('Category/Index', [
            'result' => Category::getCategoryList()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return $this->renderForm();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category) {
        return $this->renderForm($category);
    }

    public function renderForm(Category $category = null) {
        if ($category == null) { $category = new Category(); }

        $categories = Category::getCategoryList();

        return Inertia::render('Category/Form', [
           'form' => $category,
           'categories' => $categories,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request) {
        $this->save($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category) {
        $this->save($request, $category);
    }

    public function save(CategoryRequest $request, Category $category = null) {
        if ($category == null) { $category = new Category(); }
        $data = $request->validated();

        $category->updateOrCreate(['id' => $category->id], $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
