<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Services\PageService;

class PageController extends Controller
{
    protected $PageService;

    public function __construct(PageService $PageService)
    {
        $this->PageService = $PageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['title', 'status']);
        $data['pages'] = $this->PageService->list($filters);
        $data['request'] = $request;
        return view('backend.page.page', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['page_type'] = 'Create';
        return view('backend.page.page_create_or_update', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validator($request);
        try {
            $this->PageService->create($data);
            return redirect()->route('page.index')->withSuccess('Page created successfully.');
        }catch (\Exception $exception){
            return redirect()->back()->withInput()->withErrors(['error' => $exception->getMessage()]);
        }

    }

    public function validator($request)
    {
        return $request->validate([
            'title'        => 'required|string|max:255',
            'content'       => 'required|string',
            'status'      => 'nullable|string|max:255',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        $data['page'] = $page;
        $data['page_type'] = 'Edit';
        return view('backend.page.page_create_or_update', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $this->validator($request);
        try {
            $this->PageService->update($id, $data);
            return redirect()->route('page.index')->withSuccess('Page Updated successfully.');
        }catch (\Exception $exception){
            return redirect()->back()->withInput()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->PageService->delete($id);
            return redirect()->route('page.index')->withSuccess('Page deleted successfully.');
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['error' => $exception->getMessage()]);
        }
    }
}
