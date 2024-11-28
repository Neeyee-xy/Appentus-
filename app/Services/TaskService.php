<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Task;

class TaskService
{   
    public function getAll()
    {
        $tasks = Task::with('category')->where('user_id',Auth::user()->id)->get();
        return $tasks;
    }
    public function fliter($fliters)
    {

        
        $status =$category_id=$due_date_from=$due_date_to=false;
        $page_no=1;
        if (array_key_exists('status', $fliters)) {
           $status = $fliters['status'];
        }
        if (array_key_exists('category_id', $fliters)) {
           $category_id = $fliters['category_id'];
        }
        if (array_key_exists('due_date_from', $fliters)) {
           $due_date_from = $fliters['due_date_from'];
        }
        if (array_key_exists('due_date_to', $fliters)) {
           $due_date_to = $fliters['due_date_to'];
        }
        if (array_key_exists('page_no', $fliters)) {
           $page_no = $fliters['page_no'];
        }
        

        // Initial Query
        $query = DB::table('tasks');

        // Add filters to the query
        if ($status) {
            $query = $query->where('status', $status);
        }
        if ($category_id) {
            $query = $query->where('category_id', $category_id);
        }
        if ($due_date_from && $due_date_to) {
            $query = $query->whereBetween('due_date', [$due_date_from, $due_date_to]);
        }
        if ($page_no) {
            // Pagination
            $query =  $query->paginate($page_no);
        }

        
       
        return $query;
    }
    public function search($search_term )
    {

        $title =$descriptions="";
        if (array_key_exists('title', $search_term)) {
           $title = $search_term['title'];
        }
        if (array_key_exists('descriptions', $search_term)) {
           $descriptions = $search_term['descriptions'];
        }
         $tasks = DB::table('tasks')
        ->where('title', 'like', '%' . $title . '%')
        ->orWhere('description', 'like', '%' . $descriptions . '%')
        ->paginate(5);


        
       
        return $tasks;
    }
    public function getOne($id)
    {
        $task = Task::with('category')->find($id);
        return $task;
    }

    public function createTask(array $data)
    {
        $task=Task::create($data);
        return Task::with('category')->find($task->id);
    }

    public function updateTask($id, array $data)
    {
        $task = Task::find($id);
        $task?->update($data);
        return $task;
    }

    public function deleteTask($id)
    {
        return Task::destroy($id);
    }
    //... add other necessary methods
}