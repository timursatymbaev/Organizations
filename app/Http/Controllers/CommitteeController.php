<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Repositories\CommitteeRepository;
use App\Repositories\ManagementRepository;
use App\Repositories\MinistryRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\CommitteeRequest;

class CommitteeController extends Controller
{
    protected MinistryRepository $ministryRepository;
    protected CommitteeRepository $committeeRepository;
    protected ManagementRepository $managementRepository;

    public function __construct
    (
        MinistryRepository $ministryRepository,
        CommitteeRepository $committeeRepository,
        ManagementRepository $managementRepository
    )
    {
        $this->ministryRepository = $ministryRepository;
        $this->committeeRepository = $committeeRepository;
        $this->managementRepository = $managementRepository;
    }

    /**
     * Отображение страницы для создания нового комитета.
     */
    public function create(): View
    {
        $ministries = $this->ministryRepository->getAllMinistries();

        return view('committees.create', ['ministries' => $ministries]);
    }

    /**
     * Сохранение полученных данных в таблицу с комитетами.
     */
    public function store(CommitteeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->committeeRepository->storeNewCommittee($validated['committee_name'], $validated['ministry_id']);

        return redirect('/organizations');
    }

    /**
     * Отображение выбранного пользователем комитета.
     */
    public function show(Committee $committee): View
    {
        $ministry = $committee->ministry;
        $management = $this->committeeRepository->getManagementReferencesById($committee->id);

        return view('committees.show', [
            'committee' => $committee,
            'ministry' => $ministry,
            'management' => $management
        ]);
    }

    /**
     * Отображение страницы для редактирования выбранного комитета.
     */
    public function edit(Committee $committee): View
    {
        $managements = $this->committeeRepository->getManagementReferences();

        return view('committees.edit', [
            'committee' => $committee,
            'managements' => $managements
        ]);
    }

    /**
     * Обновление выбранного комитета.
     */
    public function update(CommitteeRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();

        $this->committeeRepository->updateExistingCommittee($id, $validated['committee_name'], $validated['management_id_add'], $validated['management_id_remove']);

        return redirect('/organizations');
    }

    /**
     * Удаление выбранного пользователем комитета.
     */
    public function destroy(Committee $committee): RedirectResponse
    {
        $committee->delete();

        return redirect('/organizations');
    }
}
