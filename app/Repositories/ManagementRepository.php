<?php

namespace App\Repositories;

use App\Models\Management;
use Illuminate\Support\Collection;

class ManagementRepository
{
    /**
     * Получает все отношения с комитетами.
     *
     * @return Collection
     */
    public function getCommitteeReferences(): Collection
    {
        return Management::with('committee')->get();
    }

    /**
     * Поиск управлений по названию.
     *
     * @param string $name (название для поиска)
     * @return Collection
     */
    public function searchManagementsByName(string $name): Collection
    {
        return Management::where('management_name', 'like', '%' . $name . '%')->get();
    }
}
