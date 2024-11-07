<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Display general success or error messages -->
                    @if (session('success'))
                    <div id="alert-box"
                        class="bg-green-500 border border-green-700 text-white px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <button onclick="document.getElementById('alert-box').style.display='none'">
                                <svg class="fill-current h-6 w-6 text-white" role="button"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path
                                        d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.359 5.652a.5.5 0 00-.707.707L9.293 10l-3.641 3.641a.5.5 0 00.707.707L10 10.707l3.641 3.641a.5.5 0 00.707-.707L10.707 10l3.641-3.641a.5.5 0 000-.707z" />
                                </svg>
                            </button>
                        </span>
                    </div>
                    @endif

                    @if (session('error'))
                    <div id="alert-box"
                        class="bg-red-500 border border-red-700 text-white px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <button onclick="document.getElementById('alert-box').style.display='none'">
                                <svg class="fill-current h-6 w-6 text-white" role="button"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path
                                        d="M14.348 5.652a.5.5 0 00-.707 0L10 9.293 6.359 5.652a.5.5 0 00-.707.707L9.293 10l-3.641 3.641a.5.5 0 00.707.707L10 10.707l3.641 3.641a.5.5 0 00.707-.707L10.707 10l3.641-3.641a.5.5 0 000-.707z" />
                                </svg>
                            </button>
                        </span>
                    </div>
                    @endif

                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
                            <input type="text" name="title" id="title"
                                class="w-full border-gray-300 rounded-md shadow-sm">
                            @error('title')
                            <p class="custom-text-red p-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            @error('description')
                            <p class="custom-text-red p-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Show author selection only for admin users -->
                        @if (isset($authUser) && $authUser->role === 'admin')
                        <div class="mb-4">
                            <label for="author_id" class="block text-gray-700 font-medium mb-2">Author</label>
                            <select name="author_id" id="author_id" class="w-full border-gray-300 rounded-md shadow-sm">
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('author_id')
                            <p class="custom-text-red p-2">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif

                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                            <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                            @error('status')
                            <p class="custom-text-red p-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="category" class="block text-gray-700 font-medium mb-2">Category</label>
                            <select name="category_id" id="category"
                                class="w-full border-gray-300 rounded-md shadow-sm">
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <p class="custom-text-red p-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="custom-blue-button px-4 py-2 rounded-md hover:bg-blue-600">Create Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>