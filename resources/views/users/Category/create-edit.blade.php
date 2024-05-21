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
                            @if ($data['form_title'] == 'Create Category')
                                <form method="POST" action="{{ route('category-store') }}" enctype="multipart/form-data">
                                    @csrf
                                @else
                                <form method="POST" action="{{ route('category-update', $category->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Category:</label>
                                        <select class="form-control" name="parent_id">
                                            <option value="">Select Parent Category</option>
                                            @foreach ($categories as $categorydata)
                                                <option value="{{ $categorydata->id }}" {{ isset($categorydata) && $categorydata->id == $category->parent_id ? 'selected' : '' }}>
                                                    {{ $categorydata->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Name Of Category:</label>
                                        <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') ? old('name') : ($category ? $category->name : '') }}" placeholder="Category Name">
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback"><b>{{ $errors->first('name') }}</b></div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status:</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="Active" {{ isset($Store) && $Store->status == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="InActive" {{ isset($Store) && $Store->status == 'InActive' ? 'selected' : '' }}>InActive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 mb-1">
                                    <div class="form-group">
                                        <label class="form-label" for="categoriesimage">Category Image</label>
                                        <input type="file" id="categoriesimage" class="form-control" name="categoriesimage">
                                        <span class="text-danger">
                                            @error('categoriesimage')
                                                {{ $message }}
                                            @enderror
                                        </span>

                                            <div class="mt-1">
                                                @if ($category != '' && $category->categoriesimage)
                                                    <div class="" style="width: 100px; height: 100px; overflow: hidden;">
                                                        <img src="{{ Storage::url($category->categoriesimage) }}" class="img-fluid" alt="Category Image">
                                                    </div>
                                                @else
                                                    <div class="" style="width: 100px; height: 100px; overflow: hidden;">
                                                        <img src="{{ asset('storage/coupon_images/no_img.png') }}" class="img-fluid" alt="No Image">
                                                    </div>
                                                @endif
                                            </div>
                                            <p class="mx-1 m-1">
                                                <a class="btn btn-danger btn-sm removeimage" data-toggle='tooltip' data-placement='top' title='Delete Images' href="{{ route('app-Category-photos', ['encrypted_id' => encrypt($category->id)]) }}" onclick="return RemoveProfileImage('Are you sure you want to remove Profile Photo', this)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="15" y1="9" x2="9" y2="15"></line>
                                                        <line x1="9" y1="9" x2="15" y2="15"></line>
                                                    </svg>
                                                </a>
                                            </p>

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
</x-app-layout>
