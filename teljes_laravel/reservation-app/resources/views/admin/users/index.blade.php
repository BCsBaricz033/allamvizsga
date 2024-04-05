<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    ADMIN {{ __("You're logged in!") }}
                    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Birthdate</th>
                <th>Identity</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->address }}</td>
                    <td>{{ $user->birthdate }}</td>
                    <td>{{ $user->identity_number }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        <a href="{{ route('admin.user.edit', $user->id) }}">Edit</a>

                        <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-white text-red-600">Delete</button>


                        </form>
                    </td>
                    

                </tr>
                
            @endforeach
        </tbody>
        </table>
            </div>
            
            </div>
            {{$users->links()}}
        </div>
    </div>
</x-app-layout>
