<?php

namespace App\Http\Controllers\task;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Services\UserService;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\Task\StoreTaskAction;
use App\Http\Requests\Task\StoreTaskRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskController extends Controller
{

    protected $authUser;
    protected $taskService;
    protected $userService;
    protected $categoryService;


    public function __construct(TaskService $taskService, UserService $userService, CategoryService $categoryService)
    {
        // $this->authUser = Auth::user();
        $this->authUser = auth()->user();
        $this->taskService = $taskService;
        $this->userService = $userService;
        $this->categoryService = $categoryService;
    }




    /**
     * Display a listing of the resource.
     */

    public function index(TaskService $taskService)
    {
        $tasks =  $this->taskService->getTasksByRole($this->authUser->role, $this->authUser->id);
        return view('task.index', compact('tasks'));
    }





    public function index2()
    {
        $authUser = auth()->user();
        $tasks = Task::with('author', 'category')
            ->when($authUser->role !== 'admin', function ($query) use ($authUser) {
                $query->where('author_id', $authUser->id);
            })
            ->get();

        return view('task.index', compact('tasks'));
    }





    public function index1()
    {
        $authUser = auth()->user();
        if ($authUser->role === 'admin') {
            $tasks = Task::with('author', 'category')->get();
        } else {
            $tasks = Task::with('author', 'category')->where('author_id', $authUser->id)->get();
        }
        return view('task.index', compact('tasks'));
    }



    public function index0()
    {
        $authUser = auth()->user();
        if ($authUser->role === 'admin') {
            $tasks = Task::query()->get();
        } else {
            $tasks = Task::query()->where('author_id', $authUser->id)->get();
        }
        return view('task.index', compact('tasks'));
    }









    /**
     * Show the form for creating a new resource.
     */



    public function create()
    {
        $categories = $this->categoryService->getAllCategories();
        $users = $this->authUser->role === 'admin' ? $this->userService->getAllUsers() : [];

        return view('task.create', [
            'authUser' => $this->authUser,
            'categories' => $categories,
            'users' => $users,
        ]);
    }







    public function create0()
    {
        $authUser = auth()->user();
        $categories = Category::orderBy('name')->get();
        $users = [];

        if ($authUser->role === 'admin') {
            $users = User::orderBy('name')->get();
        }

        return view('task.create', compact('authUser', 'categories', 'users'));
    }






    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, StoreTaskAction $storeTaskAction)
    {
        $authUser = auth()->user();
        $validated = $request->validated();
        $validated['author_id'] = $authUser->role === 'admin' ? $validated['author_id'] : $authUser->id;
        $validated['created_by'] = $authUser->id;

        try {
            $storeTaskAction->execute($validated);
            return redirect()->route('tasks.create')->with('success', 'Task created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('tasks.create')->with('error', 'Failed to create task. Please try again.');
        }
    }






    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }






    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = $this->taskService->getTaskById($id);

        // Check if the task was found
        if (!$task) {
            return redirect()->route('tasks.index')->with('error', 'Task not found.');
        }

        // Check if the user is allowed to edit the task
        if ($this->authUser->role !== 'admin' && $task->author_id !== $this->authUser->id) {
            return redirect()->route('tasks.index')->with('error', 'You do not have permission to edit this task.');
        }

        $categories = $this->categoryService->getAllCategories();

        return view('task.edit', compact('task', 'categories'));
    }







    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }








    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json(['success' => true, 'message' => 'Task deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete the task.'], 500);
        }
    }
}
