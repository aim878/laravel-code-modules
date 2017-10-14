<?php

namespace App\Http\Controllers;

use App\Model\Control;
use App\Model\CustomField;
use App\Model\InputField;

use App\Model\InputFieldData;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;
use App\Model\ProcessDomain;

class ProcessDomainController extends Controller
{
    public function processDomainView(){


        $domains = ProcessDomain::all();

        return view("domain.all",compact('domains'));
    }

    public function showEditForm(ProcessDomain $pd){

        $fields = InputField::with('data')->where('table_name', 'process_domain')->where('refrence_id' , $pd->id)->get();

        return view('domain.edit_form',compact('pd','fields'));

    }

    public function updateProcessDomain(Request $request){

//        dd($request->all());
        if ($request->isMethod('post') && $request->has('id')) {

            $domain = ProcessDomain::find($request->id);

            $domain->Name = $request->Name;
            $domain->Description = $request->Description;

            if($request->has('custom_select')){

                foreach($request->custom_select as $option) {

                    $data = new InputFieldData();
                    $data->refrence_id = $domain->id;
                    $data->Data = $option;
                    $data->save();
                }
            }
            if($request->has('custom_text')){

                foreach($request->custom_text as $text) {

                    $data = new InputFieldData();
                    $data->refrence_id = $domain->id;
                    $data->Data = $text;
                    $data->save();
                }
            }
            if($request->has('custom_date')){

                foreach($request->custom_date as $date) {

                    $data = new InputFieldData();
                    $data->refrence_id = $domain->id;
                    $data->Data = $data;
                    $data->save();
                }
            }


            if($domain->save()){


                    return response('update', 200)->header('Content-Type', 'text/plain');

//
//                    $field = InputField::find($request->id);
//                    $field->Data = $request->custom_field;
            }
        }

        return response('error', 500)
            ->header('Content-Type', 'text/plain');

    }


    public function storeDomainCustomField(Request $request){

        if ($request->isMethod('post') && $request->has('process_domain_id')) {

            $field = new InputField();
            $field->label = $request->label;
            $field->table_name = 'process_domain';
            $field->refrence_id = $request->process_domain_id;
            $field->type = $request->type;
            $field->tool_tip = $request->tool_tip;
            $field->default_value = $request->default_value;
            $field->element = $request->element;
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

                return response(json_encode($field->toArray()), 200)->header('Content-Type', 'text/plain');
            }

            return response('error', 5process-hierarchy-view00)->header('Content-Type', 'text/plain');

        }


    }

    public function getDomain(ProcessDomain $domain){


//        $domain->load('subProcess.control')->pluck('subprocess.id');


        $controls = Control::join('sub_process' , 'control.sub_process_id','=' , 'sub_process.id')
            ->join('process' , 'sub_process.process_id' , '=' , 'process.id')
            ->join('process_domain' , 'process.process_domain_id' , '=' , 'process_domain.id')
            ->where('process_domain.id' , $domain->id)
            ->select(
                'control.id AS control_id',
                'process.id AS process.id',
                'control.Name',
                'control.Description',
                'process_domain.id AS process_domain_id',
                'process_domain.Name AS name',
                'process_domain.description AS des',
                'process.Name AS n'
            )
            ->get();

        return Datatables::of($controls)->make();

        return compact('domain','controls');
    }
}
