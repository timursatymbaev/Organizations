<?php

namespace App\Repositories;

use App\Models\Committee;
use App\Models\Management;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
     * @return Management|Response|null
     */
    public function getManagementReferencesById(string $committeeId): null|Management|Response
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
     * @param string $committeeName (название комитета)
     * @param string $ministryId (идентификатор министерства для прикрепления)
     * @return null|Response
     */
    public function storeNewCommittee(string $committeeName, string $ministryId): ?Response
    {
        try {
            $userId = Auth::id();

            return DB::transaction(function () use ($committeeName, $ministryId, $userId) {
                $committee = new Committee;
                $committee->committee_name = $committeeName;
                $committee->ministry_id = $ministryId;
                $committee->user_id = $userId;

                $committee->save();

                return true;
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
     * @param string $committeeName (название комитета)
     * @param string|null $managementIdAdd (идентификатор управления, которое добавляется к комитету)
     * @param string|null $managementIdRemove (идентификатор управления, которое открепляется от комитета)
     * @return Response|bool
     */
    public function updateExistingCommittee(string $id, string $committeeName, ?string $managementIdAdd = null, ?string $managementIdRemove = null): bool|Response
    {
        try {
            $userId = Auth::id();

            return DB::transaction(function () use($id, $committeeName, $managementIdAdd, $managementIdRemove, $userId) {
                $committee = Committee::where('id', $id)->where('user_id', $userId)->first();

                if (!$committee) {
                    return new Response('Комитет не существует или не принадлежит текущему пользователю.', 404);
                }

                $committee = Committee::find($id);
                $committee->committee_name = $committeeName;
                $committee->save();

                if ($managementIdAdd !== null) {
                    $this->addManagementToCommittee($managementIdAdd, $committee->id);
                }

                if ($managementIdRemove !== null) {
                    $this->removeManagementFromCommittee($managementIdRemove);
                }

                return true;
            });
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении комитета: ' . $e->getMessage());

            return new Response('Ошибка при обновлении комитета.', 500);
        }
    }

    /**
     * Прикрепление управления к комитету. (вспомогательная функция)
     *
     * @param string $committeeId (идентификатор комитета, к которому будет прикреплено управление)
     * @param string $managementId (идентификатор управления, которое будет прикреплено к комитету)
     * @return bool
     */
    public function addManagementToCommittee(string $managementId, string $committeeId): bool
    {
        return DB::transaction(function () use ($managementId, $committeeId) {
            $management = Management::find($managementId);

            if ($management !== null) {
                $management->committee_id = $committeeId;
                $management->save();
            }

            return true;
        });
    }

    /**
     * Открепление управления от комитета. (вспомогательная функция)
     *
     * @param string $managementId (идентификатор управления, которое будет откреплено от комитета)
     * @return bool
     */
    public function removeManagementFromCommittee(string $managementId): bool
    {
        return DB::transaction(function () use ($managementId) {
            $management = Management::find($managementId);

            if ($management !== null) {
                $management->committee_id = null;
                $management->save();
            }

            return true;
        });
    }
}
