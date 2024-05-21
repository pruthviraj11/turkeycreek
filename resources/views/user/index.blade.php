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
                    <h3>Users</h3>
                </div>
                <div class="card-body">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200">
                            <div class="container">
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                <?php
                                $i = 1;
                                ?>
                                <table class="table user-data-table">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Subscription Start Date</th>
                                            <th>Subscription End Date</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            {{-- <th>Description</th> --}}
                                            {{-- <th>Status</th> --}}
                                            {{-- <th>Actions</th> --}}
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
</x-app-layout>

<script type="text/javascript">
    $(function() {
        var table = $('.user-data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('user.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'start_date',
                    name: 'start_date',
                    render: function(data) {
                    if (data) {
                        return new Date(data).toLocaleDateString();
                    } else {
                        return '';
                    }
                }
                },
                {
                    data: 'end_date',
                    name: 'end_date',
                    render: function(data) {
                    if (data) {
                        return new Date(data).toLocaleDateString();
                    } else {
                        return '';
                    }
                }
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data) {
                    if (data) {
                        return new Date(data).toLocaleDateString();
                    } else {
                        return '';
                    }
                }
                },
                {
                    data: 'updated_at',
                    name: 'updated_at',
                    render: function(data) {
                        return new Date(data).toLocaleDateString();
                    }
                }
            ]
        });
    });
</script>
