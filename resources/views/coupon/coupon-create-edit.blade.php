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
                              
                                @if ($data['form_title'] == 'Create Coupon')
                
                                    <form method="POST" action="{{ route('coupon.store') }}" enctype="multipart/form-data">
                                        @csrf
                                    @else
                                        <form method="POST" action="{{ route('coupon.update', $coupon->id) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                @endif
                
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name:</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                                placeholder="Enter Coupon Name"
                                                value="{{ isset($coupon) ? $coupon->name : old('name') }}">
                                            
                                            @error('name')
                                                <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    
                                
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="url">Url:</label>
                                            <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url"
                                                placeholder="Enter Url" value="{{ isset($coupon) ? $coupon->url : old('url') }}">
                                                @error('url')
                                                <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                            @enderror
                                        </div>
                                    </div>
                
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="store_id">Store:</label>
                                            <select class="form-select form-control select2" name="store_id" id="store_id">
                                                <option value="">Select Store</option>
                                                <option value="">All</option>
                
                                                @foreach ($store as $store_name)
                                                    <option value="{{ $store_name->id }}"
                                                        {{ isset($coupon) && $coupon->store_id == $store_name->id ? 'selected' : '' }}>
                                                        {{ $store_name->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status:</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Active"
                                                {{ isset($coupon) && $coupon->status == 'Active' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="In Active"
                                                {{ isset($coupon) && $coupon->status == 'In Active' ? 'selected' : '' }}>In Active</option>
                                            </select>
                                        </div>
                                    </div>
                
                                    <div class="col-md-6">
                                        <label>Image:</label>
                                        <input type="file" id="image" class="form-control @error('image') is-invalid @enderror" name="image"
                                            value="{{ isset($coupon) ? $coupon->image : old('image') }}">
                                     
                                        @if (isset($coupon) && $coupon->image)
                                            <img src="{{ Storage::url(isset($coupon) ? $coupon->image : old('image')) }}"
                                                width="150"/>
                                                <input type="hidden" name="old_image" value="{{ $coupon->image }}">
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
