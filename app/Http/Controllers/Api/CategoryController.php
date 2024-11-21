<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return $this->response('Ok', 200, [$categories]);
    }

    public function store(Request $request)
    {
        $this->authorize('userDeny', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $category = Category::create($validated);

        return $this->response('Created', 201, [$category]);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->response('Not Found', 404, [$category]);
        }

        return $this->response('Created', 201, [$category]);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('userDeny', User::class);

        $category = Category::find($id);

        if (!$category) {
            return $this->response('Not Found', 404, [$category]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $category->update($validated);

        return $this->response('Created', 201, [$category]);
    }

    public function destroy($id)
    {
        $this->authorize('userDeny', User::class);

        $category = Category::find($id);

        if (!$category) {
            return $this->response('Not Found', 404, [$category]);
        }

        $category->delete();

        return $this->response('No Content', 204, [$category]);
    }
}
