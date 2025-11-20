<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlotRequest;
use App\Http\Requests\UpdatePlotRequest;
use App\Http\Resources\PlotResource;
use App\Models\Plot;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlotController extends Controller
{
    /**
     * GET /api/plots
     * Lista parcelas con paginación y búsqueda opcional.
     */
    public function index(Request $request)
    {
        $query = Plot::query();

        if ($search = $request->query('search')) {
            $query->where('code', 'like', '%' . $search . '%');
        }

        $plots = $query->paginate(15);

        return PlotResource::collection($plots);
    }

    /**
     * POST /api/plots
     * Crea una nueva parcela usando StorePlotRequest.
     */
    public function store(StorePlotRequest $request)
    {
        $data = $request->validated();

        $plot = Plot::create([
            'code'          => $data['name'],              // El nombre llega como "name"
            'area_hectares' => $data['area'],              // Área en hectáreas
            'soil_type'     => $data['soil_type'] ?? null,
            'soil_analysis' => $data['soil_ph'] ?? null,   // ph del suelo
        ]);

        return (new PlotResource($plot))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * GET /api/plots/{plot}
     * Muestra una parcela específica
     */
    public function show(Plot $plot)
    {
        return new PlotResource($plot);
    }

    /**
     * PUT/PATCH /api/plots/{plot}
     * Actualiza una parcela existente
     */
    public function update(UpdatePlotRequest $request, Plot $plot)
    {
        $data = $request->validated();

        $plot->update([
            'code'          => $data['name'] ?? $plot->code,
            'area_hectares' => $data['area'] ?? $plot->area_hectares,
            'soil_type'     => $data['soil_type'] ?? $plot->soil_type,
            'soil_analysis' => $data['soil_ph'] ?? $plot->soil_analysis,
        ]);

        return new PlotResource($plot);
    }

    /**
     * DELETE /api/plots/{plot}
     * Elimina una parcela
     */
    public function destroy(Plot $plot)
    {
        $plot->delete();

        return response()->noContent(); // 204 sin body
    }
}