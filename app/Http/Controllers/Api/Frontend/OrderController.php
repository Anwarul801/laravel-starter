<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function bookOrderCheckout(Request $request)
    {
        try {

            $request->validate([
                'order_type'=>'required',
                'name' => 'exclude_if:order_type,ebook|required|string|max:255',
                'phone' => 'exclude_if:order_type,ebook|required|string|max:20',
                'shipping_address' => 'exclude_if:order_type,ebook|nullable|string',
                'courier' => 'exclude_if:order_type,ebook|nullable|in:ঢাকার ভিতর,ঢাকার বাইরে',
                'payment_method' => 'required|in:Cash on Delivery,Online Payment',
                'books' => 'required|array|min:1',
                'books.*.book_id' => 'required|exists:books,id',
                'books.*.quantity' => 'required|integer|min:1',
                'books.*.unit_price' => 'required|numeric|min:0',
            ]);


            $data = $this->orderService->bookOrderCheckout($request);

            return ResponseHelper::success($data, 'Book order created successfully');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function courseCheckout(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();

            $request->validate([
                'courses' => 'required|array|min:1',
                'referral_id' => 'nullable',
                'courses.*.id' => 'required|exists:courses,id',
            ]);

            $request->merge([
                'name' => $user->name,
                'phone' => $user->phone,
            ]);

            $data = $this->orderService->checkout($request);
            return ResponseHelper::success($data, 'Course order created successfully');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    
    public function couponApplied(Request $request)
    {
        try {
            $data = $this->orderService->applyCoupon($request->only(['amount', 'courseIds', 'coupon_code']));

            return ResponseHelper::success($data, 'Coupon applied successfully.');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }
}
