<?php

namespace App\Services;

use App\Http\Requests\CarRequest;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarService
{

    public function index(Request $request)
    {
        $search = $request->input("search");
        $car = Car::query();
        if ($search) {
            $car->where('car_name', "LIKE", "%{$search}%");
        }

        return $car->paginate(10)->withQueryString();
    }

    public function create(CarRequest $carRequest)
    {
        $image = $carRequest->file("image");
        $name = uniqid() . ".{$image->getClientOriginalExtension()}";
        $imagePath = $image->storeAs("car_image", $name, 'public');

        $car = Car::create([
            'car_name' => $carRequest->car_name,
            'day_rate' => $carRequest->day_rate,
            'month_rate' => $carRequest->month_rate,
            'image' => $imagePath,
        ]);

        return $car;
    }

    public function update(CarRequest $carRequest, Car $car)
    {
        $path = $car->image;
        if ($carRequest->hasFile("image")) {
            if (Storage::exists($path)) {
                Storage::delete($car->image);
            }
            $path = $this->uploadImage($carRequest->file("image"));
        }

        $car->update([
            'car_name' => $carRequest->car_name,
            'day_rate' => $carRequest->day_rate,
            'month_rate' => $carRequest->month_rate,
            'image' => $path,
        ]);

        return true;
    }

    public function delete($car)
    {
        if (Storage::exists($car->image)) {
            Storage::delete($car->image);
        }
        $car->delete();

        return true;
    }

    private function uploadImage($image)
    {
        $name = uniqid() . ".{$image->getClientOriginalExtension()}";
        $imagePath = $image->storeAs("car_image", $name, 'public');

        return $imagePath;
    }
}
