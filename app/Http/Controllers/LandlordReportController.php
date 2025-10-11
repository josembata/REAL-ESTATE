<?php

namespace App\Http\Controllers;

use App\Services\LandlordReportService;

class LandlordReportController extends Controller
{
 
public function index(LandlordReportService $reportService)
{
    $landlord = \App\Models\Landlord::where('user_id', auth()->id())->first();
    $landlordId = $landlord ? $landlord->id : null;

    $report = $reportService->getReport($landlordId);

    return view('landlord.report', compact('report'));
}
}
