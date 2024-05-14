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


                                <form method="POST" action="{{ route('product.update', $productDetails->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="product_id" value="{{ $productDetails->product_id }}" />

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Product Name:</label>
                                                <input type="text"
                                                    class="form-control @error('title') is-invalid @enderror"
                                                    id="title" name="title" placeholder="Enter Product Name"
                                                    value="{{ isset($productDetails) ? $productDetails->title : old('title') }}">
                                                @error('title')
                                                    <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                                @enderror

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Product Status:</label>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="1"
                                                        {{ isset($productDetails) && $productDetails->visible == '1' ? 'selected' : '' }}>
                                                        Visible
                                                    </option>
                                                    <option value="In Active"
                                                        {{ isset($productDetails) && $productDetails->visible == '0' ? 'selected' : '' }}>
                                                        In Visible</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Description:</label>

                                                <textarea name="description" class="ckeditor form-control @error('description') is-invalid @enderror" id="description"
                                                    style="height:250px;">
                                                    {{ isset($productDetails) ? strip_tags($productDetails->description) : old('description') }}
                                                </textarea>


                                                @error('description')
                                                    <div class="invalid-feedback"><b>{{ $message }}</b></div>
                                                @enderror
                                            </div>
                                        </div>






                                    </div>

                                    <div class="col-md-12 text-center mt-4">
                                        <button type="submit"
                                            class="btn btn-secondary savebtn button_save">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });
    </script>
</x-app-layout>
