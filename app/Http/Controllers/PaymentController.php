<?php

namespace App\Http\Controllers;

// use Config;
use App\Models\DetailIuranRTPengguna;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function addToCart(Request $request)
    {
        $this->validate($request, [
            'qty' => 'numeric|required',
            'jumlah' => 'numeric|required'
        ]);

        $iuran = new DetailIuranRTPengguna();
        // $iuran -> id_iuran_rt = 
    }

    public function createCharge(Request $request)
    {
        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => $request->amount,
            ],
            'credit_card' => [
                'secure' => true
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        return response() -> json($snapToken);
    }
}
