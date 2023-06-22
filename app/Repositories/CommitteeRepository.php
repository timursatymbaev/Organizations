<?php

namespace App\Repositories;

use App\Models\Committee;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CommitteeRepository
{
    /**
     * Получает все отношения с министерствами.
     *
     * @return Collection
     */
    public function getMinistryReferences(): Collection
    {
        return Committee::with('ministry')->get();
    }

    /**
     * Поиск комитетов по названию.
     *
     * @param string $name (название для поиска)
     * @return Collection
     */
    public function searchCommitteesByName(string $name): Collection
    {
        return Committee::where('committee_name', 'like', '%' . $name . '%')->get();
    }

    /**
     * Создание нового комитета.
     *
     * @param string $committee_name (название комитета)
     * @param int $ministry_id (идентификатор министерства для прикрепления)
     * @return bool|JsonResponse
     */
    public function storeNewCommittee(string $committee_name, int $ministry_id): bool|JsonResponse
    {
        try {
            $committee = new Committee;
            $committee->committee_name = $committee_name;
            $committee->ministry_id = $ministry_id;

            return $committee->save();
        } catch (\Exception $e) {
            Log::error('Ошибка при создании нового комитета: ' . $e->getMessage());

            return response()->json(['error' => 'Ошибка при создании нового комитета.'], 500);
        }
    }
}
