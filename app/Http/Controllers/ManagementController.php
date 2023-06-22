<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\Ministry;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use App\Models\Management;

class ManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('managements.create', [
            'ministries' => Ministry::all(),
            'committees' => Committee::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'management_name' => 'required|max:255|string',
            'ministry_id' => 'required',
            'committee_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $management = new Management;
        $management->management_name = $request->management_name;
        $management->ministry_id = $request->ministry_id;
        $management->committee_id = $request->committee_id;
        $management->save();

        return redirect('/organizations');
    }

    /**
     * Display the specified resource.
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
     * Show the form for editing the specified resource.
     */
    public function edit(Management $management): View
    {
        $committees = Committee::with('management')->get();

        return view('managements.edit', [
            'management' => $management,
            'committees' => $committees
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'management_name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $management = Management::findOrFail($id);
        $management->management_name = $request->management_name;
        $management->save();

        return redirect('/organizations');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Management $management): RedirectResponse
    {
        $management->delete();

        return redirect('/organizations');
    }
}
