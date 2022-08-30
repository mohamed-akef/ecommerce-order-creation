<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\User;
use Foodics\Order\Command\CreateCommand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CreateController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @param Request       $request
     * @param CreateCommand $createOrderHandler
     * @return Response
     */
    public function __invoke(Request $request, CreateCommand $createOrderHandler): Response
    {
        /**
         * @todo add validation
         * @todo create a DTO for order data and pass it to the handler instead of array
         * @todo should extract user from session
         */
        $user = User::find(1);
        $order = $createOrderHandler->handle($request->get('products'), $user);
        $response = [
            'order_id' => $order->id
        ];
        return response(
            $response,
            201
        );
    }
}
