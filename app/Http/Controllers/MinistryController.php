<?php

namespace App\Http\Controllers;

use App\Models\Ministry;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Repositories\MinistryRepository;
use App\Repositories\CommitteeRepository;
use App\Http\Requests\MinistryRequest;

class MinistryController extends Controller
{
    protected MinistryRepository $ministryRepository;
    protected CommitteeRepository $committeeRepository;

    public function __construct
    (
        MinistryRepository $ministryRepository,
        CommitteeRepository $committeeRepository
    )
    {
        $this->ministryRepository = $ministryRepository;
        $this->committeeRepository = $committeeRepository;
    }

    /**
     * Отображение страницы для создания нового министерства.
     */
    public function create(): View
    {
        return view('ministries.create');
    }

    /**
     * Сохранение полученных данных в таблицу с министерствами.
     */
    public function store(MinistryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->ministryRepository->storeNewMinistry($validated['ministry_name']);

        return redirect('/organizations');
    }

    /**
     * Отображение выбранного пользователем министерства.
     */
    public function show(Ministry $ministry): View
    {
        return view('ministries.show', ['ministry' => $ministry]);
    }

    /**
     * Отображение страницы для редактирования выбранного министерства.
     */
    public function edit(Ministry $ministry): View
    {
        $committees = $this->committeeRepository->getMinistryReferences();

        return view('ministries.edit', [
            'ministry' => $ministry,
            'committees' => $committees
        ]);
    }

    /**
     * Обновление выбранного министерства.
     */
    public function update(MinistryRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();

        $committee_id_add = $validated['committee_id_add'] ? intval($validated['committee_id_add']) : null;
        $committee_id_remove = $validated['committee_id_remove'] ? intval($validated['committee_id_remove']) : null;

        $this->ministryRepository->updateExistingMinistry($id, $validated['ministry_name'], $committee_id_add, $committee_id_remove);

        return redirect('/organizations');
    }

    /**
     * Удаление выбранного пользователем министерства.
     */
    public function destroy(Ministry $ministry): RedirectResponse
    {
        $this->ministryRepository->deleteMinistry($ministry);

        return redirect('/organizations');
    }
}
