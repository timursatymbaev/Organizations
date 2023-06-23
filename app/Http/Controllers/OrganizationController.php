<?php

namespace App\Http\Controllers;

use App\Repositories\ManagementRepository;
use App\Repositories\CommitteeRepository;
use App\Repositories\MinistryRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationController extends Controller
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
     * Отображение всех организаций.
     *
     * @return View
     */
    public function index(): View
    {
        $ministries = $this->ministryRepository->getAllMinistries();
        $committees = $this->committeeRepository->getMinistryReferences();
        $managements = $this->managementRepository->getCommitteeReferences();

        return view('organizations.index', [
            'ministries' => $ministries,
            'committees' => $committees,
            'managements' => $managements,
        ]);
    }

    /**
     * Отображение страницы с найденными организациями.
     *
     * @param Request $request (параметр для поиска организаций)
     * @return View
     */
    public function search(Request $request): View
    {
        $search = trim($request->input('search'));

        $ministries = $this->ministryRepository->searchMinistriesByName($search);
        $committees = $this->committeeRepository->searchCommitteesByName($search);
        $managements = $this->managementRepository->searchManagementsByName($search);

        return view('organizations.search', [
            'ministries' => $ministries,
            'committees' => $committees,
            'managements' => $managements
        ]);
    }
}
