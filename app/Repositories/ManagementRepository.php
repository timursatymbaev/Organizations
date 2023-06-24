<?php

namespace App\Repositories;

use App\Models\Management;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
     * @param string $managementName (название управления)
     * @param string $ministryId (идентификатор министерства, которое курирует управление)
     * @param string $committeeId (идентификатор комитета, который курирует управление)
     * @return bool|Response
     */
    public function storeNewManagement(string $managementName, string $ministryId, string $committeeId): bool|Response
    {
        try {
            $userId = Auth::id();

            $management = new Management;

            $management->management_name = $managementName;
            $management->ministry_id = $ministryId;
            $management->committee_id = $committeeId;
            $management->user_id = $userId;

            return $management->save();
        } catch (\Exception $e) {
            Log::error('Ошибка при создании нового управления: ' . $e->getMessage());

            return new Response('Ошибка при создании нового управления.', 500);
        }
    }

    /**
     * Обновление существующего управления.
     *
     * @param string $managementId (идентификатор управления)
     * @param string $managementName (новое название управления)
     * @return bool|Response
     */
    public function updateExistingManagement(string $managementId, string $managementName): bool|Response
    {
        try {
            $userId = Auth::id();

            $management = Management::where('id', $managementId)->where('user_id', $userId)->first();

            if (!$management) {
                return new Response('Управление не существует или не принадлежит текущему пользователю.', 404);
            }

            $management = Management::find($managementId);
            $management->management_name = $managementName;

            return $management->save();
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении существующего управления: ' . $e->getMessage());

            return new Response('Ошибка при обновлении существующего управления.', 500);
        }
    }
}
