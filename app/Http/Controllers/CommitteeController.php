<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommitteeRequest;
use App\Models\Committee;
use App\Models\Management;
use App\Repositories\CommitteeRepository;
use App\Repositories\ManagementRepository;
use App\Repositories\MinistryRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        $management = Management::where('committee_id', $committee->id)->first();

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
        $managements = Management::with('committee')->get();

        return view('committees.edit', [
            'committee' => $committee,
            'managements' => $managements
        ]);
    }

    /**
     * Обновление выбранного комитета.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'committee_name' => 'required|max:255|string',
            'management_id_assign' => 'required_without:management_id_unassign',
            'management_id_unassign' => 'required_without:management_id_assign'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $committee = Committee::findOrFail($id);
        $committee->committee_name = $request->committee_name;
        $committee->save();

        $managementIdAssign = $request->management_id_assign;
        $managementIdUnassign = $request->management_id_unassign;

        $managementAssign = Management::find($managementIdAssign);
        if ($managementAssign) {
            $managementAssign->committee_id = $committee->id;
            $managementAssign->save();
        }

        $managementUnassign = Management::find($managementIdUnassign);
        if ($managementUnassign) {
            $managementUnassign->committee_id = null;
            $managementUnassign->save();
        }

        return redirect('/organizations');
    }

    /**
     * Удаление выбранного пользователем комитета.
     */
    public function destroy(Committee $committee): RedirectResponse
    {
        $committee->delete();

        return redirect();
    }
}
