<?php

namespace App\Http\Controllers;

use App\Model\Process;
use App\Model\InputField;
use App\Model\ProcessCustomField;
use App\Model\SubProcess;
use App\Model\SubProcessField;
use Illuminate\Http\Request;

class SubProcessController extends Controller
{
    public function subProcessView(){


        $sub_processes = SubProcess::all();

        return view("subprocess.all",compact('sub_processes'));
    }

    public function showEditForm(SubProcess $sp){

        $fields = SubProcessField::
        join('input_fields' , 'sub_process_fields.input_field_id' ,'input_fields.id' )
            ->where('sub_process_fields.sub_process_id' , '=', $sp->id)
            ->get();
        $processes = Process::all();
        return view('subprocess.edit_form',compact('sp','processes' ,'fields'));

    }
    public function updateSubProcess(Request $request){

        if ($request->isMethod('post') && $request->has('id')) {

            $subprocess = SubProcess::find($request->id);
            if($request->has('process_id')){
                $subprocess->process_id = $request->process_id;
            }
            $subprocess->Name = $request->Name;
            $subprocess->Description = $request->Description;

            if($subprocess->save()){

                if($request->has('custom_field')){

                    $old_data = ProcessCustomField::where('sub_process_id' , $subprocess->id)->delete();
                    $data = $request->custom_field;

                    foreach($data as $d){
                        if($d != null){
                            $custom_data = new ProcessCustomField;
                            $custom_data->sub_process_id = $subprocess->id;
                            $custom_data->Data = $d;
                            $custom_data->save();
                        }
                    }

                }
                return response('update', 200)->header('Content-Type', 'text/plain');
            }
        }

        return response('error', 500)
            ->header('Content-Type', 'text/plain');

    }

    public function storeSubProcessField(Request $request){

        if ($request->isMethod('post') && $request->has('sub_process_id')) {

            $field = new InputField();
            $field->label = $request->label;
            $field->type = $request->type;
            $field->tool_tip = $request->tool_tip;
            $field->default_value = $request->default_value;
            $field->element = htmlspecialchars($request->element);
            $select_options = array();
            $options_values = array();
            if($request->has('options') && $request->type == 'select'){

                foreach($request->options as $option){

                    $select_options[] = $option['opt'];
                    $options_values[] = $option['val'];
                }

                $field->options = implode(',' ,$select_options);
                $field->option_values = implode(',' ,$options_values);
            }

            if($field->save()){

                $pdf = new SubProcessField();
                $pdf->sub_process_id = $request->sub_process_id;
                $pdf->input_field_id = $field->id;
                $pdf->save();

                return response(json_encode($field->toArray()), 200)->header('Content-Type', 'text/plain');
            }

            return response('error', 500)->header('Content-Type', 'text/plain');

        }

    }
}
