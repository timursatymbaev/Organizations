<?php

namespace App\Repositories;

use App\Models\Committee;
use App\Models\Ministry;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MinistryRepository
{
    /**
     * Получает все министерства из базы данных.
     *
     * @return Collection|Response
     */
    public function getAllMinistries(): Collection|Response
    {
        try {
            return Ministry::all();
        } catch (\Exception $e) {
            Log::error('Ошибка при получении министерств: ' . $e->getMessage());

            return new Response('Ошибка при получении министерств.', 500);
        }
    }

    /**
     * Поиск министерств по названию.
     *
     * @param string $name (название для поиска)
     * @return Collection|Response
     */
    public function searchMinistriesByName(string $name): Collection|Response
    {
        try {
            return Ministry::where('ministry_name', 'like', '%' . $name . '%')->get();
        } catch (\Exception $e) {
            Log::error('Ошибка при поиске министерств по названию: ' . $e->getMessage());

            return new Response('Ошибка при поиске министерств по названию.', 500);
        }
    }

    /**
     * Сохраняет созданное министерство в таблицу базы данных.
     *
     * @param string $ministryName (название нового министерства)
     * @return bool|Response
     */
    public function storeNewMinistry(string $ministryName): bool|Response
    {
        try {
            $userId = Auth::id();

            $ministry = new Ministry;
            $ministry->ministry_name = $ministryName;
            $ministry->user_id = $userId;

            return $ministry->save();
        } catch (\Exception $e) {
            Log::error('Ошибка при создании нового министерства: ' . $e->getMessage());

            return new Response('Ошибка при создании нового министерства.', 500);
        }
    }

    /**
     * Обновление существующего министерства.
     *
     * @param string $id (идентификатор министерства)
     * @param string $ministryName (название министерства)
     * @param string|null $committeeIdAdd (идентификатор комитета для прикрепления к министерству)
     * @param string|null $committeeIdRemove (идентификатор комитета для открепления от министерства)
     * @return bool|Response
     */
    public function updateExistingMinistry(string $id, string $ministryName, ?string $committeeIdAdd = null, ?string $committeeIdRemove = null): bool|Response
    {
        try {
            $userId = Auth::id();

            return DB::transaction(function () use ($id, $ministryName, $committeeIdAdd, $committeeIdRemove, $userId) {
                $ministry = Ministry::where('id', $id)->where('user_id', $userId)->first();

                if (!$ministry) {
                    return new Response('Министерство не найдено или не принадлежит текущему пользователю.', 404);
                }

                $ministry = Ministry::find($id);
                $ministry->ministry_name = $ministryName;
                $ministry->save();

                if (!empty($committee_id_add)) {
                    $this->addMinistryToCommittee($committeeIdAdd, $ministry->id);
                }

                if (!empty($committee_id_remove)) {
                    $this->removeMinistryFromCommittee($committeeIdRemove);
                }

                return true;
            });
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении существующего министерства: ' . $e->getMessage());

            return new Response('Ошибка при обновлении существующего министерства', 500);
        }
    }

    /**
     * Добавление комитета к министерству. (вспомогательная функция)
     *
     * @param string $committeeId (идентификатор комитета, который добавляется к министерству)
     * @param string $ministryId (идентификатор министерства, к которому добавляется комитет)
     * @return bool|Response
     */
    public function addMinistryToCommittee(string $committeeId, string $ministryId): bool|Response
    {
        try {
            return DB::transaction(function () use ($committeeId, $ministryId) {
                $committee = Committee::find($committeeId);

                if ($committee !== null) {
                    $committee->ministry_id = $ministryId;
                    $committee->save();
                }
            });
        } catch (\Exception $e) {
            Log::error('Ошибка при добавлении министерства к комитету: ' . $e->getMessage());

            return new Response('Ошибка при добавлении министерства к комитету.', 500);
        }
    }

    /**
     * Открепление комитета от министерства. (вспомогательная функция)
     *
     * @param string $committeeId (идентификатор комитета, который открепляется от министерства)
     * @return bool|Response
     */
    public function removeMinistryFromCommittee(string $committeeId): bool|Response
    {
        try {
            return DB::transaction(function () use ($committeeId) {
                $committee = Committee::find($committeeId);

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
            });
        } catch (\Exception $e) {
            Log::error('Ошибка при откреплении министерства от комитета: ' . $e->getMessage());

            return new Response('Ошибка при откреплении министерства от комитета.', 500);
        }
    }
}
