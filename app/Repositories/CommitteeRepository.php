<?php

namespace App\Repositories;

use App\Models\Committee;
use App\Models\Management;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CommitteeRepository
{
    /**
     * Получает все комитеты.
     *
     * @return Collection|Response
     */
    public function getAllCommittees(): Collection|Response
    {
        try {
            return Committee::all();
        } catch (\Exception $e) {
            Log::error('Ошибка при получении министерств: ' . $e->getMessage());

            return new Response('Ошибка при получении министерств.', 500);
        }
    }

    /**
     * Получает все отношения с министерствами.
     *
     * @return Collection|Response
     */
    public function getMinistryReferences(): Collection|Response
    {
        try {
            return Committee::with('ministry')->get();
        } catch (\Exception $e) {
            Log::error('Ошибка при получении отношений с министерствами: ' . $e->getMessage());

            return new Response('Ошибка при получении отношений с министерствами.', 500);
        }
    }

    /**
     * Получает отношения комитетов, которые курируют управления.
     *
     * @param string $committeeId (идентификатор комитета, который курирует управление)
     * @return Management|Response
     */
    public function getManagementReferencesById(string $committeeId): Management|Response
    {
        try {
            return Management::where('committee_id', $committeeId)->first();
        } catch (\Exception $e) {
            Log::error('Ошибка при получении отношений с управлениями по идентификатору: ' . $e->getMessage());

            return new Response('Ошибка при получении отношений с управлениями по идентификатору.', 500);
        }
    }

    /**
     * Получает отношения комитетов с управлениями.
     *
     * @return Collection|Response
     */
    public function getManagementReferences(): Collection|Response
    {
        try {
            return Management::with('committee')->get();
        } catch (\Exception $e) {
            Log::error('Ошибка при получении отношений с управлениями: ' . $e->getMessage());

            return new Response('Ошибка при получении отношений с управлениями.', 500);
        }
    }

    /**
     * Поиск комитетов по названию.
     *
     * @param string $name (название для поиска)
     * @return Collection|Response
     */
    public function searchCommitteesByName(string $name): Collection|Response
    {
        try {
            return Committee::where('committee_name', 'like', '%' . $name . '%')->get();
        } catch (\Exception $e) {
            Log::error('Ошибка при получении данных для поиска комитетов: ' . $e->getMessage());

            return new Response('Ошибка при получении данных для поиска комитетов.', 500);
        }
    }

    /**
     * Создание нового комитета.
     *
     * @param string $committee_name (название комитета)
     * @param string $ministry_id (идентификатор министерства для прикрепления)
     * @return bool|Response
     */
    public function storeNewCommittee(string $committee_name, string $ministry_id): bool|Response
    {
        try {
            return DB::transaction(function () use ($committee_name, $ministry_id) {
                $committee = new Committee;
                $committee->committee_name = $committee_name;
                $committee->ministry_id = $ministry_id;

                $committee->save();
            });
        } catch (\Exception $e) {
            Log::error('Ошибка при создании нового комитета: ' . $e->getMessage());

            return new Response('Ошибка при создании нового комитета.', 500);
        }
    }

    /**
     * Обновление существующего комитета.
     *
     * @param string $id (идентификатор комитета)
     * @param string $committee_name (название комитета)
     * @param string|null $management_id_add (идентификатор управления, которое добавляется к комитету)
     * @param string|null $management_id_remove (идентификатор управления, которое открепляется от комитета)
     * @return Response|bool
     */
    public function updateExistingCommittee(string $id, string $committee_name, ?string $management_id_add = null, ?string $management_id_remove = null): bool|Response
    {
        try {
            return DB::transaction(function () use($id, $committee_name, $management_id_add, $management_id_remove) {
                $committee = Committee::find($id);
                $committee->committee_name = $committee_name;
                $committee->save();

                if ($management_id_add !== null) {
                    $this->addManagementToCommittee($management_id_add, $committee->id);
                }

                if ($management_id_remove !== null) {
                    $this->removeManagementFromCommittee($management_id_remove);
                }
            });
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении комитета: ' . $e->getMessage());

            return new Response('Ошибка при обновлении комитета.', 500);
        }
    }

    /**
     * Прикрепление управления к комитету. (вспомогательная функция)
     *
     * @param string $committee_id (идентификатор комитета, к которому будет прикреплено управление)
     * @param string $management_id (идентификатор управления, которое будет прикреплено к комитету)
     * @return bool
     */
    public function addManagementToCommittee(string $management_id, string $committee_id): bool
    {
        return DB::transaction(function () use ($management_id, $committee_id) {
            $management = Management::find($management_id);

            if ($management !== null) {
                $management->committee_id = $committee_id;
                $management->save();
            }
        });
    }

    /**
     * Открепление управления от комитета. (вспомогательная функция)
     *
     * @param string $management_id (идентификатор управления, которое будет откреплено от комитета)
     * @return bool
     */
    public function removeManagementFromCommittee(string $management_id): bool
    {
        return DB::transaction(function () use($management_id) {
            $management = Management::find($management_id);

            if ($management !== null) {
                $management->committee_id = null;
                $management->save();
            }
        });
    }
}
