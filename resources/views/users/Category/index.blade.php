@include('links')
<!-- Include Bootstrap JavaScript and jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
<x-app-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <div class="container">

                @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading">Success!</h4>
                    <p>{{ Session::get('success') }}</p>

                    <button type="button" class="close" data-dismiss="alert" aria-label=" Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if (Session::has('errors'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading">Error!</h4>
                    <p>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    </p>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif


                <div class="row">
                    <div class="col-md-12">
                        <div class="text-right mb-2">
                            <a href="{{ route('category-add') }}" class="btn add_storebtn">Add New Category</a>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3>Category</h3>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    @foreach ($categories as $category)
                                    <li class="list-group-item">

                                        {{-- perent category --}}
                                        
                                        <div class="d-flex justify-content-between">
                                            {{ $category->name }}
                                            

                                            <div class="button-group d-flex">
                                                {{-- <a class="btn btn-warning edit-category mr-2" data-toggle="modal" data-target="#editCategoryModal" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-parent-id="{{ $category->parent_id }}"><i class="fa fa-edit"></i></a> --}}
                                                <a href="{{ route('category-edit', $category->id) }}" class="btn btn-warning edit-category mr-2"><i class="fa fa-edit"></i></a>

                                                <form action="{{ route('category-delete', $category->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return deleteConfirm()" class="btn bg-danger btn-danger"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>

                                        @if ($category->subcategory)

                                        {{-- Chiled category --}}

                                        <ul class="list-group mt-2">
                                            @foreach ($category->subcategory as $child)
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between">
                                                    {{ $child->name }}

                                                    <div class="button-group d-flex">
                                                        <a href="{{ route('category-edit', $child->id) }}" class="btn btn-warning edit-category mr-2"><i class="fa fa-edit"></i></a>
                                                        <form action="{{ route('category-delete', $child->id) }}" method="POST" class="m-0">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit" onclick="return deleteConfirm()" class="btn bg-danger btn-danger"><i class="fa fa-trash"></i></button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                   
                </div>

                <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

                <script type="text/javascript">
                    $('.edit-category').on('click', function() {
                        var id = $(this).data('id');
                        var name = $(this).data('name');
                        var parent = $(this).data('parent-id');
                        var url = "{{ route('category-update','') }}/" + id;
                        $('#editCategoryModal form').attr('action', url);
                        $('#editCategoryModal form input[name="name"]').val(name);
                        /* $('#editCategoryModal form [name="parent"] option[value="'+parent+'"]').prop('selected',true); */
                        if(parent){
                            $('.parent_group').css({'display':'block'});
                        } else {
                            $('.parent_group').css({'display':'none'});
                        }
                        $('#editCategoryModal form select[name="parent_id"]').val(parent);
                    });
                </script>
                <script>
                    function deleteConfirm() {
                        if (confirm("Are you sure to delete data?")) {
                            return true;
                        }
                        return false;
                    }
                </script>
            </div>
        </div>
    </div>
</x-app-layout>