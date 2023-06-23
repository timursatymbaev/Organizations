<?php

namespace App\Repositories;

use App\Models\Management;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ManagementRepository
{
    /**
     * Получает все отношения с комитетами.
     *
     * @return Collection|Response
     */
    public function getCommitteeReferences(): Collection|Response
    {
        try {
            return Management::with('committee')->get();
        } catch (\Exception $e) {
            Log::error('Ошибка при получении отношений с комитетами: ' . $e->getMessage());

            return new Response('Ошибка при получении отношений с комитетами.', 500);
        }
    }

    /**
     * Поиск управлений по названию.
     *
     * @param string $name (название для поиска)
     * @return Collection|Response
     */
    public function searchManagementsByName(string $name): Collection|Response
    {
        try {
            return Management::where('management_name', 'like', '%' . $name . '%')->get();
        } catch (\Exception $e) {
            Log::error('Ошибка при получении данных для поиска управлений: ' . $e->getMessage());

            return new Response('Ошибка при получении данных для поиска управлений.', 500);
        }
    }

    /**
     * Сохранение нового управления.
     *
     * @param string $management_name (название управления)
     * @param string $ministry_id (идентификатор министерства, которое курирует управление)
     * @param string $committee_id (идентификатор комитета, который курирует управление)
     * @return bool|Response
     */
    public function storeNewManagement(string $management_name, string $ministry_id, string $committee_id): bool|Response
    {
        try {
            $management = new Management;

            $management->management_name = $management_name;
            $management->ministry_id = $ministry_id;
            $management->committee_id = $committee_id;

            return $management->save();
        } catch (\Exception $e) {
            Log::error('Ошибка при создании нового управления: ' . $e->getMessage());

            return new Response('Ошибка при создании нового управления.', 500);
        }
    }

    /**
     * Обновление существующего управления.
     *
     * @param string $management_id (идентификатор управления)
     * @param string $management_name (новое название управления)
     * @return bool|Response
     */
    public function updateExistingManagement(string $management_id, string $management_name): bool|Response
    {
        try {
            $management = Management::find($management_id);
            $management->management_name = $management_name;

            return $management->save();
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении существующего управления: ' . $e->getMessage());

            return new Response('Ошибка при обновлении существующего управления.', 500);
        }
    }
}
