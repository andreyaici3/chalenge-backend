<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Car;
use App\Models\Order;
use App\Traits\ResponseApi;
use App\Services\OrderService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class OrderController extends Controller
{
    use ResponseApi;
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $order = $this->orderService->index($request);
        return $this->sendResponse("Data Order Berhasil Di Ambil", $order);
    }

    public function show($orderId)
    {
        try {
            $order = Order::with(["car"])->findOrFail($orderId);

            return $this->sendResponse("Data Order Berhasil Di Ambil", $order);
        } catch (ModelNotFoundException $e) {
            return $this->sendError("Data Order Tidak Ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Error", [
                "err" => $e->getMessage()
            ]);
            return $this->sendError("Terjadi Kesalahan Saat memproses data", 500);
        }
    }

    public function store(OrderRequest $request, $carId)
    {
        try {
            $car = Car::findOrFail($carId);
            $result = $this->orderService->placeOrder($request, $car);
            if ($result["status"]) {
                return $this->sendResponse("Order Berhasil Ditambahkan", $result["data"]);
            }
            return $this->sendError("Order Gagal Ditambahkan", 400);
        } catch (ModelNotFoundException $e) {
            return $this->sendError("Data Mobil Tidak Ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Error", [
                "err" => $e->getMessage()
            ]);
            return $this->sendError("Terjadi Kesalahan Saat memproses data", 500);
        }
    }

    public function destroy($orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            $this->orderService->destroy($order);
            return $this->sendResponse("Data Order Berhasil Di Hapus");
        } catch (ModelNotFoundException $e) {
            return $this->sendError("Data Order Tidak Ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Error", [
                "err" => $e->getMessage()
            ]);
            return $this->sendError("Terjadi Kesalahan Saat memproses data", 500);
        }
    }

    public function update(OrderRequest $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            $car = Car::findOrFail($request->car_id);

            $result = $this->orderService->update($request, $order, $car);

            if ($result["status"]) {
                return $this->sendResponse("Order Berhasil DiUbah", $result["data"]);
            }
            return $this->sendError("Order Gagal Di Ubah", 400);
        } catch (ModelNotFoundException $e) {
            return $this->sendError("Data Ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Error", [
                "err" => $e->getMessage()
            ]);
            return $this->sendError("Terjadi Kesalahan Saat memproses data", 500);
        }
    }
}
