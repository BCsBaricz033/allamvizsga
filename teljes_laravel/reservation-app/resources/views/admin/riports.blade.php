<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    ADMIN {{ __("Riports") }}

                    <form id="appointment-form">
        <select id="institution" name="institution">
            <option value="">Choose Institution</option>
            @foreach($institutions as $institution)
                <option value="{{ $institution->id }}">{{ $institution->name }}</option>
            @endforeach
        </select>
        <select id="section" name="section">
            <option value="">Choose Section</option>
        </select>
        <select id="doctor" name="doctor">
            <option value="">Choose Doctor</option>
        </select>
        <input type="datetime-local" id="start_time" name="start_time">
        <input type="datetime-local" id="end_time" name="end_time">
        <button type="submit">Submit</button>
    </form>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
