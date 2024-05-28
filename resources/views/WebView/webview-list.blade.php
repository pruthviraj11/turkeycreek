<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @include('links')
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="container">
                        <div class="card-body">
                            <div class="row g-0">
                                @if ($paymentMethods->isEmpty())
                                    <div class="col-md-6">
                                        <h4>No Payment Methods Available</h4>
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <h4>Payment Methods</h4>
                                        <ul class="list-group mt-2">
                                            @foreach ($paymentMethods as $paymentMethod)
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center rounded-lg shadow mb-1">
                                                    <div>
                                                        <img src="{{ asset('images/icons/cards/' . $paymentMethod->card->brand . '.svg') }}"
                                                            class="me-25" height="32" alt="">
                                                        <span class="me-50">●●●●
                                                            {{ $paymentMethod->card->last4 }}</span>
                                                        <span class="text-muted me-1">expires
                                                            {{ $paymentMethod->card->exp_month }},
                                                            {{ $paymentMethod->card->exp_year }}</span>
                                                        @if ($paymentMethod->id === $customer->invoice_settings->default_payment_method)
                                                            <span class="badge bg-success">Default <i class="ficon"
                                                                    data-feather="check-circle"></i></span>
                                                        @endif
                                                    </div>
                                                    <a href="#" class="btn btn-sm text-white pay-now-btn"
                                                        style="background-color:#726C49 "
                                                        data-payment-method-id="{{ $paymentMethod->id }}">Pay Now</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Set the CSRF token for every AJAX request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.pay-now-btn').on('click', function(e) {
                e.preventDefault();
                var paymentMethodId = $(this).data('payment-method-id');

                $.ajax({
                    url: '{{ route('processPayment') }}',
                    method: 'POST',
                    data: {
                        payment_method_id: paymentMethodId,
                        customer_id: '{{ $customerId }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = '{{ route('successPage') }}';
                        } else {
                            window.location.href = '{{ route('failedPage') }}';
                        }
                    },
                    error: function(xhr, status, error) {
                        window.location.href = '{{ route('failedPage') }}';
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>

</body>

</html>
