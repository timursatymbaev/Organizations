<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganizationRequest;
use App\Models\Organization;
use App\Repositories\OrganizationRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    protected OrganizationRepository $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function index(): View
    {
        return view('organizations.index', [
            'organizations' => $this->organizationRepository->getOrganizations(),
            'followedByOrganizations' => $this->organizationRepository->getFollowedByOrganizations()
        ]);
    }

    public function create(): View
    {
        return view('organizations.create', [
            'organizations' => Organization::all(),
        ]);
    }

    public function store(OrganizationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->organizationRepository->createOrganization($validated);

        return redirect('/organizations');
    }

    public function show(Organization $organization): View
    {
        return view('organizations.view', ['organization' => $organization]);
    }

    public function edit(Organization $organization): View
    {
        return view('organizations.edit', [
            'organization' => $organization,
            'organizations' => Organization::all()
        ]);
    }

    public function update(OrganizationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->organizationRepository->updateOrganization($validated);

        return redirect('/organizations');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->organizationRepository->deleteOrganization($id);

        return redirect('/organizations');
    }
}
