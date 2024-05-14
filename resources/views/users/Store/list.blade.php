@include('links')
<link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<x-app-layout>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Store</h3>
                </div>
                <div class="card-body">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200">
                            <div class="container">
                                <div class="text-right">
                                    <a href="{{ route('store-add') }}" class="btn add_storebtn">Add New Store</a>
                                </div>
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                <?php
                                $i = 1;
                                ?>
                                <table id="store-table" class="table store-table">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Store Name</th>
                                            <th>Category</th>
                                            <th>photo</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function() {
            var table = $('.store-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('store-list') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'subcategory_name',
                        name: 'subcategory_name',
                        render: function(data, type, full, meta) {
                            return data;
                        }
                    },
                    {
                        data: 'photo',
                        name: 'photo',
                        render: function(data, type, full, meta) {
                            if (data) {
                                return '<img src="{{ url('/images/') }}/' + data +
                                    '" alt="Store Photo" width="100" />';
                            } else {
                                return '<img src="{{ asset('images/no-image.png') }}" alt="No Image" width="100" />';
                            }
                        }
                    },


                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, full, meta) {
                            if (data === 'Active') {
                                return '<span class="badge bg-success">' + data + '</span>';
                            } else {
                                return '<span class="badge bg-danger">' + data + '</span>';
                            }
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
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
</x-app-layout>
