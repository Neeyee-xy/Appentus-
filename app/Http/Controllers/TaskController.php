<?php

namespace App\Http\Controllers;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum; 
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;


class TaskController extends Controller
{
    protected $taskService;
    /**
     * use  Task services to isolate concerns.
     *
     * 
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }
   
    
    /**
     * list catgories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $tasks=$this->taskService->getAll();
        return response()->json(['tasks' => $tasks], 200);
    }
    /**
     * Fliter Task.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fliters(Request $request)
    {
        $data=$request->all();
        $task=$this->taskService->fliter($data);
        return response()->json(['task' => $task], 200);
    }
    /**
     * Search Task.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $data=$request->all();
        $task=$this->taskService->search($data);
        return response()->json(['task' => $task], 200);
    }
    /**
     * list one Task.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $task=$this->taskService->getOne($id);
        return response()->json(['task' => $task], 200);
    }
    /**
     * store task.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => ['required','string'],
            'description' => ['required','string'],
            'status' => ['required','string','in:PENDING,IN-PROGRESS,COMPLETED'],
            'due_date' => ['required','date'],
            'category_id' => ['sometimes','integer'],
           
        
        ];


        $customMessages = [];
        $validator = Validator::make($request->all(), $rules, $customMessages);
       
         if (!$validator->fails()) {
            $data = $request->all();
            $data['user_id']=Auth::user()->id;
            $task=$this->taskService->createTask($data);
            if ($task) {
               return response()->json(['message' => 'Task created ','task'=>$task], 200);
            }else{
                return response()->json(['message' =>'Task could not be created. Check your input and try again.' ], 400);
            }
            
        }else{
            return response()->json(['message' => $validator->errors()], 422);
        }
    }
    /**
     * update Task.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    { 

        $rules = [

            
            'title' => ['required','string'],
            'description' => ['required','string'],
            'status' => ['required','string','in:PENDING,IN-PROGRESS,COMPLETED'],
            'due_date' => ['required','date'],
            'category_id' => ['sometimes','integer'],
        ];


        $customMessages = [];
        $validator = Validator::make($request->input(), $rules, $customMessages);
       
        if (!$validator->fails()) {
            $data = $request->all();
            $task=$this->taskService->updateTask($id, $data);
            if ($task) {
               return response()->json(['message' => 'Task updated ','task'=>$task], 200);
            }else{
                return response()->json(['message' =>'Task could not be updated. Check your input and try again.' ], 400);
            }
            
        }else{
           return response()->json(['message' => $validator->errors()], 422); 
        }
    }
    /**
     * delete Task.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        
        if ($this->taskService->deleteTask($id)) {
                return response()->json(['message' => 'Task deleted'], 200);
            }else{
                return response()->json(['message' =>'Task could not be deleted' ], 400);
            }
    }

    
}
