<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePlantingSeasonRequest;
use App\Http\Requests\Api\UpdatePlantingSeasonRequest;
use App\Http\Resources\PlantingSeasonResource;
use App\Models\PlantingSeason;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlantingSeasonController extends Controller
{
    /**
     * Listar todas las temporadas de siembra con filtros opcionales.
     * 
     * Filtros disponibles:
     * - plot_id: Filtrar por parcela
     * - status: Filtrar por estado (planned, active, harvested, closed)
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = PlantingSeason::with(['plot', 'variety.crop']);

        // Filtro por parcela
        if ($request->has('plot_id')) {
            $query->where('plot_id', $request->plot_id);
        }

        // Filtro por estado
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Ordenar por fecha de siembra descendente (más recientes primero)
        $query->orderBy('planting_date', 'desc');

        $plantingSeasons = $query->paginate(15);

        return PlantingSeasonResource::collection($plantingSeasons);
    }

    /**
     * Crear una nueva temporada de siembra.
     */
    public function store(StorePlantingSeasonRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $plantingSeason = PlantingSeason::create($validated);
        
        $plantingSeason->load(['plot', 'variety.crop']);

        return (new PlantingSeasonResource($plantingSeason))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Mostrar una temporada de siembra específica.
     */
    public function show(PlantingSeason $plantingSeason): PlantingSeasonResource
    {
        $plantingSeason->load(['plot', 'variety.crop', 'harvests']);

        return new PlantingSeasonResource($plantingSeason);
    }

    /**
     * Actualizar una temporada de siembra.
     */
    public function update(UpdatePlantingSeasonRequest $request, PlantingSeason $plantingSeason): PlantingSeasonResource
    {
        $validated = $request->validated();

        $plantingSeason->update($validated);
        
        $plantingSeason->load(['plot', 'variety.crop']);

        return new PlantingSeasonResource($plantingSeason);
    }

    /**
     * Eliminar una temporada de siembra (opcional, no especificado en la HU).
     */
    public function destroy(PlantingSeason $plantingSeason): JsonResponse
    {
        // Verificar si tiene cosechas asociadas
        if ($plantingSeason->harvests()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar la temporada de siembra porque tiene cosechas asociadas.'
            ], 409);
        }

        $plantingSeason->delete();

        return response()->json(null, 204);
    }
}
