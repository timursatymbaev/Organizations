<?php

namespace App\Repositories;

use App\Models\Committee;
use App\Models\Ministry;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
     * @param string $ministry_name (название нового министерства)
     * @return bool|Response
     */
    public function storeNewMinistry(string $ministry_name): bool|Response
    {
        try {
            $ministry = new Ministry;
            $ministry->ministry_name = $ministry_name;

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
     * @param string $ministry_name (название министерства)
     * @param string|null $committee_id_add (идентификатор комитета для прикрепления к министерству)
     * @param string|null $committee_id_remove (идентификатор комитета для открепления от министерства)
     * @return bool|Response
     */
    public function updateExistingMinistry(string $id, string $ministry_name, ?string $committee_id_add = null, ?string $committee_id_remove = null): bool|Response
    {
        try {
            return DB::transaction(function () use ($id, $ministry_name, $committee_id_add, $committee_id_remove) {
                $ministry = Ministry::find($id);
                $ministry->ministry_name = $ministry_name;
                $ministry->save();

                if (!empty($committee_id_add)) {
                    $this->addMinistryToCommittee($committee_id_add, $ministry->id);
                }

                if (!empty($committee_id_remove)) {
                    $this->removeMinistryFromCommittee($committee_id_remove);
                }
            });
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении существующего министерства: ' . $e->getMessage());

            return new Response('Ошибка при обновлении существующего министерства', 500);
        }
    }

    /**
     * Добавление комитета к министерству. (вспомогательная функция)
     *
     * @param string $committee_id (идентификатор комитета, который добавляется к министерству)
     * @param string $ministry_id (идентификатор министерства, к которому добавляется комитет)
     * @return bool|Response
     */
    public function addMinistryToCommittee(string $committee_id, string $ministry_id): bool|Response
    {
        try {
            return DB::transaction(function () use ($committee_id, $ministry_id) {
                $committee = Committee::find($committee_id);

                if ($committee !== null) {
                    $committee->ministry_id = $ministry_id;
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
     * @param string $committee_id (идентификатор комитета, который открепляется от министерства)
     * @return bool|Response
     */
    public function removeMinistryFromCommittee(string $committee_id): bool|Response
    {
        try {
            return DB::transaction(function () use ($committee_id) {
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
            });
        } catch (\Exception $e) {
            Log::error('Ошибка при откреплении министерства от комитета: ' . $e->getMessage());

            return new Response('Ошибка при откреплении министерства от комитета.', 500);
        }
    }
}
