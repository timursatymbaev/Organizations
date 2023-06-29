<?php

namespace App\Repositories;

use App\Models\Organization;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizationRepository
{
    public function searchOrganization(?string $search, ?string $filter): Collection
    {
        $query = Organization::query();

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if (!empty($filter)) {
            match ($filter) {
                'Министерство' => $query->where('type', 'Министерство'),
                'Комитет' => $query->where('type', 'Комитет'),
                'Управление' => $query->where('type', 'Управление'),
                default => Organization::all()
            };
        }

        return $query->get();
    }

    public function getOrganizations(): Collection
    {
        return Organization::all();
    }

    public function getFollowedByOrganizations(): Collection
    {
        return Organization::with('followedBy')->get();
    }

    public function createOrganization(array $data): Organization
    {
        return DB::transaction(function () use ($data) {
            $type = $data['type'];

            return $this->createOrganizationByType($type, $data);
        });
    }

    private function createOrganizationByType(string $type, array $data): Organization
    {
        return match ($type) {
            'Министерство' => $this->createMinistry($data),
            'Комитет' => $this->createCommittee($data),
            'Управление' => $this->createManagement($data),
            default => null,
        };
    }

    private function createMinistry(array $data): Organization
    {
        return Organization::create([
            'name' => $data['name'],
            'type' => 'Министерство',
            'created_by' => Auth::id(),
        ]);
    }

    private function createCommittee(array $data): Organization
    {
        return Organization::create([
            'name' => $data['name'],
            'type' => 'Комитет',
            'created_by' => Auth::id(),
            'followed_by' => $data['followed_by']
        ]);
    }

    private function createManagement(array $data): Organization
    {
        return Organization::create([
            'name' => $data['name'],
            'type' => 'Управление',
            'created_by' => Auth::id(),
            'followed_by' => $data['followed_by'],
            'followed_by_committee' => $data['followed_by_committee']
        ]);
    }

    public function updateOrganization(array $data): Organization
    {
        return DB::transaction(function () use ($data) {
            $type = $data['type'];

            return $this->updateOrganizationByType($type, $data);
        });
    }

    private function updateOrganizationByType(string $type, array $data): Organization
    {
        return match ($type) {
            'Министерство' => $this->updateMinistry($data),
            'Комитет' => $this->updateCommittee($data),
            'Управление' => $this->updateManagement($data),
            default => null
        };
    }

    private function updateMinistry(array $data): Organization
    {
        $organization = Organization::findOrFail($data['id']);

        $organization->name = $data['name'];
        $organization->save();

        return $organization;
    }

    private function updateCommittee(array $data): Organization
    {
        $organization = Organization::findOrFail($data['id']);

        $organization->name = $data['name'];
        $organization->followed_by = $data['followed_by_add'] ?? null;
        $organization->save();

        return $organization;
    }

    private function updateManagement(array $data): Organization
    {
        $organization = Organization::findOrFail($data['id']);

        $organization->name = $data['name'];
        $organization->followed_by_committee = $data['followed_by_committee_add'] ?? null;
        $organization->save();

        return $organization;
    }

    public function deleteOrganization(string $id): void
    {
        $organization = Organization::findOrFail($id);

        match ($organization->type) {
            'Министерство', 'Управление', 'Комитет' => $organization->delete($id)
        };
    }
}
