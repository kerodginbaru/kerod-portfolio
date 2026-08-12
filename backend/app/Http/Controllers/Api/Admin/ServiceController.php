<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Service::class);

        $services = Service::query()->orderBy('sort_order')->get();

        return $this->success(ServiceResource::collection($services), 'Services retrieved successfully.');
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        $this->authorize('create', Service::class);

        $service = Service::create($request->validated());

        return $this->success(new ServiceResource($service), 'Service created successfully.', 201);
    }

    public function update(UpdateServiceRequest $request, Service $service): JsonResponse
    {
        $this->authorize('update', $service);

        $service->update($request->validated());

        return $this->success(new ServiceResource($service), 'Service updated successfully.');
    }

    public function destroy(Service $service): JsonResponse
    {
        $this->authorize('delete', $service);

        $service->delete();

        return $this->success(null, 'Service deleted successfully.');
    }
}
