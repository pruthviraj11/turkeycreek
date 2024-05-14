@include('links')
<link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<x-app-layout>
    <div class="row">
        <!----- Product Details Information -------------->
        <div class="col-md-12 productInfo">
            <h2>Product Information</h2>
            {{-- {{ dd($productDetails); }} --}}
            @foreach ($productDetails as $productDetail)
                <div class="row mt-2">
                    <div class="col-md-2">Product Name</div>
                    <div class="col-md-1">:</div>
                    <div class="col-md-9">{{ $productDetail['metadata']['title'] }}</div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-2">Variant Type</div>
                    <div class="col-md-1">:</div>
                    <div class="col-md-9">{{ $productDetail['metadata']['variant_label'] }}</div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-2">Sku Code</div>
                    <div class="col-md-1">:</div>
                    <div class="col-md-9">{{ $productDetail['metadata']['sku'] }}</div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-2">Country</div>
                    <div class="col-md-1">:</div>
                    <div class="col-md-9">{{ $productDetail['metadata']['country'] }}
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-2">Quntity</div>
                    <div class="col-md-1">:</div>
                    <div class="col-md-9">{{ $productDetail['quantity'] }}
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-2">Shipping_cost</div>
                    <div class="col-md-1">:</div>
                    <div class="col-md-9">${{ $productDetail['shipping_cost'] }}
                    </div>
                </div>


                <div class="row mt-2">
                    <div class="col-md-2">status</div>
                    <div class="col-md-1">:</div>
                    <div class="col-md-9">
                        {{ ucfirst($productDetail['status']) }}</div>
                </div>

                @php
                    $product_json = json_decode($productView->product_data, true);
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
            @endforeach





        </div>
        <!------- End Product Details Information ------->


        <!------ Start Shipping Address Information ---->
        <div class="col-md-12 mt-4 productInfo">
            <h2>Address Information</h2>
            <div class="row mt-2">
                <div class="col-md-2">Name</div>
                <div class="col-md-1">:</div>
                <div class="col-md-9">
                    {{   isset($shippingAddress['first_name']) && isset($shippingAddress['last_name']) ? $shippingAddress['first_name'] . ' ' . $shippingAddress['last_name'] : 'N/A' }}
                </div>
                <div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-2">Email</div>
                    <div class="col-md-1">:</div>
                    <div class="col-md-9">
                      {{ isset($shippingAddress['email']) ? $shippingAddress['email'] : 'N/A' }}
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-2">Mobile No</div>
                    <div class="col-md-1">:</div>
                    <div class="col-md-9">
                      {{ isset($shippingAddress['phone']) ? $shippingAddress['phone'] : 'N/A' }}
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-2">Region - Country</div>
                    <div class="col-md-1">:</div>
                    <div class="col-md-9">
                        {{ isset($shippingAddress['region']) && isset($shippingAddress['country']) ? $shippingAddress['region'] . ' ' . $shippingAddress['country'] : 'N/A' }}
                    </div>


                    <div class="row mt-2">
                        <div class="col-md-2">Address 1</div>
                        <div class="col-md-1">:</div>
                      <div class="col-md-9">  {{ isset($shippingAddress['address1']) ? $shippingAddress['address1'] : 'N/A' }}</div>
                    </div>
              

                    <div class="row mt-2">
                        <div class="col-md-2">Address 2</div>
                        <div class="col-md-1">:</div>
                      <div class="col-md-9">  {{ isset($shippingAddress['address2']) ? $shippingAddress['address2'] : 'N/A' }}</div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-2">City</div>
                    <div class="col-md-1">:</div>
                    <div class="col-md-9">
                  {{isset($shippingAddress['city']) ? $shippingAddress['city'] : 'N/A'}}</div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-2">Zip</div>
                <div class="col-md-1">:</div>
                <div class="col-md-9">
                    {{  isset($shippingAddress['zip']) ? $shippingAddress['zip'] : 'N/A' }}
                </div>
            </div>


            <!----- End Shipping Address Information ---->





        </div>
</x-app-layout>

<style>
    .productInfo h2 {
        background-color: #b0ac89;
        padding: 10px;
        font-weight: bold;
    }
</style>
