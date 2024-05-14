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
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="text-decoration: none">
                        <div class=" border-b border-gray-200">
                            <div class="container">

                                @if ($data['form_title'] == 'Create Vip Membership')
                                    <form method="POST" action="{{ route('vip_membership.store') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                    @else
                                        <form method="POST"
                                            action="{{ route('vip_membership.update', $VipMembership->id) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                @endif


                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title">Title:</label>
                                            <input type="text"
                                                class="form-control @error('title') is-invalid @enderror" id="title"
                                                name="title" placeholder="Enter VipMembership Title"
                                                value="{{ isset($VipMembership) ? $VipMembership->title : old('title') }}">

                                            @error('title')
                                                <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                            @enderror
                                        </div>
                                    </div>




                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status:</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Active"
                                                    {{ isset($VipMembership) && $VipMembership->status == 'Active' ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option value="In Active"
                                                    {{ isset($VipMembership) && $VipMembership->status == 'In Active' ? 'selected' : '' }}>
                                                    In Active</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Image:</label>
                                        <input type="file" id="image"
                                            class="form-control @error('image') is-invalid @enderror" name="image"
                                            value="{{ isset($VipMembership) ? $VipMembership->image : old('image') }}">

                                        {{-- @if (isset($VipMembership) && $VipMembership->image)
                                            <img src="{{ $VipMembership->image }}" width="150" />
                                            <input type="hidden" name="old_image" value="{{ $VipMembership->image }}">
                                        @endif
                                        @error('image')
                                            <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                        @enderror --}}
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Description:</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                                rows="4">{{ isset($VipMembership) ? $VipMembership->description : old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                            @enderror
                                        </div>
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
