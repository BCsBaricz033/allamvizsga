<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    ADMIN {{ __("You're logged in!") }}
                    
    <h1>Edit User</h1>
    <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <label for="name">Name</label>
        <input type="text" name="name" value="{{ $user->name }}">
        <label for="email">Email</label>
        <input type="email" name="email" value="{{ $user->email }}">
        <label for="phone">Phone</label>
        <input type="text" name="phone" value="{{ $user->phone }}">
        <label for="address">Address</label>
        <input type="text" name="address" value="{{ $user->address }}">
        <label for="user_type">User Type</label>
        <select name="role" id="role">
            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="doctor" {{ $user->role == 'doctor' ? 'selected' : '' }}>Doctor</option>
            <option value="assistant" {{ $user->role == 'assistant' ? 'selected' : '' }}>Assistant</option>
        </select>
        <x-primary-button type="submit">Update</x-primary-button>

    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

