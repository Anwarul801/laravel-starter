<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Models\Transaction;

class PaymentController extends Controller
{
    protected $paymentService;
    protected $frontendUrl;
    protected $frontendPaths;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->frontendUrl = config('frontend.url', 'http://localhost:3000');
         $this->frontendPaths = config('frontend.paths', [
            'payment_success' => '/payment-success',
            'payment_failed' => '/payment-failed',
            'payment_cancelled' => '/payment-cancel',
            'payment_error' => '/payment-cancel',
        ]);
    }


    private function buildFrontendUrl($path, $params = [])
    {
        $url = rtrim($this->frontendUrl, '/') . '/' . ltrim($path, '/');

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }

    public function success(Request $request)
    {
        try {
            $result = $this->paymentService->handleSuccess($request->all());

            if ($result['success'] == true) {

                return redirect()->to($this->buildFrontendUrl(
                    $this->frontendPaths['payment_success'],
                    [
                        'order_id' => $result['enroll_id'] ?? null
                    ]
                ));
                
            } else {
                return redirect()->to($this->buildFrontendUrl(
                    $this->frontendPaths['payment_failed'],

                ));
            }
        } catch (\Exception $e) {
            return redirect()->to($this->buildFrontendUrl(
                $this->frontendPaths['payment_error'],
                ['message' => $e->getMessage()]
            ));
        }
    }

    public function fail(Request $request)
    {
        try {
            $result = $this->paymentService->handleFail($request->all());

            return redirect()->to($this->buildFrontendUrl(
                $this->frontendPaths['payment_failed'],
            ));
        } catch (\Exception $e) {
            return redirect()->to($this->buildFrontendUrl(
                $this->frontendPaths['payment_error'],
                ['message' => $e->getMessage()]
            ));
        }
    }

    public function cancel(Request $request)
    {
        try {
            $result = $this->paymentService->handleCancel($request->all());

            return redirect()->to($this->buildFrontendUrl(
                $this->frontendPaths['payment_cancelled'],
            ));
        } catch (\Exception $e) {
            return redirect()->to($this->buildFrontendUrl(
                $this->frontendPaths['payment_error'],
                ['message' => $e->getMessage()]
            ));
        }
    }

    public function ipn(Request $request)
    {
        $result = $this->paymentService->handleIpn($request->all());

        return response()->json($result);
    }


    public function checkTransactionStatus(Request $request)
    {
        try {
            $request->validate([
                'transaction_id' => 'required|string'
            ]);

            $transaction = Transaction::where('transaction_id', $request->transaction_id)->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'transaction' => [
                    'id' => $transaction->id,
                    'transaction_id' => $transaction->transaction_id,
                    'user_id' => $transaction->user_id,
                    'amount' => $transaction->amount,
                    'status' => $transaction->status,
                    'payment_for' => $transaction->payment_for,
                    'course_ids' => $transaction->course_ids ? json_decode($transaction->course_ids) : null,
                    'enroll_ids' => $transaction->enroll_ids ? json_decode($transaction->enroll_ids) : null,
                    'order_id' => $transaction->order_id,
                    'created_at' => $transaction->created_at,
                    'updated_at' => $transaction->updated_at,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
