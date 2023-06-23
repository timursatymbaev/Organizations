<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManagementRequest;
use App\Models\Management;
use App\Repositories\CommitteeRepository;
use App\Repositories\ManagementRepository;
use App\Repositories\MinistryRepository;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ManagementController extends Controller
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
     * Отображение страницы для создания нового управления.
     */
    public function create(): View
    {
        $ministries = $this->ministryRepository->getAllMinistries();
        $committees = $this->committeeRepository->getAllCommittees();

        return view('managements.create', [
            'ministries' => $ministries,
            'committees' => $committees,
        ]);
    }

    /**
     * Сохранение полученных данных в таблицу с управлениями.
     */
    public function store(ManagementRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->managementRepository->storeNewManagement($validated['management_name'], $validated['ministry_id'], $validated['committee_id']);

        return redirect('/organizations');
    }

    /**
     * Отображение выбранного пользователем управления.
     */
    public function show(Management $management): View
    {
        $ministry = $management->ministry;
        $committee = $management->committee;

        return view('managements.show', [
            'management' => $management,
            'ministry' => $ministry,
            'committee' => $committee
        ]);
    }

    /**
     * Отображение страницы для редактирования выбранного управления.
     */
    public function edit(Management $management): View
    {
        $committees = $this->committeeRepository->getManagementReferences();

        return view('managements.edit', [
            'management' => $management,
            'committees' => $committees
        ]);
    }

    /**
     * Обновление выбранного управления.
     */
    public function update(ManagementRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();

        $this->managementRepository->updateExistingManagement($id, $validated['management_name']);

        return redirect('/organizations');
    }

    /**
     * Удаление выбранного пользователем управления.
     */
    public function destroy(Management $management): RedirectResponse
    {
        $management->delete();

        return redirect('/organizations');
    }
}
