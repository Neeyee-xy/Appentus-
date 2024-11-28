<?php

namespace App\Http\Controllers;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum; 
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    protected $categoryService;
    /**
     * use  category services to isolate concerns.
     *
     * 
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
   
    
    /**
     * list catgories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $categories=$this->categoryService->getAll();
        return response()->json(['categories' => $categories], 200);
    }
    /**
     * list one category.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $category=$this->categoryService->getOne($id);
        return response()->json(['category' => $category], 200);
    }
    /**
     * store User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required','string'],
           
        
        ];


        $customMessages = [];
        $validator = Validator::make($request->all(), $rules, $customMessages);
       
         if (!$validator->fails()) {
            $data = $request->all();
            $data['user_id']=Auth::user()->id;
            $category=$this->categoryService->createCategory($data);
            if ($category) {
               return response()->json(['message' => 'Category created ','category'=>$category], 200);
            }else{
                return response()->json(['message' =>'Category could not be created. Check your input and try again.' ], 400);
            }
            
        }else{
            return response()->json(['message' => $validator->errors()], 422);
        }
    }
    /**
     * update Category.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    { 

        $rules = [

            
            'name' => ['required','string'],
        
        ];


        $customMessages = [];
        $validator = Validator::make($request->input(), $rules, $customMessages);
       
        if (!$validator->fails()) {
            $data = $request->all();
            $category=$this->categoryService->updateCategory($id, $data);
            if ($category) {
               return response()->json(['message' => 'Category updated ','category'=>$category], 200);
            }else{
                return response()->json(['message' =>'Category could not be updated. Check your input and try again.' ], 400);
            }
            
        }else{
           return response()->json(['message' => $validator->errors()], 422); 
        }
    }
    /**
     * delete Category.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        
        if ($this->categoryService->deleteCategory($id)) {
                return response()->json(['message' => 'Category deleted'], 200);
            }else{
                return response()->json(['message' =>'Category could not be deleted' ], 400);
            }
    }

    
}
