<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreHarvestRequest;
use App\Http\Requests\Api\UpdateHarvestRequest;
use App\Http\Resources\HarvestResource;
use App\Models\Harvest;
use App\Models\PlantingSeason;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HarvestController extends Controller
{
    public function index(Request $request)
    {
        $query = Harvest::with('plantingSeason.plot');

        if ($request->filled('planting_season_id')) {
            $query->where('planting_season_id', $request->planting_season_id);
        }

        if ($request->filled('quality')) {
            $query->where('quality', $request->quality);
        }

        $harvests = $query->paginate(10);

        $totalsByUnit = (clone $query)
            ->selectRaw('unit, SUM(quantity) as total_quantity')
            ->groupBy('unit')
            ->pluck('total_quantity', 'unit');

        return HarvestResource::collection($harvests)
            ->additional([
                'meta' => [
                    'totals_by_unit' => $totalsByUnit
                ]
            ]);
    }

    public function store(StoreHarvestRequest $request)
    {
        $data = $request->validated();

        $plantingSeason = PlantingSeason::with('plot')->findOrFail($data['planting_season_id']);
        $plot = $plantingSeason->plot;

        if ($plantingSeason->planting_date > $data['harvest_date']) {
            return response()->json([
                'message' => 'La fecha de cosecha no puede ser menor a la fecha de siembra.'
            ], 422);
        }

        $data['yield_per_hectare'] = $plot->area_hectares > 0
            ? $data['quantity'] / $plot->area_hectares
            : null;

        $harvest = Harvest::create($data);
        $harvest->load('plantingSeason.plot');

        return (new HarvestResource($harvest))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Harvest $harvest)
    {
        $harvest->load('plantingSeason.plot');
        return new HarvestResource($harvest);
    }

    public function update(UpdateHarvestRequest $request, Harvest $harvest)
    {
        $data = $request->validated();

        $plantingSeason = isset($data['planting_season_id'])
            ? PlantingSeason::with('plot')->findOrFail($data['planting_season_id'])
            : $harvest->plantingSeason->load('plot');

        $plot = $plantingSeason->plot;

        $harvestDate = $data['harvest_date'] ?? $harvest->harvest_date;

        if ($plantingSeason->planting_date > $harvestDate) {
            return response()->json([
                'message' => 'La fecha de cosecha no puede ser menor a la fecha de siembra.'
            ], 422);
        }

        $finalQuantity = $data['quantity'] ?? $harvest->quantity;

        $data['yield_per_hectare'] = $plot->area_hectares > 0
            ? $finalQuantity / $plot->area_hectares
            : null;

        $harvest->update($data);
        $harvest->load('plantingSeason.plot');

        return new HarvestResource($harvest);
    }

    public function destroy(Harvest $harvest)
    {
        $harvest->delete();
        return response()->noContent();
    }
}
