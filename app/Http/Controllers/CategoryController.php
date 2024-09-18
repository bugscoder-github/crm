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
            'result' => Category::generateOptions()
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

        $categories = Category::generateOptions();

        return Inertia::render('Category/Form', [
           'form' => $category,
           'categories' => $categories,
           'success' => session('success') ?? '',
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
        $result = $this->save($request);

        return $this->goto($result->id, 'User created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category) {
        $result = $this->save($request, $category);

        return $this->goto($result->id, 'User updated successfully.');
    }

    public function save(CategoryRequest $request, Category $category = null) {
        if ($category == null) { $category = new Category(); }
        $data = $request->validated();

        return $category->updateOrCreate(['id' => $category->id], $data);
    }

	public function goto($id, $msg = '') {
		return redirect()
				->route('category.edit', $id)
				->withInput()
				->with('success', $msg);
	}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
