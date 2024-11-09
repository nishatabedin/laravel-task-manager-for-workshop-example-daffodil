<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($tasks->isEmpty())
                    <p>No tasks available.</p>
                    @else

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


                    <table class="min-w-full border-collapse border border-gray-300">
                        <thead>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2">Title</th>
                                <th class="border border-gray-300 px-4 py-2">Description</th>
                                <th class="border border-gray-300 px-4 py-2">Status</th>
                                <th class="border border-gray-300 px-4 py-2">Author</th>
                                <th class="border border-gray-300 px-4 py-2">Category</th>
                                <th class="border border-gray-300 px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">{{ $task->title }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $task->description }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $task->status }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $task->author->name }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $task->category->name }}</td>


                                {{-- <td class="border border-gray-300 px-4 py-2">
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="text-blue-500">Edit</a> |
                                    <a href="#" class="text-red-500"
                                        onclick="confirmDelete(event, {{ $task->id }})">Delete</a>
                                </td> --}}


                                <td class="border border-gray-300 px-4 py-2">
                                    @can('update', $task)
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="text-blue-500">Edit</a>
                                    @endcan

                                    @can('delete', $task)
                                    @can('update', $task)
                                    |
                                    @endcan
                                    <a href="#" class="text-red-500"
                                        onclick="confirmDelete(event, {{ $task->id }})">Delete</a>
                                    @endcan
                                </td>


                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $tasks->links() }}
                        <!-- Pagination links -->
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add SweetAlert Script -->
    <script>
        function confirmDelete(event, taskId) {
            event.preventDefault(); // Prevent the default action

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to delete the task
                    fetch(`/tasks/${taskId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Deleted!',
                                data.message, // Display dynamic message
                                'success'
                            ).then(() => {
                                // Optionally, remove the task row or reload the page
                                // document.getElementById(`task-row-${taskId}`).remove();
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Failed to delete the task. Please try again.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'Something went wrong. Please try again.',
                            'error'
                        );
                    });
                }
            });

        }
    </script>
</x-app-layout>