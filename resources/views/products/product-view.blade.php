@include('links')
<link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<x-app-layout>
    <div class="row">
        <!----- Product Details Information -------------->
        {{-- <div class="card-header mb-5">
            <h3>{{ $data['form_title'] }}</h3>
        </div> --}}
        <div class="col-md-12 productInfo">
            <h2>View Product Information</h2>

            <div class="row mt-2">
                <div class="col-md-2">Product Name</div>
                <div class="col-md-1">:</div>
                <div class="col-md-9">{{ $productDetails->title }}</div>
            </div>


            <div class="row mt-2">
                <div class="col-md-2">Product Description</div>
                <div class="col-md-1">:</div>
                <div class="col-md-9">{{ strip_tags($productDetails->description) }}</div>
            </div>

            @php
                $product_json = json_decode($productDetails->product_data, true);
                $productimages = $product_json['images'];

            @endphp

            <div class="row mt-5">
                <div class="col-md-2">Product Images</div>
                <div class="col-md-1">:</div>
                <div class="col-md-9">
                    <div class="row">

                        @foreach ($productimages as $productimage)
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <img src="{{ $productimage['src'] }}" />
                                    </div>
                                    <div class="col-md-12">
                                        <p class="text-center imagetype">{{ $productimage['position'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach








                    </div>



                </div>
            </div>



        </div>
        <!------- End Product Details Information ------->




</x-app-layout>

<style>
    .productInfo h2 {
        background-color: #b0ac89;
        padding: 10px;
        font-weight: bold;
    }

    .imagetype {
        font-size: 15px;
        font-weight: bold;
    }
</style>
