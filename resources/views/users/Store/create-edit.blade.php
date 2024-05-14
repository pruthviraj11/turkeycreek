@include('links')
<link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">

<!-- Include Bootstrap CSS -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<link href="{{ asset('assets/css/Navigation.css') }}" rel="stylesheet">

<!-- Include Bootstrap JavaScript and jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<x-app-layout>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 >{{ $data['form_title'] }}</h3>
                </div>
                <div class="card-body">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200">
                            <div class="container">
                                @if ($data['form_title'] == 'Create Store')
                                    <form method="POST" action="{{ route('store-store') }}" enctype="multipart/form-data">
                                        @csrf
                                    @else
                                        <form method="POST" action="{{ route('store-update', $Store->id) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                @endif
                                <div class="row mt-4 form_styles">
                                    <div class="col-md-6 mb-3">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" id="title"
                                            value="{{ old('title') ? old('title') : ($Store ? $Store->title : '') }}"
                                            class="form-control @error('title') is-invalid @enderror">
                                        @error('title')
                                            <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="address_store">Address</label>
                                        <textarea name="address_store" id="address_store" class="form-control @error('address_store') is-invalid @enderror">{{ old('address_store') ? old('address_store') : ($Store ? $Store->address_store : '') }}</textarea>
                                        @error('address_store')
                                            <span class="invalid-feedback"><b>{{ $message }}</b></span>
                                        @enderror
                                    </div>
                                    
                
                                    <div class="col-md-3 mb-3">
                                        <label for="category_id">Category</label>
                                        <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                            <option value="">Select Category</option>
                                            @foreach ($categoryData as $category)
                                                <option value="{{ $category->id }}" @if (($Store ? $Store->category_id : '') == $category->id) selected @endif>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                        <span class="invalid-feedback"><b>{{ $message }}</b></span>
                                    @enderror
                                    </div>
                
                                    <div class="col-md-3 mb-3">
                                        <label for="sub_category_id">Sub Category</label>
                                        <select name="sub_category_id" id="sub_category_id" class="form-control @error('sub_category_id') is-invalid @enderror">
                                            <option value="">Select Sub Category</option>
                                            @foreach ($SubcategoryData as $category)
                                                <option value="{{ $category->id }}" @if (($Store ? $Store->sub_category_id : '') == $category->id) selected @endif>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('sub_category_id')
                                        <span class="invalid-feedback"><b>{{ $message }}</b></span>
                                    @enderror
                                    </div>
                
                                    <div class="col-md-6 mb-3">
                                        <label for="website_name">Website Name</label>
                                        <input type="text" name="website_name" id="website_name"
                                            value="{{ old('website_name') ? old('website_name') : ($Store ? $Store->website_name : '') }}"
                                            class="form-control @error('website_name') is-invalid @enderror">
                                        @error('website_name')
                                            <span class="invalid-feedback"><b>{{ $message }}</b></span>
                                        @enderror
                                    </div>
                
                                    <div class="col-md-6 mb-3">
                                        <label for="mobile">Mobile</label>
                                        <input type="tel" name="mobile" id="mobile"
                                            value="{{ old('mobile') ? old('mobile') : ($Store ? $Store->mobile : '') }}"
                                            class="form-control @error('mobile') is-invalid @enderror">
                                        @error('mobile')
                                            <span class="invalid-feedback"><b>{{ $message }}</b></span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3 mt-4">
                                        <label for="photo">Logo</label>
                                        <input type="file" name="photo" id="photo" class="@error('photo') is-invalid @enderror">
                                        
                                        @if ($Store && $Store->photo)
                                            <img src="{{ url('/images/' . $Store->photo) }}" alt="{{ $Store->title }}" width="100">
                                        @else
                                            <img src="{{ asset('images/no-image.png') }}" alt="No Image" width="100">
                                        @endif
                                        
                                        @error('photo')
                                            <span class="invalid-feedback"><b>{{ $message }}</b></span>
                                        @enderror
                                    </div>
                                    
                                
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status:</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Active"
                                                    {{ isset($Store) && $Store->status == 'Active' ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="In Active"
                                                {{ isset($Store) && $Store->status == 'In Active' ? 'selected' : '' }}>In Active
                                            </option>
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
    </div>
    <?php $__C = [];
    foreach ($SubcategoryDataTemp as $key => $category) {
        $__C[] = ['id' => $category->id, 'name' => $category->name, 'parent_id' => $category->parent_id];
    } ?>

    <script>
        var __C = <?php echo json_encode($__C); ?>;
        $(document).ready(function() {
            $('#category_id').change(function() {
                var op = '';
                op += '<option value="">Select Sub Category</option>';
                __C.filter((e) => e.parent_id == $(this).val()).map((data, i) => {
                    op += `<option value="${data.id}">${data.name}</option>`;
                })
                $('#sub_category_id').html(op);
            })
        });
    </script>
</x-app-layout>
