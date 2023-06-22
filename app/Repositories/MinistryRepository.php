<?php

namespace App\Repositories;

use App\Models\Committee;
use App\Models\Ministry;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MinistryRepository
{
    /**
     * Получает все министерства из базы данных.
     *
     * @return Collection|JsonResponse
     */
    public function getAllMinistries(): Collection|JsonResponse
    {
        try {
            return Ministry::all();
        } catch (\Exception $e) {
            Log::error('Ошибка при получении министерств: ' . $e->getMessage());

            return response()->json(['error' => 'Ошибка при получении министерств.'], 500);
        }
    }

    /**
     * Сохраняет созданное министерство в таблицу базы данных.
     *
     * @param string $ministry_name (название нового министерства)
     * @return bool|JsonResponse
     */
    public function storeNewMinistry(string $ministry_name): bool|JsonResponse
    {
        try {
            $ministry = new Ministry;
            $ministry->ministry_name = $ministry_name;

            return $ministry->save();
        } catch (\Exception $e) {
            Log::error('Ошибка при создании нового министерства: ' . $e->getMessage());

            return response()->json(['error' => 'Ошибка при создании нового министерства.'], 500);
        }
    }

    /**
     * Обновление существующего министерства.
     *
     * @param string $id (идентификатор министерства)
     * @param string $ministry_name (название министерства)
     * @param int|null $committee_id_add (идентификатор комитета для прикрепления к министерству)
     * @param int|null $committee_id_remove (идентификатор комитета для открепления от министерства)
     * @return bool|JsonResponse
     */
    public function updateExistingMinistry(string $id, string $ministry_name, ?int $committee_id_add = null, ?int $committee_id_remove = null): bool|JsonResponse
    {
        try {
            $ministry = Ministry::findOrFail($id);
            $ministry->ministry_name = $ministry_name;
            $ministry->save();

            if ($committee_id_add !== null) {
                $this->addMinistryToCommittee($committee_id_add, $ministry->id);
            }

            if ($committee_id_remove !== null) {
                $this->removeMinistryFromCommittee($committee_id_remove);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении существующего министерства: ' . $e->getMessage());

            return response()->json(['error' => 'Ошибка при обновлении существующего министерства'], 500);
        }
    }

    /**
     * Добавление комитета к министерству.
     *
     * @param int $committee_id (идентификатор комитета, который добавляется к министерству)
     * @param int $ministry_id (идентификатор министерства, к которому добавляется комитет)
     * @return null|JsonResponse
     */
    public function addMinistryToCommittee(int $committee_id, int $ministry_id): null|JsonResponse
    {
        try {
            $committee = Committee::find($committee_id);

            if ($committee !== null) {
                $committee->ministry_id = $ministry_id;
                $committee->save();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Ошибка при добавлении комитета к министерству: ' . $e->getMessage());

            return response()->json(['error' => 'Ошибка при добавлении комитета к министерству.'], 500);
        }
    }

    /**
     * Открепление комитета от министерства.
     *
     * @param int $committee_id (идентификатор комитета, который открепляется от министерства)
     * @return null|JsonResponse
     */
    public function removeMinistryFromCommittee(int $committee_id): null|JsonResponse
    {
        try {
            $committee = Committee::find($committee_id);

            if ($committee !== null) {
                $committee->ministry_id = null;
                $managements = $committee->management;

                foreach ($managements as $management) {
                    $management->committee_id = null;
                    $management->ministry_id = null;

                    $management->save();
                }

                $committee->save();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Ошибка при откреплении комитета от министерства: ' . $e->getMessage());

            return response()->json(['error' => 'Ошибка при откреплении комитета от министерства.'], 500);
        }
    }

    /**
     * Поиск министерств по названию.
     *
     * @param string $name (название для поиска)
     * @return Collection|JsonResponse
     */
    public function searchMinistriesByName(string $name): Collection|JsonResponse
    {
        try {
            return Ministry::where('ministry_name', 'like', '%' . $name . '%')->get();
        } catch (\Exception $e) {
            Log::error('Ошибка при поиске министерств по названию: ' . $e->getMessage());

            return response()->json(['error' => 'Ошибка при поиске министерств по названию.'], 500);
        }
    }

    /**
     * Удаление министерства из таблицы базы данных.
     *
     * @param Ministry $ministry (выбранное министерство)
     * @return bool
     */
    public function deleteMinistry(Ministry $ministry): bool
    {
        return $ministry->delete();
    }
}
