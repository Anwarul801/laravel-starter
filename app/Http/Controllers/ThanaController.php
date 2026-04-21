<?php

namespace App\Http\Controllers;

use App\Models\AlumniRegistration;
use App\Models\Thana;
use App\Models\District;
use Illuminate\Http\Request;

class ThanaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $this->search_filter($request);
        $data['thanas'] = Thana::with('district')->where($search)->orderBy('id', 'DESC')->paginate(10);
        $data['districts'] = District::where('status', 1)->orderBy('name', 'ASC')->get();
        $data['request'] = $request;
        return view('backend.thana', $data);
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
        if ($request->district_id){
            $search[] = ['district_id', $request->district_id];
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
            $thana = new Thana();
            $this->dataInsert($request, $thana);
            return back()->withSuccess('Thana Added Successfully!');

        } catch (\Exception $e) {
            return back()->withError('Error: ' . $e->getMessage());
        }
    }

    public function storeValidator($request)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255|unique:thanas,name',
            'bn_name' => 'nullable|string|max:255|unique:thanas,bn_name',
            'url' => 'nullable|max:255',
        ], [
            'name.unique' => 'This thana name already exists.',
            'bn_name.unique' => 'This Bangla thana name already exists.',
            'district_id.required' => 'Please select a district.',
            'url.url' => 'Please enter a valid URL.'
        ]);
    }

    public function dataInsert($request, $thana)
    {
        $thana->district_id = $request->district_id;
        $thana->name = $request->name;
        $thana->bn_name = $request->bn_name;
        $thana->url = $request->url;
        $thana->status = $request->status ?? 1;
        $thana->save();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Thana $thana)
    {
        $this->updateValidator($request, $thana);

        try {
            $this->dataInsert($request, $thana);
            return back()->withSuccess('Thana Updated Successfully!');

        } catch (\Exception $e) {
            return back()->withError('Error: ' . $e->getMessage());
        }
    }

    public function updateValidator($request, $thana)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255|unique:thanas,name,' . $thana->id,
            'bn_name' => 'nullable|string|max:255|unique:thanas,bn_name,' . $thana->id,
            'url' => 'nullable|max:255',
        ], [
            'name.unique' => 'This thana name already exists.',
            'bn_name.unique' => 'This Bangla thana name already exists.',
            'district_id.required' => 'Please select a district.',
            'url.url' => 'Please enter a valid URL.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Thana $thana)
    {
        try {
            if (
                AlumniRegistration::where('thana', $thana->id)->exists()
                || AlumniRegistration::where('permanent_thana', $thana->id)->exists()
                || AlumniRegistration::where('current_thana', $thana->id)->exists()
            ) {
                return back()->withError('Error: Cannot delete this thana as it is associated with alumni registrations.');
            }
            $thana->delete();
            return back()->withSuccess('Thana Deleted Successfully!');
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
            $thana = Thana::findOrFail($request->id);
            $thana->status = $request->status;
            $thana->save();

            return back()->withSuccess('Status Changed Successfully');
        } catch (\Exception $e) {
            return back()->withError('Error: ' . $e->getMessage());
        }
    }
}
