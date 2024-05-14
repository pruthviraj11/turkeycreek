@include('links')
<link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/Navigation.css') }}" rel="stylesheet">

<!-- Include Bootstrap CSS -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Include Bootstrap JavaScript and jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



<x-app-layout>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $data['form_title'] }}</h3>
                </div>
                <div class="card-body">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200">
                            <div class="container">

                                @if ($data['form_title'] == 'Create Event')
                                    <form method="POST" action="{{ route('event.store') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                    @else
                                        <form method="POST" action="{{ route('event.update', $event->id) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                @endif



                                @if (isset($event))
                                    @method('PUT')
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name:</label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror" id="name"
                                                name="name" placeholder="Enter Event Name"
                                                value="{{ isset($event) ? $event->name : old('name') }}">
                                            @error('name')
                                                <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date">Date:</label>
                                            <input type="date"
                                                class="form-control @error('date') is-invalid @enderror" id="date"
                                                name="date" placeholder="Enter Date"
                                                value="{{ isset($event) ? $event->date : old('date') }}">
                                            @error('date')
                                                <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description">Description:</label>
                                            <input type="text"
                                                class="form-control @error('description') is-invalid @enderror"
                                                id="description" name="description" placeholder="Enter Description"
                                                value="{{ isset($event) ? $event->description : old('description') }}">
                                            @error('description')
                                                <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="location">Location:</label>
                                            <input type="text"
                                                class="form-control @error('location') is-invalid @enderror"
                                                id="location" name="location" placeholder="Enter Event location"
                                                value="{{ isset($event) ? $event->location : old('location') }}">
                                            @error('location')
                                                <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="google_map_link">Google Map Link:</label>
                                            <textarea class="form-control @error('google_map_link') is-invalid @enderror" id="google_map_link"
                                                name="google_map_link" placeholder="Enter Event Google Map Link">{{ isset($event) ? $event->google_map_link : old('google_map_link') }}</textarea>
                                            @error('google_map_link')
                                                <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="learn_more">Learn More Button:</label>
                                            <input type="text"
                                                class="form-control @error('learn_more') is-invalid @enderror"
                                                id="learn_more" name="learn_more" placeholder="Enter Learn More"
                                                value="{{ isset($event) ? $event->learn_more : old('learn_more') }}">
                                            @error('learn_more')
                                                <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="startTime">From Time:</label>
                                            <input type="time" id="startTime" name="startTime"
                                                value="{{ isset($event) ? $event->startTime : old('startTime') }}">

                                            <label for="endTime">To Time:</label>
                                            <input type="time" id="endTime" name="endTime"
                                                value="{{ isset($event) ? $event->endTime : old('endTime') }}">
                                        </div>
                                    </div>




                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status:</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Active"
                                                    {{ isset($event) && $event->status == 'Active' ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option value="In Active"
                                                    {{ isset($event) && $event->status == 'In Active' ? 'selected' : '' }}>
                                                    In Active</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Image:</label>
                                        <input type="file" id="image"
                                            class="form-control @error('image') is-invalid @enderror" name="image">
                                        @if (isset($event) && $event->image)
                                            <img src="{{ Storage::url($event->image) }}" width="150" />
                                            <input type="hidden" name="old_image" value="{{ $event->image }}">
                                        @endif
                                        @error('image')
                                            <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="col-md-12 text-center mt-4">
                                    <button type="submit" class="btn btn-secondary savebtn button_save">Save</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
