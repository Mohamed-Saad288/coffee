<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::leftJoin('categories as parents','parents.id','=','categories.parent_id')
            ->select([
                'categories.*',
                'parents.name as parent_name'
            ])
            // ->selectRaw('(SELECT COUNT(*) FROM products WHERE products.category_id = categories.id) as product_count')
            ->withCount(['products' => function($qurey) {
                $qurey->where('status','active');
            }
            ])->paginate();

        return CategoryResource::collection($categories);
    }

    public function store(Request $request)
    {
        $request->validate(Category::rules());


        $data = $request->except('image');

        $data['image'] = $this->uploadImage($request);

        $category = Category::create($data);

        return Response::json($category,201);
    }


    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(Category $category , Request $request)
    {
        $request->validate([
            'name' => ['sometimes|required','string','min:3','max:255'],
            'parent_id' => ['nullable','exists:categories,id'],
            'image' => ['nullable','image'],
        ]);

        $old_image = $category->image ;
        $data = $request->except('image');

        $new_image = $this->uploadImage($request);
        if ($new_image)
        {
            $data['image'] = $new_image;
        }
        $category->update($data);

        if ($old_image && $new_image)
        {
            Storage::disk('public')->delete($old_image);
        }

        return Response::json($category);

    }



    public function destroy(Category $category)
    {


        DB::beginTransaction();

        try {
            $category->delete();

            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            DB::commit();

            return response()->json([
                'message' => 'Category Deleted Successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while deleting the category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('image'))
        {
            return null;
        }
        $file = $request->file('image');
        $path = $file->store('categories','public');

        return $path;

    }

}
