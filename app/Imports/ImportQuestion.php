<?php
/**
 * @Author: Anwarul
 * @Date: 2026-01-08 14:58:16
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-08 15:08:18
 * @Description: Innova IT
 */

namespace App\Imports;

use App\Models\QuizQuestion;
use App\Models\Lesson;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;

class ImportQuestion implements ToCollection, WithHeadingRow, ToModel
{
    protected $lesson_id;
    public function __construct($lesson_id)
    {
        $this->lesson_id = $lesson_id;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $rows = 0;
    public function collection(Collection $rows) {
        $canImport = true;
        if($canImport) {
               $lession = Lesson::with('question')->where('id',$this->lesson_id)->first();
                if (empty($lession->question)){
                    $order = 0;
                }else{
                    $order = $lession->question->order;
                }
            foreach ($rows as $row) {
                $question = new QuizQuestion();
                $question->lesson_id = $this->lesson_id;
                $question->course_id = $lession->course_id;
                $question->module_id = $lession->module_id;
                $question->name =  $row['title'];
                $question->option1 = $row['option1'];
                $question->option2 = $row['option2'];
                $question->option3 = $row['option3'];
                $question->option4 = $row['option4'];
                $question->notes = $row['notes'];
                $question->correct_answers = $row['answer'];
                $question->mark = 1;
                $question->order = $order +1;
                $question->save();
            }
        }
    }

    public function model(array $row)
    {
        //dd($row);
        ++$this->rows;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    // public function model(array $row)
    // {
    //     return new Question([
    //         //
    //     ]);
    // }
}
