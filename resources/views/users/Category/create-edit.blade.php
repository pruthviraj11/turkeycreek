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
