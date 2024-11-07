<?php

namespace App\Http\Controllers\task;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Task\StoreTaskAction;
use App\Http\Requests\Task\StoreTaskRequest;

class TaskController extends Controller
{

    protected $storeTaskAction;

    public function __construct(StoreTaskAction $storeTaskAction)
    {
        $this->storeTaskAction = $storeTaskAction;
    }





    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with('author', 'category')->get();
        return view('task.index', compact('tasks'));
    }






    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
    public function store(StoreTaskRequest $request)
    {
        $authUser = auth()->user();
        $validated = $request->validated();
        $validated['author_id'] = $authUser->role === 'admin' ? $validated['author_id'] : $authUser->id;
        $validated['created_by'] = $authUser->id;

        try {
            $this->storeTaskAction->execute($validated);
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
        $task = Task::findOrFail($id);
        $categories = Category::orderBy('name')->get();
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
        //
    }
}
