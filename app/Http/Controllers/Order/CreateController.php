<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\User;
use Foodics\Order\Command\CreateCommand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreateController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @param Request       $request
     * @param CreateCommand $createOrderCommand
     * @return JsonResponse
     */
    public function __invoke(Request $request, CreateCommand $createOrderCommand): JsonResponse
    {
        $validation = Validator::make(
            $request->all(),
            [
                'products' => 'required|array',
                'products.*.product_id' => 'required|int|exists:App\Models\Product,id',
                'products.*.quantity' => 'required|int',
            ]
        );

        if ($validation->valid()) {
            /**
             * @todo create a DTO for order data and pass it to the handler instead of array
             * @todo should extract user from session
             */
            $user = User::find(1);
            $order = $createOrderCommand->handle($request->get('products'), $user);
            $response = \response()->json(['order_id' => $order->id], 201);
        } else {
            $response = \response()->json($validation->errors(), 422);
        }

        return $response;
    }
}
