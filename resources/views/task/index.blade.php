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
                    <table class="min-w-full border-collapse border border-gray-300">
                        <thead>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2">Title</th>
                                <th class="border border-gray-300 px-4 py-2">Description</th>
                                <th class="border border-gray-300 px-4 py-2">Status</th>
                                <th class="border border-gray-300 px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Sample Task 1</td>
                                <td class="border border-gray-300 px-4 py-2">Description of Task 1</td>
                                <td class="border border-gray-300 px-4 py-2">Pending</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <a href="{{ route('tasks.edit', 1) }}" class="text-blue-500">Edit</a> |
                                    <a href="#" class="text-red-500" onclick="confirmDelete(event, 1)">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Sample Task 2</td>
                                <td class="border border-gray-300 px-4 py-2">Description of Task 2</td>
                                <td class="border border-gray-300 px-4 py-2">Completed</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <a href="{{ route('tasks.edit', 1) }}" class="text-blue-500">Edit</a> |
                                    <a href="#" class="text-red-500" onclick="confirmDelete(event, 2)">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                    // Add logic to close the modal
                    Swal.fire(
                        'Deleted!',
                        'Your task has been deleted.',
                        'success'
                    );
                   
                    
                }
            });
        }
    </script>
</x-app-layout>