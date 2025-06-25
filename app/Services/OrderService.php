<?php

namespace App\Services;

use App\Http\Requests\OrderRequest;
use App\Models\Car;
use App\Models\Order;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class OrderService
{
    use ResponseApi;

    public function index(Request $request)
    {
        $order = Order::with(["car"])->get();
        return $order;
    }

    public function placeOrder(OrderRequest $request, Car $car)
    {
        $orderExists = Order::where('car_id', $car->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('pickup_date', [$request->pickup_date, $request->dropoff_date])
                    ->orWhereBetween('dropoff_date', [$request->pickup_date, $request->dropoff_date])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('pickup_date', '<=', $request->pickup_date)
                            ->where('dropoff_date', '>=', $request->dropoff_date);
                    });
            })
            ->exists();

        if (!$orderExists) {
            $order = Order::create([
                "car_id" => $car->id,
                "order_date" => $request->order_date,
                "pickup_date" => $request->pickup_date,
                "dropoff_date" => $request->dropoff_date,
                "pickup_location" => $request->pickup_location,
                "dropoff_location" => $request->dropoff_location
            ]);


            return [
                'status' => true,
                'data' => $order,
            ];
        };

        return [
            "status" => false,
            "data" => null
        ];
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return true;
    }

    public function update(OrderRequest $request, $order, $car)
    {
        $orderExists = Order::where('car_id', $car->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('pickup_date', [$request->pickup_date, $request->dropoff_date])
                    ->orWhereBetween('dropoff_date', [$request->pickup_date, $request->dropoff_date])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('pickup_date', '<=', $request->pickup_date)
                            ->where('dropoff_date', '>=', $request->dropoff_date);
                    });
            })
            ->where('id', "!=", $order->id)
            ->exists();


        if (!$orderExists) {
            $order->update([
                "car_id" => $car->id,
                "order_date" => $request->order_date,
                "pickup_date" => $request->pickup_date,
                "dropoff_date" => $request->dropoff_date,
                "pickup_location" => $request->pickup_location,
                "dropoff_location" => $request->dropoff_location
            ]);

            return [
                'status' => true,
                'data' => $order,
            ];
        };

        return [
            "status" => false,
            "data" => null
        ];
    }
}
