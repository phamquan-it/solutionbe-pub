<?php

namespace App\Http\Controllers;

use App\DTO\PaymentRequest;
use App\Http\Requests\ConfirmPaymentRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Cashflow;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * @OA\Tag(
 *     name="Payments",
 *     description="API Endpoints for Managing payments"
 * )
 */
class PaymentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/payments",
     *     summary="Get all payments with pagination & filtering",
     *     tags={"Payments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status (PENDDING, COMPLETED, CANCELED)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Filter by user ID",
     *         required=false,
     *         @OA\Schema(type="uuid")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Payment::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        $query->orderBy('created_at', 'desc');
        $perPage = $request->get('per_page', 10);
        $payments = $query->with('user')->paginate($perPage);

        return response()->json($payments, 200);
    }


    /**
     * @OA\Post(
     *     path="/api/payments",
     *     summary="Create a new payment",
     *     tags={"Payments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"amount", "status", "user_id"},
     *             @OA\Property(property="amount", type="number", format="float"),
     *             @OA\Property(property="status", type="string", enum={"PENDDING", "COMPLETED", "CANCELED"}),
     *             @OA\Property(property="user_id", type="string", format="uuid")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Created"),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(StorePaymentRequest $request): JsonResponse
    {
        $user = $request->user();

        // Update all existing "PENDING" payments to "CANCELED"
        Payment::where('user_id', $user->id)
            ->where('status', 'PENDING')
            ->update(['status' => 'CANCELED']);

        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => $request->validated()['amount'],
            'rate' => 24000, // Store exchange rate
        ]);

        return response()->json($payment, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/payments/{id}",
     *     summary="Get payment by ID",
     *     tags={"Payments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show($id): JsonResponse
    {
        $payment = Payment::findOrFail($id);
        return response()->json($payment);
    }

    /**
     * @OA\Patch(
     *     path="/api/payments/{id}",
     *     summary="Update a payment",
     *     tags={"Payments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", enum={"PENDDING", "COMPLETED", "CANCELED"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="Updated"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'status' => 'required|in:PENDDING,COMPLETED,CANCELED'
        ]);

        $payment->update($request->all());

        return response()->json($payment, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/payments/{id}",
     *     summary="Delete a payment",
     *     tags={"Payments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Deleted"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function destroy($id): JsonResponse
    {
        Payment::findOrFail($id)->delete();
        return response()->json(null, 204);
    }


    /**
     *
     * @OA\Get(
     *     path="/api/payments/stats",
     *     summary="Get payment statistic",
     *     tags={"Payments"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Get payment statistic",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="PENDING", type="integer", example=5),
     *             @OA\Property(property="COMPLETED", type="integer", example=10),
     *             @OA\Property(property="CANCELED", type="integer", example=2)
     *         )
     *     )
     * )
     */
    public function getPaymentStats(): JsonResponse
    {
        $stats = Payment::countByStatus(); // Call the countByStatus method from the Payment model
        return response()->json($stats);
    }


    public function receivePaymentRequest(ConfirmPaymentRequest $request)
    {
        // Call the Exchangerate API to get the latest exchange rates
        $response = Http::get('https://v6.exchangerate-api.com/v6/103b3f5359ad45d203040d22/latest/USD');

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Can\'t get exchange rate data'
            ], 500);
        }

        $data = $response->json();
        $vndRate = $data['conversion_rates']['VND']; // Extract VND rate

        return DB::transaction(function () use ($request, $vndRate) {
            // Process payment data
            $paymentConfirm = new PaymentRequest($request->validated());
            $cleanedContent = preg_replace('/[^0-9SC]/', '', $paymentConfirm->content); // Keep numbers and "SC"

            $hasSC = str_starts_with($cleanedContent, 'SC'); // Check if content starts with SC

            $number = (int) ltrim(preg_replace('/[^0-9]/', '', $cleanedContent), '0'); // Remove non-numeric chars and leading zeros
            if ($hasSC) {
                $project = Project::with('transactions')->findOrFail($number);
                $transactions = $project->transactions()->whereNull('transaction_content')->get();
                if ($transactions->isEmpty()) {
                    return response()->json(['message' => 'No transactions to verify'], 200);
                }
                if ($paymentConfirm->transferAmount / $vndRate > 1) {
                    $transactions->first()->update([
                        'transaction_content' => "or_{$project->id}_verified"
                    ]);
                    DB::commit(); // Commit transaction
                    return response()->json([
                        'message' => 'Project verified successfully',
                        'transactions' => $transactions
                    ], 200);
                }
                return response()->json("Not valid!", 403);
            } else {
                $number = preg_replace('/[^0-9]/', '', $paymentConfirm->content); // Extract numbers
                $number = (int) ltrim($number, '0'); // Remove leading zeros

                $payment = Payment::with('user')->find($number);
                if (!$payment) {
                    return response()->json(['error' => 'Payment not found'], 404);
                }

                if ($payment->status !== 'PENDING') {
                    return response()->json(['error' => 'Payment is not pending'], 400);
                }

                // Create Transaction
                $transaction = Transaction::create([
                    'gateway' => $paymentConfirm->gateway,
                    'transaction_date' => $paymentConfirm->transactionDate,
                    'account_number' => $paymentConfirm->accountNumber,
                    'amount_in' => $paymentConfirm->transferAmount,
                    'amount_out' => null,
                    'accumulated' => null,
                    'code' => $paymentConfirm->code,
                    'transaction_content' => $paymentConfirm->content,
                    'reference_number' => $paymentConfirm->referenceCode,
                    'body' => null
                ]);

                // Process User balance update
                $user = $payment->user;
                if (!$user) {
                    return response()->json(['error' => 'User not found'], 404);
                }

                if ($payment->rate == 0) {
                    return response()->json(['error' => 'Invalid payment rate'], 400);
                }

                $fluctuation = $paymentConfirm->transferAmount / $payment->rate;
                $user->remains += $fluctuation;
                $user->fund += $fluctuation;
                $user->save();

                // Create Cashflow record
                $cashflow = Cashflow::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'balance' => $user->remains,
                    'fluctuation' => $fluctuation,
                    'action' => 'deposit'
                ]);

                // Update payment details
                $payment->rate = $vndRate;
                $payment->status = 'COMPLETED';
                $payment->amount = $fluctuation;
                $payment->transaction_id = $transaction->id;
                $payment->save();

                return response()->json([
                    'payment' => $payment,
                    'transaction' => $transaction,
                    'user' => $user,
                    'cashflow' => $cashflow
                ]);
            }
        });
    }
}
