<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Responsive Email Template</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Spectral+SC:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');
</style>

<body style="margin: 0; padding: 0;">
    <div
        style="border: 1px solid #ddd; border-radius: 8px; background-color: #fff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 20px; text-align: left; max-width: 384px; margin: auto; margin-top: 70px; height: auto;">
        <div style="background-color: #FFFFFF; padding: 0 15px;">
            <div style="width: 100%; text-align: center; padding: 15px 0;">
                <img src="/assets/imgs/TurkeyCreekLogo.png" alt="Logo" width="140px" style="display: inline-block;">
            </div>
        </div>
        <h2
            style="text-align: center; margin-bottom: 20px; font-size: 34px; margin: auto; font-family: 'Spectral SC', serif; ">
            Order Confirmation</h2>
        <hr>
        <p style="font-family: 'Spectral SC', serif;font-size:16px; color:#198754">Your order has been successfully placed.</p>
        <h2 style="font-family: 'Spectral SC', serif;font-size:24px">Here Are Your Order Details:</h2>
        <table style="width: 100%; border-collapse: collapse; font-family: 'Spectral SC', serif;font-size:16px">
            <thead>

                <tr>
                    <th style="border: 1px solid #ddd; background-color: #f2f2f2; padding: 8px; text-align: left;">Image
                    </th>
                    <th style="border: 1px solid #ddd; background-color: #f2f2f2; padding: 8px; text-align: left;">Name
                    </th>
                    <th style="border: 1px solid #ddd; background-color: #f2f2f2; padding: 8px; text-align: left;">
                        Quantity</th>
                    <th style="border: 1px solid #ddd; background-color: #f2f2f2; padding: 8px; text-align: left;">Price
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($emailData as $product)
                    <?php $productName = App\Models\Product::where('product_id', $product['product_id'])->first();
                    $productData = json_decode($productName->product_data);
                    $images = $productData->images;

                    $imageSrc = $images[0]->src;
                    ?>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;"><img src="{{ $imageSrc }}"
                                style="width: 40px" alt=""></td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $productName->title }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $product->quantity }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${{ $product->amount }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p style="text-align: right; margin-bottom:6px ;font-family: 'Spectral SC', serif;font-size: 16px;" id="netAmount">NetAmount:${{ $amount }}</p>
        <p style="text-align: right ;margin-bottom:6px;margin-top:0px;font-family: 'Spectral SC', serif; font-size: 16px;" id="tax">Tax:${{ $tax }}</p>
        <p style="text-align: right; margin-bottom:6px; margin-top:0px;font-family: 'Spectral SC', serif; font-size: 16px;" id="shippingCharge">Shipping
            Charge:${{ $shippingCharge }}</p>
        <p style="text-align: right; margin-top:0px;font-family: 'Spectral SC', serif; font-size: 16px;" id="total">Total:${{ $totalAmount }}</p>
        <div style="text-align: center;font-family: 'Spectral SC', serif;font-size: 18px;">
            <h2>Thank You For Your Purchase.</h2>
        </div>
    </div>
</body>

</html>
