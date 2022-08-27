<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Foodics\Order\Handler\CreateHandler;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, CreateHandler $createOrderHandler)
    {
        /**
         * @todo add validation
         * @todo create a DTO for order data and pass it to the handler instead of array
         */
        $createOrderHandler->handle($request->get('products'));

        return response(status:201);
    }
}
