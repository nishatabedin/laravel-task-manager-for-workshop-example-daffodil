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

                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                            <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="category" class="block text-gray-700 font-medium mb-2">Category</label>
                            <select name="category_id" id="category"
                                class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="1">Work</option>
                                <option value="2">Personal</option>
                                <option value="3">Study</option>
                            </select>
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