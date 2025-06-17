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
            'status' => 'required',
            'description' => 'required|string',
            'updated_date' => 'required|date',
        ]);

        $inquiry = Inquiry::findOrFail($id);

        $progress = new Progress();
        $progress->inquiry_id = $id;
        $progress->status = $request->status;
        $progress->description = $request->description;
        $progress->updated_date = $request->updated_date;
        $progress->save();

        $inquiry->status = $request->status;
        $inquiry->save();

        return redirect()->route('inquiry.progress.edit', $id)
                         ->with('success', 'Progress updated successfully.');
    }

public function view(Request $request, $inquiry_id)
{
    $progressRecords = Progress::where('inquiry_id', $inquiry_id)
                        ->orderBy('created_at', 'desc')
                        ->get();

    return view('ManageProgress.ViewInquiryProgress', compact('progressRecords'));
}

