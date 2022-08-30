# Welcome!

This application build in top of laravel 9 and dockerized by sail

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/).
2. Run `./vendor/bin/sail up` to state the contender.
3. Use [the](https://www.getpostman.com/collections/05d64d2ee31c2a162cad) collection to test the API.

## Main concerns
* The first issue that may happen is the deadlock because we have multiple ingredients shared between Products so I go with adding every ingredient deduct in a separated transaction.
* The other part is notify when the stock reach limits and that will be done by worker will run every 5m to check if the current stock  < init stock, and it was not notified before(by key in db) will send the email.

## DB structure
![Screenshot from 2022-08-31 01-04-30](https://user-images.githubusercontent.com/1524321/187562633-ccd74ed4-4aec-43a7-93fe-9dc830cdf760.png)

## Request life cycle

* `\App\Http\Controllers\Order\CreateController` will call the Command(DDD concept).
* `\Foodics\Order\Command\CreateCommand`:
* * will create the order with `pending` status.
* * Then will call the Service to add the products into it.
* `\Foodics\Order\Service\NewOrder\AddProductToOrder` Add ProductOrder Row then:
* * start transaction and select the ingredient then deduct the quantity from it
* * update ingredient and insert row for ProductOrderIngredient then close the transaction

## Workers Part
* The first one is `FailedOrdersCleaner` it responsible to check any order that create from `5m` and the status still `pending`, so the worker will return the ingredients to stock again and change order status to `canceled`.
* The other one is `StockChangeNotifier` it will check (`current_stock` + uncompleted orders stock) < `init_stock` and notify flag is not flagged then will send email to admin
