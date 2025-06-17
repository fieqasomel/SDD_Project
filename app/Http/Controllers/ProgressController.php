<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Progress;
use App\Models\Inquiry;

class ProgressController extends Controller
{
    public function edit($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return view('ManageProgress.UpdateInquiryProgress', compact('inquiry'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'P_Status' => 'required',
            'P_Description' => 'required|string',
            'P_Date' => 'required|date',
        ]);

        $progress = new Progress();
        $progress->I_ID = $id;
        $progress->P_Status = $request->P_Status;
        $progress->P_Description = $request->P_Description;
        $progress->P_Date = $request->P_Date;
        $progress->save();

        // Optional: Update Inquiry status
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->I_Status = $request->P_Status;
        $inquiry->save();

        return redirect()->route('inquiry.progress.edit', $id)
                         ->with('success', 'Progress updated successfully.');
    }

    public function view(Request $request, $id)
    {
        $progressRecords = Progress::where('I_ID', $id)
                            ->orderByDesc('P_Date')
                            ->get();

        return view('ManageProgress.ViewInquiryProgress', compact('progressRecords'));
    }
}
