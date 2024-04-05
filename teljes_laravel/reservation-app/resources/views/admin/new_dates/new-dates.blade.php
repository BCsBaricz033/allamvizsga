<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New dates') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("ADMIN new dates page") }}
                    <form id="new-date-form">

                        <select id="institution" name="institution" required multiple>
                            <option value="" disabled>Choose Institution</option>
                            @foreach($institutions as $institution)
                            <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                            @endforeach
                        </select>
                        <select id="section" name="section" required multiple>
                            <option value="" disabled>Choose Section</option>
                        </select>
                        <select id="doctor" name="doctor" required multiple>
                            <option value="" disabled>Choose Doctor</option>
                        </select>
                        <input type="datetime-local" id="start_time" name="start_time" required>
                        <input type="datetime-local" id="end_time" name="end_time" required>
                        <button type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        //change stations after institution change
        $('#institution').on('change', function() {
            var institutionIds = $(this).val();
            $('#section').empty();

            if (institutionIds && institutionIds.length > 0) {
                $.each(institutionIds, function(index, institutionId) {
                    $.ajax({
                        url: '{{ route("admin.sections") }}',
                        type: "GET",
                        data: {
                            institution_id: institutionId
                        },
                        dataType: "json",
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $('#section').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                });
            }
        });

        //change doctors after sections changed

        $('#section').on('change', function() {
            var section_ids = $(this).val();
            var institutionIds = $("#institution").val();
            if (section_ids && section_ids.length > 0) {
                $.each(institutionIds, function(index, institutionId) {


                    $.each(section_ids, function(index, section_id) {
                        $.ajax({
                            url: '{{ route("admin.doctors") }}',
                            type: "GET",
                            data: {
                            institution_id: institutionId,
                            section_id:section_id
                            },
                            dataType: "json",
                            success: function(data) {
                                $('#doctor').empty();
                                $('#doctor').append('<option value="" disabled>Choose Doctor</option>');
                                $.each(data, function(key, value) {
                                    $('#doctor').append('<option value="' + value.id + '">' + value.name + '</option>');
                                });
                            }
                        });


                    });
                });

            } else {
                $('#doctor').empty();
            }
        });


    });
</script>