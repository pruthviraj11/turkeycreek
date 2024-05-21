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
                    <h3>Push Notification List</h3>
                </div>
                <div class="card-body">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200">
                            <div class="container">
                                {{-- <div class="text-right m-2">
                                    <a href="{{ route('event.create') }}" class="btn add_storebtn">Add New Event</a>

                                </div> --}}

                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                <?php
                                $i = 1;
                                ?>
                                <table class="table event-data-table">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Notification Title</th>

                                            <th>Notification Message Body</th>
                                            <th>Notification Type</th>

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
        var table = $('.event-data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('pushNotification.list') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'notification_title',
                    name: 'notification_title'
                },
                {
                    data: 'notification_message_body',
                    name: 'notification_message_body'
                },
                {
                    data: 'notification_type',
                    name: 'notification_type'
                },

                // {
                //     data: 'action',
                //     name: 'action',
                //     orderable: false,
                //     searchable: false
                // },
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
