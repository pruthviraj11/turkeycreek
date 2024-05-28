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
</head>

<body>
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="container">
                        <div class="card-body">
                            <h5>Please Enter Here Your Payment Method</h5>
                            <div class="row g-0">
                                <div class="col-md-6">
                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    {{-- <a href="{{ route('app-showPaymentMethod', ['customerId' => $user->stripe_id]) }}"
                                        class="btn btn-danger btn-sm d-flex float-end mb-2 manage-payment-methods">Manage</a> --}}

                                    <form id="payment-form">
                                        <input id="card-holder-name" placeholder="Card Holder Name"
                                            class="form-control mb-2" type="text">
                                        <div id="card-element" class="form-control"></div> <br>
                                        {{-- <input type="checkbox" id="set-default" name="set_default" value="1">
                                        <label for="set-default">Set as default payment method</label> <br> --}}
                                        <button type="submit" class="btn mt-2 text-white"
                                            style="background-color:#726C49">Add Payment Method</button>
                                        <div id="loader" class="spinner-border mt-2 d-none" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Page js files -->

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        $(document).ready(function() {
            const stripe = Stripe(
                'pk_test_51P0oae07pZ01yxuzbmu79Tb2DmcPBn6FQD1Mb9IOcJd9CgBfFQLRT89wtQRoQKN32kRxvMkwdF2eJpql93SA5cZO00fw1PO2Rf'
                );
            const elements = stripe.elements();
            const cardElement = elements.create('card');
            cardElement.mount('#card-element');

            let isFirstPaymentMethod = true;

            $('#payment-form').submit(async function(e) {
                e.preventDefault();
                const cardHolderName = $('#card-holder-name').val();

                // Hide submit button and show loader
                $('#payment-form button[type="submit"]').hide();
                $('#loader').removeClass('d-none');

                const {
                    setupIntent,
                    error
                } = await stripe.confirmCardSetup('{{ $intent->client_secret }}', {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: cardHolderName
                        }
                    }
                });

                // Hide loader
                $('#loader').addClass('d-none');

                if (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error: ' + error.message,
                    });
                    // Show submit button again
                    $('#payment-form button[type="submit"]').show();
                } else {
                    // Check if it's the first payment method
                    if (isFirstPaymentMethod) {
                        // Send a request to your server to update the customer's default payment method
                        $.ajax({
                            url: '{{ route('update-default-payment-method') }}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                customer_id: '{{ $user->stripe_id }}',
                                payment_method: setupIntent.payment_method
                            },
                            success: function(response) {
                                if (response.success) {
                                    location.reload(); // Refresh the page on success
                                } else {
                                    alert('Failed to update payment method');
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Error: ' + error,
                                });
                                // Show submit button again
                                $('#payment-form button[type="submit"]').show();
                            },
                            complete: function() {
                                // Show submit button again
                                $('#payment-form button[type="submit"]').show();
                            }
                        });
                        isFirstPaymentMethod = false;
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Your payment method has been added successfully.',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                        // Show submit button again
                        $('#payment-form button[type="submit"]').show();
                    }
                }
            });
        });
    </script>
</body>

</html>
