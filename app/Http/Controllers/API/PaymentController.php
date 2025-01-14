<?php

namespace App\Http\Controllers\API;

use Stripe\Token;
use Stripe\Charge;
use Stripe\Stripe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function processDeposit(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'payment_type' => 'required|string|in:ApplePay,GooglePay,Tabby,Card',
            'amount' => 'required|numeric|min:1',
            'card_number' => 'required_if:payment_type,Card|string|min:16|max:16',
            'card_holder_name' => 'required_if:payment_type,Card|string|max:255',
            'expiry_date' => 'required_if:payment_type,Card|date_format:m/y',
            'cvc' => 'required_if:payment_type,Card|string|min:3|max:3',
        ]);

        try {
            // Configure Stripe
            Stripe::setApiKey(env('STRIPE_SECRET'));

            if ($validatedData['payment_type'] === 'Card') {
                // Tokenize the card details
                $token = Token::create([
                    'card' => [
                        'number' => $validatedData['card_number'],
                        'exp_month' => explode('/', $validatedData['expiry_date'])[0],
                        'exp_year' => '20' . explode('/', $validatedData['expiry_date'])[1],
                        'cvc' => $validatedData['cvc'],
                    ],
                ]);

                // Charge the card
                $charge = Charge::create([
                    'amount' => $validatedData['amount'] * 100, // Convert to cents
                    'currency' => 'aed',
                    'description' => 'Deposit to account',
                    'source' => $token->id,
                ]);

                

                return response()->json([
                    'status' => true,
                    'message' => 'Deposit successful',
                    'data' => $charge,
                ], 200);
            } else {
                

                return response()->json([
                    'status' => true,
                    'message' => 'Deposit via ' . $validatedData['payment_type'] . ' is successful.',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Deposit failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
