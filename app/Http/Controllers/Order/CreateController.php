<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\User;
use Foodics\Order\Command\CreateCommand;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, CreateCommand $createOrderHandler)
    {
        /**
         * @todo add validation
         * @todo create a DTO for order data and pass it to the handler instead of array
         * @todo should extract user from session
         */
        $user = User::find(1);
        $createOrderHandler->handle($request->get('products'), $user);

        return response(status:201);
    }
}
