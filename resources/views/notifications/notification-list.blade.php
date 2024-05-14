@include('links')
<link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<x-app-layout>

    <div class="row">
        <div class="col-12 col-lg-8 col-xl-7">
            <div class="card">
                <div class="card-header  ">
                    <h4 class="card-title">Send Notifications</h4>
                    {{-- <a href="{{ route('pushNotification.index') }}" class="btn btn-sm btn-primary  ">Send
                                    Notifications</a> --}}
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="form form-vertical" id="packages-form" action="{{ route('pushNotification.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-1">
                                    <label class="form-label" for="notification_title">Title</label>
                                    <input type="text" id="notification_title"
                                        class="form-control @error('notification_title') is-invalid @enderror"
                                        name="notification_title" placeholder="Notification Title"
                                        value="{{ old('notification_title') }}" required />
                                    @error('notification_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-1 @error('notification_message_body') is-invalid @enderror">
                                    <label class="form-label" for="notification_message_body">Message Body</label>
                                    <textarea class="form-control @error('notification_message_body') is-invalid @enderror" id="notification_message_body"
                                        name="notification_message_body" rows="5" placeholder="Message Body" required>{{ old('notification_message_body') }}</textarea>
                                    @error('notification_message_body')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" id="sendToAllMembers"
                                        name="sendToAllMembers">
                                    <label class="form-check-label" for="sendToAllMembers">Send to All
                                        Members</label>
                                </div>
                                <div class="">OR</div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-1">
                                    <label class="form-label" for="select-members">Select Members</label>
                                    <select class="form-control select2" name="selectedMembers[]"
                                        multiple>
                                        @foreach ($users as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-1">
                                    <label class="form-label" for="notification_type">Notification Type</label>
                                    <input type="text" id="notification_type"
                                        class="form-control @error('notification_type') is-invalid @enderror"
                                        name="notification_type" placeholder="Notification Type"
                                        value="{{ old('notification_type') }}" required />
                                    @error('notification_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-secondary savebtn button_save">Submit</button>
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection
