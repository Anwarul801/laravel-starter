<?php

namespace App\Traits;

use App\Models\Union;
use App\Models\Thana;
use App\Models\District;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Exam;
use App\Models\Registration;
use App\Models\EducationLevel;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


trait HelperTrait{

    /**
     * @param $img_name
     * @param null $attribute
     * @param int $width
     * @param string $file_extension
     * @return null|string
     */
    public static function getDistrictByDivition($request){
        $districts = District::where('division_id',$request->division_id)->orderby('bn_name','asc')->get();
        $options = '<option value="">--জেলা--</option>';
        foreach ($districts as $district){
            $options .= "<option value='{$district->id}'>{$district->bn_name}</option>";
        }
        return response()->json([
            'options' => $options
        ]);
    }

    public static function getThanaByDistrict($request){
        $thanas = Thana::where('district_id',$request->district_id)->orderby('bn_name','asc')->get();
        $options = '<option value="">--থানা/উপজেলা--</option>';
        foreach ($thanas as $thana){
            $options .= "<option value='{$thana->id}'>{$thana->bn_name}</option>";
        }
        return response()->json([
            'options' => $options
        ]);
    }
    public static function getUnionByThana($request){
        $unions = Union::where('thana_id',$request->thana_id)->orderby('bn_name','asc')->get();
        $options = '<option value="">--ইউনিয়ন--</option>';
        foreach ($unions as $union){
            $options .= "<option value='{$union->id}'>{$union->bn_name}</option>";
        }
        return response()->json([
            'options' => $options
        ]);
    }
    public static function getTeacherByAffiliation($request){
        $teachers = Teacher::where('affiliation_id',$request->affiliation_id)->orderby('name','asc')->get();
        $options = '<option value="">--Select Teacher--</option>';
        foreach ($teachers as $teacher){
            $options .= "<option teacher_name='{$teacher->name}' phone='{$teacher->phone}' value='{$teacher->id}'>{$teacher->name} [{$teacher->phone}]</option>";
        }
        return response()->json([
            'options' => $options
        ]);
    }
    public static function getsubjectByexam($request){
        $exam = Exam::where('id',$request->exam_id)->first();
        $subjects =  Subject::where('level_id',$exam->education_level_id)->get();
        $options = '<option value="">--Subject--</option>';
        foreach ($subjects as $subject){
            $options .= "<option value='{$subject->id}'>{$subject->name}{$subject->code}</option>";
        }
        return response()->json([
            'options' => $options
        ]);
    }
    public static function getsubjectBylevel($request){
        //$exam = Exam::where('id',$request->exam_id)->first();
        $subjects =  Subject::where('level_id',$request->education_level_id)->get();
        $options = '';
        foreach ($subjects as $subject){
            $options .= "<option value='{$subject->id}'>{$subject->name}{$subject->code}</option>";
        }
        return response()->json([
            'options' => $options
        ]);
    }


}
