<?php

namespace App\Http\Controllers;

use App\Models\AlumniRegistration;
use App\Models\District;
use App\Models\Division;
use App\Models\Country;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $this->search_filter($request);
        $data['divisions'] = Division::with('country')->where($search)->orderBy('id', 'DESC')->paginate(10);
        $data['countries'] = Country::orderBy('name', 'ASC')->get();
        $data['request'] = $request;
        return view('backend.division', $data);
    }

    public function search_filter($request)
    {
        $search = [];
        if ($request->name){
            $search[] = ['name', 'like', '%' . $request->name . '%'];
        }
        if ($request->bn_name){
            $search[] = ['bn_name', 'like', '%' . $request->bn_name . '%'];
        }
        if ($request->country_id){
            $search[] = ['country_id', $request->country_id];
        }
        if ($request->status !== null){
            $search[] = ['status', $request->status];
        }
        return $search;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->storeValidator($request);

        try {
            $division = new Division();
            $division->country_id = $request->country_id;
            $division->name = $request->name;
            $division->bn_name = $request->bn_name;
            $division->status = $request->status ?? 1;
            $division->save();

            return back()->withSuccess('Division Added Successfully!');

        } catch (\Exception $e) {
            return back()->withError('Error: ' . $e->getMessage());
        }
    }

    public function storeValidator($request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255|unique:divisions,name',
            'bn_name' => 'required|string|max:255|unique:divisions,bn_name',
        ], [
            'name.unique' => 'This division name already exists.',
            'bn_name.unique' => 'This Bangla division name already exists.',
            'country_id.required' => 'Please select a country.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Division $division)
    {
        $this->updateValidator($request, $division);

        try {
            $division->country_id = $request->country_id;
            $division->name = $request->name;
            $division->bn_name = $request->bn_name;
            $division->status = $request->status ?? 1;
            $division->save();

            return back()->withSuccess('Division Updated Successfully!');

        } catch (\Exception $e) {
            return back()->withError('Error: ' . $e->getMessage());
        }
    }

    public function updateValidator($request, $division)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
            'bn_name' => 'required|string|max:255|unique:divisions,bn_name,' . $division->id,
        ], [
            'name.unique' => 'This division name already exists.',
            'bn_name.unique' => 'This Bangla division name already exists.',
            'country_id.required' => 'Please select a country.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division)
    {
        try {
            if (District::where('division_id', $division->id)->exists()) {
                return back()->withErrors('Error: Cannot delete division with associated districts.');
            }
            if (
                AlumniRegistration::where('division', $division->id)->exists()
                || AlumniRegistration::where('permanent_division', $division->id)->exists()
                || AlumniRegistration::where('current_division', $division->id)->exists()
            ) {
                return back()->withError('Error: Cannot delete this thana as it is associated with alumni registrations.');
            }
            $division->delete();
            return back()->withSuccess('Division Deleted Successfully!');
        } catch (\Exception $e) {
            return back()->withError('Error: ' . $e->getMessage());
        }
    }

    /**
     * Status Change
     */
    public function table_status_change(Request $request)
    {
        try {
            $division = Division::findOrFail($request->id);
            $division->status = $request->status;
            $division->save();

            return back()->withSuccess('Status Changed Successfully');
        } catch (\Exception $e) {
            return back()->withError('Error: ' . $e->getMessage());
        }
    }
}
