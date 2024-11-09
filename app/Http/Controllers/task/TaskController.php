<?php

namespace App\Http\Controllers\task;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use App\Models\TaskHistory;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Services\UserService;
use App\Services\CategoryService;
use App\Services\Task\DTOs\TaskDTO;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Actions\Task\StoreTaskAction;
use App\Actions\Task\UpdateTaskAction;
use App\Services\Task\DTOs\UpdateTaskDTO;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use Illuminate\Auth\Access\AuthorizationException;
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

    public function index()
    {
        $tasks = $this->taskService->getTasksByRole2($this->authUser->role, $this->authUser->id)
            ->paginate(10);
        return view('task.index', compact('tasks'));
    }




    public function index3()
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
        $validated = $request->validated();
        $validated['author_id'] = $this->authUser->role === 'admin' ? $validated['author_id'] : $this->authUser->id;
        $validated['created_by'] = $this->authUser->id;

        $taskDTO = new TaskDTO($validated);

        try {
            $storeTaskAction->execute($taskDTO);
            return redirect()->route('tasks.create')->with('success', 'Task created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('tasks.create')->with('error', 'Failed to create task. Please try again.');
        }
    }





    public function store3(StoreTaskRequest $request, StoreTaskAction $storeTaskAction)
    {

        $validated = $request->validated();
        $validated['author_id'] = $this->authUser->role === 'admin' ? $validated['author_id'] : $this->authUser->id;
        $validated['created_by'] = $this->authUser;

        try {
            $storeTaskAction->execute0($validated);
            return redirect()->route('tasks.create')->with('success', 'Task created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('tasks.create')->with('error', $e->getMessage());
            // return redirect()->route('tasks.create')->with('error', 'Failed to create task. Please try again.');
        }
    }





    public function store2(StoreTaskRequest $request)
    {

        $validated = $request->validated();
        $validated['author_id'] = auth()->user()->role === 'admin' ? $validated['author_id'] : auth()->user()->id;
        $validated['created_by'] = auth()->user()->id;

        try {
            $this->taskService->storeTask0($validated);

            return redirect()->route('tasks.create')->with('success', 'Task created successfully!');
        } catch (\Exception $e) {
            // Handle the exception with a generic message
            return redirect()->route('tasks.create')->with('error', $e->getMessage());
            // return redirect()->route('tasks.create')->with('error', 'Failed to create task. Please try again.');
        }
    }





    public function store1(StoreTaskRequest $request)
    {

        $validated = $request->validated();
        $validated['author_id'] = auth()->user()->role === 'admin' ? $validated['author_id'] : auth()->user()->id;
        $validated['created_by'] = auth()->user()->id;

        try {
            $task = Task::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'category_id' => $validated['category_id'],
                'author_id' => $validated['author_id'],
                'created_by' => $validated['created_by'],
            ]);

            TaskHistory::create([
                'task_id' => $task->id,
                'history_created_by' => $validated['created_by'],
                'action' => 'Created',
                'description' => $validated['description'],
            ]);

            return redirect()->route('tasks.create')->with('success', 'Task created successfully!');
        } catch (\Exception $e) {
            // Handle the exception with a generic message
            return redirect()->route('tasks.create')->with('error', 'Failed to create task. Please try again.');
        }
    }





    public function store0(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:Pending,In Progress,Completed',
            'category_id' => 'required|integer|exists:categories,id',
        ];

        if (auth()->check() && auth()->user()->role === 'admin') {
            $rules['author_id'] = 'required|integer|exists:users,id';
        }

        $validated = $request->validate($rules);

        $validated['author_id'] = auth()->user()->role === 'admin' ? $validated['author_id'] : auth()->user()->id;
        $validated['created_by'] = auth()->user()->id;

        try {
            $task = Task::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'category_id' => $validated['category_id'],
                'author_id' => $validated['author_id'],
                'created_by' => $validated['created_by'],
            ]);

            TaskHistory::create([
                'task_id' => $task->id,
                'history_created_by' => $validated['created_by'],
                'action' => 'Created',
                'description' => $validated['description'],
            ]);

            return redirect()->route('tasks.create')->with('success', 'Task created successfully!');
        } catch (\Exception $e) {
            // Handle the exception with a generic message
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



    // public function edit(Task $task)
    // {
    //     $categories = $this->categoryService->getAllCategories();
    //     return view('task.edit', compact('task', 'categories'));
    // }




    public function edit(Task $task)
    {
        $categories = $this->categoryService->getAllCategories();
        Gate::authorize('update', $task);
        return view('task.edit', compact('task', 'categories'));
    }




    // public function edit(string $id)
    // {

    //     try {
    //         $task = $this->taskService->getTaskById($id);
    //         //Gate::authorize('update', $task);

    //         // if ($this->authUser->cannot('update', $task)) {
    //         //     return redirect()->route('tasks.index')->with('error', 'Unauthorized');
    //         //     abort(403);
    //         // }

    //         $categories = $this->categoryService->getAllCategories();

    //         return view('task.edit', compact('task', 'categories'));
    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         //throw new ModelNotFoundException('Page not found');
    //         return redirect()->route('tasks.index')->with('error', 'Task not found.');
    //     } catch (AuthorizationException $e) {
    //         return redirect()->route('tasks.index')->with('error', 'Unauthorized');
    //     } catch (\Exception $e) {
    //         return redirect()->route('tasks.index')->with('error', 'Something Went Wrong');
    //     } catch (\Error $e) {
    //         // Handle non-existent class or unexpected errors
    //         return redirect()->route('tasks.index')->with('error', 'Unexpected error. Please try again.');
    //         // return redirect()->route('tasks.index')->with('error', $e->getMessage());
    //     }
    // }






    // public function edit(string $id)
    // {
    //     $task = $this->taskService->getTaskById2($id);

    //     // Check if the task was found
    //     if (!$task) {
    //         return redirect()->route('tasks.index')->with('error', 'Task not found.');
    //     }

    //     // Check if the user is allowed to edit the task
    //     if ($this->authUser->role !== 'admin' && $task->author_id !== $this->authUser->id) {
    //         return redirect()->route('tasks.index')->with('error', 'You do not have permission to edit this task.');
    //     }

    //     $categories = $this->categoryService->getAllCategories();

    //     return view('task.edit', compact('task', 'categories'));
    // }







    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id, UpdateTaskAction $updateTaskAction)
    {
        try {
            $task = $this->taskService->getTaskById($id);
            Gate::authorize('update', $task);
            $taskDTO = new UpdateTaskDTO(array_merge(
                $request->validated(),
                ['updated_by' => $this->authUser->id]
            ));

            $updateTaskAction->execute($task, $taskDTO);

            return redirect()->route('tasks.edit', ['task' => $task->id])->with('success', 'Task updated successfully!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('tasks.index')->with('error', 'Task not found.');
        } catch (AuthorizationException $e) {
            return redirect()->route('tasks.index')->with('error', 'Unauthorized to update this task.');
        } catch (\Exception $e) {
            return redirect()->route('tasks.edit', ['task' => $task->id])->with('error', 'Failed to update task. Please try again.');
        }
    }








    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $task = $this->taskService->getTaskById($id);
            Gate::authorize('delete', $task);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Task not found.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['success' => false, 'message' => 'You are not allowed to delete this task'], 403);
        }

        $response = $this->taskService->deleteTask($task);

        if ($response['success']) {
            return response()->json(['success' => true, 'message' => $response['message']]);
        } else {
            return response()->json(['success' => false, 'message' => $response['message']], 500);
        }
    }
}
