<?php

namespace App\Http\Controllers\Processing;

use App\Http\Controllers\Controller;
use App\Models\EmployeeBiometricLog;
use App\Services\DatFileProcessor;
use Illuminate\Http\Request;

class ProcessAttendanceController extends Controller
{
    public function index()
    {
        return inertia('processing-attendance/Uploading');
    }
    public function upload(Request $request, DatFileProcessor $processor)
    {
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $file = $request->file('file');

        $filename = $file->getClientOriginalName();

        $records = $processor->process($file->getRealPath());
        foreach ($records as &$record) {
            $record['FileName'] = $filename;
        }

        $chunkSize = 200;
        foreach (array_chunk($records, $chunkSize) as $chunk) {
            EmployeeBiometricLog::upsert(
                $chunk,
                ['EmpID', 'DateTime'],
                ['VerificationMode', 'InOut', 'WordCode', 'Reserved', 'FileName']
            );
        }
        return back()->with('success', 'File processed successfully!');
    }
    public function interpretAttendanceLogs()
    {
        return inertia('processing-attendance/InterpretAttendanceLogs');
    }
}
