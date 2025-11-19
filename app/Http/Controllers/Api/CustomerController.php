<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCustomerRequest;
use App\Http\Requests\Api\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     * 
     * Filtros disponibles:
     * - search: buscar por nombre, email o teléfono
     * - customer_type: filtrar por tipo (person/company)
     * - is_active: filtrar por estado (1/0)
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Customer::query();

        // Búsqueda por nombre, email o teléfono
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%");
            });
        }

        // Filtrar por tipo de cliente
        if ($customerType = $request->input('customer_type')) {
            $query->where('customer_type', $customerType);
        }

        // Filtrar por estado activo/inactivo
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Ordenar por nombre
        $query->orderBy('name', 'asc');

        // Paginación (15 por página por defecto)
        $customers = $query->paginate($request->input('per_page', 15));

        return CustomerResource::collection($customers);
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customer = Customer::create($request->validated());

        return (new CustomerResource($customer))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer): CustomerResource
    {
        // Cargar el conteo de pedidos si está disponible
        $customer->loadCount('orders');

        return new CustomerResource($customer);
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): CustomerResource
    {
        // No permitir cambiar el tipo de cliente
        $data = $request->validated();
        if (isset($data['customer_type']) && $data['customer_type'] !== $customer->customer_type) {
            return response()->json([
                'message' => 'No se puede cambiar el tipo de cliente.',
                'errors' => [
                    'customer_type' => ['El tipo de cliente no puede ser modificado.']
                ]
            ], 422);
        }

        $customer->update($data);

        return new CustomerResource($customer);
    }

    /**
     * Toggle customer active status.
     */
    public function toggleStatus(Customer $customer): CustomerResource
    {
        $customer->update([
            'is_active' => !$customer->is_active
        ]);

        return new CustomerResource($customer);
    }

    /**
     * Remove the specified customer from storage (soft delete).
     */
    public function destroy(Customer $customer): JsonResponse
    {
        // Verificar si tiene pedidos asociados
        if ($customer->orders()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar el cliente porque tiene pedidos asociados.',
                'error' => 'El cliente tiene pedidos registrados.'
            ], 409);
        }

        $customer->delete();

        return response()->json(null, 204);
    }
}
