<?php

namespace App\Http\Controllers;

use App\Models\AlumniRegistration;
use App\Models\District;
use App\Models\Division;
use App\Models\Thana;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $this->search_filter($request);
        $data['districts'] = District::with('division')->where($search)->orderBy('id', 'DESC')->paginate(10);
        $data['divisions'] = Division::where('status', 1)->orderBy('name', 'ASC')->get();
        $data['request'] = $request;
        return view('backend.district', $data);
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
        if ($request->division_id){
            $search[] = ['division_id', $request->division_id];
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
            $district = new District();
            $this->dataInsert($request, $district);
            return back()->withSuccess('District Added Successfully!');

        } catch (\Exception $e) {
            return back()->withError('Error: ' . $e->getMessage());
        }
    }

    public function storeValidator($request)
    {
        $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'name' => 'required|string|max:255|unique:districts,name',
            'bn_name' => 'nullable|string|max:255|unique:districts,bn_name',
            'lat' => 'nullable|string|max:50',
            'lon' => 'nullable|string|max:50',
            'url' => 'nullable|max:255',
            'code' => 'nullable|string|max:20',
        ], [
            'name.unique' => 'This district name already exists.',
            'bn_name.unique' => 'This Bangla district name already exists.',
            'division_id.required' => 'Please select a division.',
            'url.url' => 'Please enter a valid URL.'
        ]);
    }

    public function dataInsert($request, $district)
    {
        $district->division_id = $request->division_id;
        $district->name = $request->name;
        $district->bn_name = $request->bn_name;
        $district->lat = $request->lat;
        $district->lon = $request->lon;
        $district->url = $request->url;
        $district->code = $request->code;
        $district->status = $request->status ?? 1;
        $district->save();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, District $district)
    {
        $this->updateValidator($request, $district);

        try {
            $this->dataInsert($request, $district);
            return back()->withSuccess('District Updated Successfully!');

        } catch (\Exception $e) {
            return back()->withError('Error: ' . $e->getMessage());
        }
    }

    public function updateValidator($request, $district)
    {
        $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'name' => 'required|string|max:255|unique:districts,name,' . $district->id,
            'bn_name' => 'nullable|string|max:255|unique:districts,bn_name,' . $district->id,
            'lat' => 'nullable|string|max:50',
            'lon' => 'nullable|string|max:50',
            'url' => 'nullable|max:255',
            'code' => 'nullable|string|max:20',
        ], [
            'name.unique' => 'This district name already exists.',
            'bn_name.unique' => 'This Bangla district name already exists.',
            'division_id.required' => 'Please select a division.',
            'url.url' => 'Please enter a valid URL.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(District $district)
    {
        try {
            if (Thana::where('district_id', $district->id)->exists()) {
                return back()->withErrors('Error: Cannot delete district with associated thanas.');
            }
            if (
                AlumniRegistration::where('district', $district->id)->exists()
                || AlumniRegistration::where('permanent_district', $district->id)->exists()
                || AlumniRegistration::where('current_district', $district->id)->exists()
            ) {
                return back()->withError('Error: Cannot delete this thana as it is associated with alumni registrations.');
            }
            $district->delete();
            return back()->withSuccess('District Deleted Successfully!');
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
            $district = District::findOrFail($request->id);
            $district->status = $request->status;
            $district->save();

            return back()->withSuccess('Status Changed Successfully');
        } catch (\Exception $e) {
            return back()->withError('Error: ' . $e->getMessage());
        }
    }
}
