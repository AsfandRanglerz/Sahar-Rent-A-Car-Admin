<?php

namespace App\Http\Controllers\Admin;

use App\Models\SubAdminLog;

use Illuminate\Http\Request;
use App\Models\LicenseApproval;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\LicenseRequest;
use App\Mail\LicenseApprovalActivated;
use App\Mail\LicenseApprovalDeActivated;


class LicenseController extends Controller
{
    
    public function index()
    {
        
        $LicenseApprovals = LicenseApproval::latest()
        ->with(['driver.document'])
        ->whereHas('driver.document', function ($query) {
            $query->whereNotNull('license'); // Assuming 'license' is the column where license data is stored
        })  
        ->get();
        $pendingCount = LicenseApproval::where('counter', 1)->count();
        
        return view('admin.LicenseApproval.index',compact('LicenseApprovals','pendingCount'));
    }

    public function create()
    {
        return view('admin.LicenseApproval.create');
    }

    public function store(LicenseRequest $request)
    {
        // return $request;

        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:LicenseApprovals,email',
        //     'phone' => 'required|string|max:15',
        // ]);

        // $generatedPassword = random_int(10000000, 99999999);


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = null;
        }

        $status = 1;

        // Create the user
        $LicenseApproval = LicenseApproval::create([
            'name' => $request->name,
            'email' => $request->email,
            // 'phone' => $request->phone,
            // 'availability' => $request->availability,
            // 'password' => Hash::make($generatedPassword),
            'image' => $image,
            // 'status' => $status

        ]);

        // Mail::to($LicenseApproval->email)->send(new LicenseApprovalCredentials($LicenseApproval->name, $LicenseApproval->email, $generatedPassword));

        return redirect()->route('license.index')->with(['message' => 'License Approval Created Successfully']);
    }

    public function edit($id)
    {
        $LicenseApproval = LicenseApproval::find($id);
        // return $user;
        return view('admin.LicenseApproval.edit', compact('LicenseApproval'));
    }

    public function update(Request $request, $id)
    {

        // Validate the incoming request
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:LicenseApprovals,email,' . $id,
        //     'phone' => 'required|string|max:15',
        // ]);

        $LicenseApproval = LicenseApproval::findOrFail($id);
        // Handle image upload
        if ($request->hasFile('image')) {
            $destination = 'public/admin/assets/img/users/' . $LicenseApproval->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/admin/assets/images/users', $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = $LicenseApproval->image;
        }
        // $status = $LicenseApproval->status; // Default to existing status
        // if ($request->has('action')) {
        //     if ($request->action == 1) {
        //         $status = 1; // Approved
        //     } elseif ($request->action == 0) {
        //         $status = 0; // Rejected
        //     }
        // }
        return $status;
        // Update user details
        $LicenseApproval->update([
            'name' => $request->name,
            'email' => $request->email,
            // 'phone' => $request->phone,
            // 'availability' => $request->availability,
            'image' => $image,
            'status' => $status
        ]);

        // Redirect back with a success message
        return redirect()->route('license.index')->with(['message' => 'License Approval Updated Successfully']);
    }



    public function destroy(Request $request, $id)
    {
        $LicenseApproval = LicenseApproval::find($id);
        $LicenseApprovalName = $LicenseApproval->name;
        if (Auth::guard('subadmin')->check()) {
            $subadmin = Auth::guard('subadmin')->user();
            $subadminName = $subadmin->name;
            SubAdminLog::create([
                'subadmin_id' => Auth::guard('subadmin')->id(),
                'section' => 'License Approvals',
                'action' => 'Delete',
                'message' => "SubAdmin: {$subadminName} deleted License: {$LicenseApprovalName}",
            ]);
        }
        $LicenseApproval->delete();
        return redirect()->route('license.index')->with(['message' => 'License Approval Deleted Successfully']);
    }

    public function active(Request $request, $id)
    {
        $data = LicenseApproval::find($id);
    
        if (!$data) {
            return redirect()->route('license.index')->with([
                'action' => false,
                'message' => 'License Approval record not found.',
            ]);
        }
    
        $data->update([
            'action' => $request->action,
        ]);
    
        $message['name'] = $data->name;

        $licenseApproval = LicenseApproval::findOrFail($id);
        $licenseApproval->update([
            'status' => 1  // Set status to Approved when action is taken
        ]);
        try {
            Mail::to($data->email)->send(new LicenseApprovalActivated($message));

            return redirect()->route('license.index')->with([
                'action' => true,
                'message' => 'License Approved Successfully',
            ]);
        } catch (\Throwable $th) {
            return back()->with([
                'action' => false,
                'message' => 'Failed to send email: ' . $th->getMessage(),
            ]);
        }
    }
    



    public function deactive(Request $request, $id)
{
    $data = LicenseApproval::find($id);

    if (!$data) {
        return redirect()->route('license.index')->with([
            'action' => false,
            'message' => 'License Approval record not found.',
        ]);
    }

    $data->update([
        'action' => $request->action,
    ]);

    $message['reason'] = $request->reason;
    $message['name'] = $data->name;

 $licenseApproval = LicenseApproval::findOrFail($id);
    $licenseApproval->update([
        'status' => 0  // Set status to Rejected when action is taken
    ]);
    try {
        Mail::to($data->email)->send(new LicenseApprovalDeActivated($message));

        return redirect()->route('license.index')->with([
            'action' => true,
            'message' => 'license Rejected Successfully',
        ]);
    } catch (\Throwable $th) {
        return back()->with([
            'action' => false,
            'message' => 'Failed to send email: ' . $th->getMessage(),
        ]);
    }
}

}
