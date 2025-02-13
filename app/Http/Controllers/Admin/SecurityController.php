<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use App\Models\TermCondition;
use Illuminate\Http\Request;
class SecurityController extends Controller
{
    public function PrivacyPolicy(){
        $data=PrivacyPolicy::first();
        return view('admin.privacyPolicy.index',compact('data'));
    }
    public function PrivacyPolicyEdit(){
        $data=PrivacyPolicy::first();
        return view('admin.privacyPolicy.edit',compact('data'));
    }
    public function PrivacyPolicyUpdate(Request $request){
        $request->validate([
            'description'=>'required'
        ]);
        $data = PrivacyPolicy::first();
        if (!$data) {
            // If no record exists, create a new one
            $data = PrivacyPolicy::create([
                'description' => $request->description
            ]);
        } else {
            // Update the existing record
            $data->update($request->all());
        }
        // $data=PrivacyPolicy::first();
        // PrivacyPolicy::find($data->id)->update($request->all());
        return redirect('/admin/Privacy-policy')->with(['status'=>true, 'message' => 'Privacy Policy Updated Successfully']);
    }
    public function TermCondition(){
        $data=TermCondition::first();
        return view('admin.termCondition.index',compact('data'));
       }
       public function TermConditionEdit(){
           $data=TermCondition::first();
           return view('admin.termCondition.edit',compact('data'));
       }
    public function TermConditionUpdate(Request $request){
        $request->validate([
            'description'=>'required'
        ]);
        $data = TermCondition::first();
        if (!$data) {
            // If no record exists, create a new one
            $data = TermCondition::create([
                'description' => $request->description
            ]);
        } else {
            // Update the existing record
            $data->update($request->all());
        }
        // $data=TermCondition::first();
        // TermCondition::find($data->id)->update($request->all());
        return redirect('/admin/term-condition')->with(['status'=>true, 'message' => 'Terms & Conditions Updated Successfully']);
    }
}
