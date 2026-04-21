<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Traits\ImageCustomizeTrait;
use Illuminate\Http\Request;
use App\Services\SettingService;
use App\Models\User;
use App\Traits\FileCustomizeTrait;
use DB;
use Hash;
use Illuminate\Support\Arr;
class SettingController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:setting.index|setting.create|setting.edit|setting.delete', ['only' => ['index','store']]);
         $this->middleware('permission:setting.create', ['only' => ['create','store']]);
         $this->middleware('permission:setting.edit', ['only' => ['edit','update']]);
         $this->middleware('permission:setting.delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $setting = Setting::find(1);
        if($setting == false){
            $setting = new Setting();
            $setting->id = 1;
            $setting->save();
        }
        return view('backend.setting.config',compact('setting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        return redirect('/admin');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        request()->validate([
            'site_title' => 'required',
        ]);

        $setting->update($setting->all());


        return redirect()->route('settigs.index')
            ->with('success','Setting Update successfully.');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'site_title' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:50',
            'became_affiliate' => 'nullable|numeric|min:0',

            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'telegram' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',

            'app_section_title' => 'nullable|string|max:255',
            'app_section_description' => 'nullable|string',
            'app_play_store_link' => 'nullable|string|max:255',
            'app_app_store_link' => 'nullable|string|max:255',

            'footer_description' => 'nullable|string',
            'delivery_cost_in_dhaka' => 'nullable|numeric',
            'delivery_cost_outside_dhaka' => 'nullable|numeric',

            'badge_text' => 'nullable|string|max:255',
            'home_title' => 'nullable|string|max:255',
            'home_description' => 'nullable|string|max:5000',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
            'button_text2' => 'nullable|string|max:255',
            'button_link2' => 'nullable|string|max:255',

            'about_top_title' => 'nullable||string|max:255',
            'about_top_text' => 'nullable|string',
            'about_bottom_title' => 'nullable|string|max:255',
            'about_bottom_text' => 'nullable|string',
            'about_featured_title' => 'nullable|string|max:255',
            'about_featured_text' => 'nullable|string|max:255',
            'about_featured_title2' => 'nullable|string|max:255',
            'about_featured_text2' => 'nullable|string|max:255',
        ]);

        // Always keep single row (id = 1)
        $setting = Setting::firstOrCreate(['id' => 1]);

        // Mass assign safe fields
        $setting->fill($data);
        $setting->save();

        // Handle images separately
        $this->updateImages($request, $setting);

        return redirect()
            ->route('setting.index')
            ->with('success', 'Settings updated successfully.');
    }



    public function updateImages(Request $request, Setting $setting)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'footer_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'favicon' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'home_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'about_top_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'about_bottom_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $images = [
            'logo' => ['width' => 183, 'height' => 36],
            'footer_logo' => ['width' => 183, 'height' => 36],
            'favicon' => ['width' => 16, 'height' => 16],
            'home_banner' => ['width' => 583, 'height' => 362],
            'about_top_banner' => ['width' => 360, 'height' => 324],
            'about_bottom_banner' => ['width' => 360, 'height' => 324],
        ];

        foreach ($images as $field => $size) {
            if ($request->hasFile($field)) {
                if ($setting->$field) {
                    ImageCustomizeTrait::deleteImage($setting->$field);
                }
                $path = ImageCustomizeTrait::uploadImage(
                    $request->file($field),
                    $field,
                    $size['width'],
                    $size['height']
                );
                $setting->$field = $path;
            }
        }

        $setting->save();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }

    public function image_upload(Request $request)
{

$file=$request->file('file');
$path= url('/storages/').'/'.$file->getClientOriginalName();
$imgpath=$file->move(public_path('/storages/'),$file->getClientOriginalName());
$fileNameToStore= $path;


return json_encode(['location' => $fileNameToStore]);

}
}
