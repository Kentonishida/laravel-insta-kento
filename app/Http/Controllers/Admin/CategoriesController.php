<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;

class CategoriesController extends Controller
{
    private $category;

    public function __construct(Category $category, Post $post){
        $this->category = $category;
        $this->post = $post;
    }

    public function index(){

        $all_categories = $this->category->orderBy('created_at' , 'desc')->paginate(10);

        $uncategorized_count = 0;
        $all_posts = $this->post->all();
        foreach($all_posts as $post) {
            if($post->categoryPost->count() == 0){
                $uncategorized_count++;
            }
        }
        return view('admin.categories.index')
        ->with('all_categories', $all_categories)
        ->with('uncategorized_count',$uncategorized_count);
    }

    #Note
    #1 You have to convert the text into Lowercase, and the first Letter should be capitalize
    #2 Save it into the categorise table

    // public function store(Request $request){
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //     ]);

    //     $category = new Category();
    //     $category->name = ucfirst(strtolower($request->name));
    //     $category->save();

    //      return redirect()->back()->with('success');
    // }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|min:1|max:50|unique:categories,name',
        ]);

        $this->category->name = ucwords (strtolower($request->name)); //SPORT -->Sport
        $this->category->save();

        return redirect()->back();
    }



# Create the update method
# 2 Add the validation and the codes
# 3 Create the route
# 4 Use the route

    public function update(Request $request, $id){
        $request->validate([
            'new_name' => 'required|min:1|max:50|unique:categories,name,'. $id
        ]);

        $category = $this->category->findOrFail($id);
        $category->name = ucwords(strtolower($request->new_name));
        $category->save();

        return redirect()->back();
    }
     public function destroy($id){
        $this->category->destroy($id);
        return redirect()->back();
     }




}
