<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Traits\ResponseApi;
use App\Services\CarService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    protected $carService;
    use ResponseApi;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    public function index(Request $request)
    {
        return $this->carService->index($request);
    }

    public function store(CarRequest $request)
    {
        try {
            $store = $this->carService->create($request);
            return $this->sendResponse("Data Mobil Berhasil Ditambahkan", $store);
        } catch (Exception $e) {
            Log::error("Error => ", [
                'message' => $e->getMessage()
            ]);
            return $this->sendError("Terjadi Kesalahaan Saat Memproses Data", 500);
        }
    }

    public function update(CarRequest $request, $car)
    {
        try {
            $car = Car::findOrFail($car);
            $this->carService->update($request, $car);

            return $this->sendResponse("Data Mobil Berhasil Di Ubah");
        } catch (ModelNotFoundException $e) {
            return $this->sendError("Data Mobil Tidak Ditemukan", 404);
        } catch (Exception $e) {
            Log::error("error", [
                'errr' => $e->getMessage()
            ]);
            return $this->sendError("Terjadi Kesalahaan Saat Memproses Data", 500);
        }
    }

    public function destroy($carId)
    {
        try {
            $car = Car::findOrFail($carId);
            $this->carService->delete($car);
            return $this->sendResponse("Data Mobil Berhasil Di Hapus");
        } catch (ModelNotFoundException $e) {
            return $this->sendError("Data Mobil Tidak Ditemukan", 404);
        } catch (Exception $e) {
            Log::error("error", [
                'errr' => $e->getMessage()
            ]);
            return $this->sendError("Terjadi Kesalahaan Saat Memproses Data", 500);
        }
    }

    public function show($carId)
    {
        try {
            $order = Car::findOrFail($carId);

            return $this->sendResponse("Data Mobil Berhasil Di Ambil", $order);
        } catch (ModelNotFoundException $e) {
            return $this->sendError("Data Mobil Tidak Ditemukan", 404);
        } catch (Exception $e) {
            Log::error("Error", [
                "err" => $e->getMessage()
            ]);
            return $this->sendError("Terjadi Kesalahan Saat memproses data", 500);
        }
    }
}
