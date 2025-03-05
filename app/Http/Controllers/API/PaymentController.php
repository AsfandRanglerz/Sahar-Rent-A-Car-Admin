<?php

namespace App\Http\Controllers\API;

use Stripe\Token;
use Stripe\Charge;
// use Stripe\Stripe;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function processDeposit(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'payment_type' => 'required|string|in:ApplePay,GooglePay,Tabby,Card',
            'amount' => 'required|numeric|min:1',
        //     'card_number' => 'required_if:payment_type,Card|string|min:16|max:16',
        //     'card_holder_name' => 'required_if:payment_type,Card|string|max:255',
        //     'expiry_date' => 'required_if:payment_type,Card|date_format:m/y',
        //     'cvc' => 'required_if:payment_type,Card|string|min:3|max:3',
        ]);

        try {
            // Configure Stripe
            // Stripe::setApiKey(config('services.stripe.secret'));

            // if ($validatedData['payment_type'] === 'Card') {
            //     // Tokenize the card details
            //     $token = Token::create([
            //         'card' => [
            //             'number' => $validatedData['card_number'],
            //             'exp_month' => explode('/', $validatedData['expiry_date'])[0],
            //             'exp_year' => '20' . explode('/', $validatedData['expiry_date'])[1],
            //             'cvc' => $validatedData['cvc'],
            //         ],
            //     ]);

            //     // Charge the card
            //     $charge = Charge::create([
            //         'amount' => $validatedData['amount'] * 100, // Convert to cents
            //         'currency' => 'aed',
            //         'description' => 'Deposit to account',
            //         'source' => $token->id,
            //     ]);
            $status = ($validatedData['payment_type'] === 'Card') ? 'approved' : 'pending';
                $charge = Transaction::create([
                    'user_id' => Auth::id(),
                    'payment_type' => $validatedData['payment_type'],
                    'amount' => $validatedData['amount'],
                    // 'transaction_id' => null, // Stripe transaction ID
                    'status' => $status, // e.g., succeeded, failed
                ]);

                return response()->json([
                    // 'status' => true,
                    'message' => 'Deposit successful',
                    'data' => $charge,
                ], 200);
            // } 
            // else {

            //      $payment = Transaction::create([
            //         'user_id' => Auth::id(),
            //         'payment_type' => $validatedData['payment_type'],
            //         'amount' => $validatedData['amount'],
            //         'transaction_id' => null,
            //         'status' => 'pending',
            //     ]);

            //     return response()->json([
            //         // 'status' => true,
            //         'message' => 'Deposit via ' . $validatedData['payment_type'] . ' is successful.',
            //     ], 200);
            // }
        } catch (\Exception $e) {
            return response()->json([
                // 'status' => false,
                'message' => 'Deposit failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getWalletHistory()
{
    $userId = Auth::id(); // Get logged-in user ID

    if (!$userId) {
        return response()->json([
            'message' => 'User not authenticated',
        ], 401);
    }

    // Fetch all transactions for the logged-in user
    $transactions = Transaction::where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'wallet_balance' => Transaction::where('user_id', $userId)
            ->where('status', 'approved')
            ->sum('amount'), // Sum only approved transactions
        'history' => $transactions,
    ], 200);
}

}
