<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A Responsive Email Template</title>
</head>
<body style="margin: 0; padding: 0;">
    <div style="border: 1px solid #ddd; border-radius: 8px; background-color: #fff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 20px; text-align: left; max-width: 384px; margin: auto; margin-top: 70px; height: auto;">
        <div style="background-color: #FFFFFF; padding: 0 15px;">
            <div style="width: 100%; text-align: center; padding: 15px 0;">
                <img src="/assets/imgs/TurkeyCreekLogo.png" alt="Logo" width="140px" style="display: inline-block;">
            </div>
        </div>
        <h2 style="text-align: center; margin-bottom: 20px; font-size: 34px; margin: auto;">Order Confirmation</h2>
        <hr>
        <p>Your order has been successfully placed.</p>
        <h2>Here Are Your Order Details:</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ddd; background-color: #f2f2f2; padding: 8px; text-align: left;">Image</th>
                    <th style="border: 1px solid #ddd; background-color: #f2f2f2; padding: 8px; text-align: left;">Name</th>
                    <th style="border: 1px solid #ddd; background-color: #f2f2f2; padding: 8px; text-align: left;">Quantity</th>
                    <th style="border: 1px solid #ddd; background-color: #f2f2f2; padding: 8px; text-align: left;">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderDetails as $product)
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;"><img src="{{ $product['image'] }}" alt=""></td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $product['name'] }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $product['quantity'] }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">$ {{ number_format($product['price'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="text-align: right; margin-bottom:6px" id="netAmount"></p>
        <p style="text-align: right ;margin-bottom:6px;margin-top:0px" id="tax"></p>
        <p style="text-align: right; margin-bottom:6px; margin-top:0px" id="shippingCharge"></p>
        <p style="text-align: right; margin-top:0px" id="total"></p>
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var netAmount = @json(collect($orderDetails)->sum('price'));
                var tax = (netAmount * 18) / 100;
                var shippingCharge = 20;
                var totalAmount = netAmount + tax + shippingCharge;
                document.getElementById('netAmount').textContent = 'Net Amount: $' + netAmount.toFixed(2);
                document.getElementById('tax').textContent = 'Tax: $' + tax.toFixed(2);
                document.getElementById('shippingCharge').textContent = 'Shipping Charge: $' + shippingCharge.toFixed(2);
                document.getElementById('total').textContent = 'Total: $' + totalAmount.toFixed(2);
            });
        </script>
        <div style="text-align: center;">
            <h2>Thank You For Your Purchase.</h2>
        </div>
    </div>
</body>
</html>
