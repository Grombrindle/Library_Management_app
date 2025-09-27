<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Reports\ReportService;
use App\Models\User;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function add(Request $request)
    {
        $result = $this->reportService->addReport($request->all());

        return response()->json($result);
    }

    public function ignore($id)
    {
        $result = $this->reportService->ignoreReport((int) $id);

        if ($result) {
            $data = ['id' => $id, 'name' => User::find($result['user_id'])->userName, 'message' => 'ignored'];
            session(['user_info' => $data]);
            session(['link' => '/reports']);
            return redirect()->route('user.confirmation');
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function warn($id)
    {
        $result = $this->reportService->warnReport((int) $id);

        if ($result) {

            $data = ['id' => $id, 'name' => User::find($result['user_id'])->userName, 'message' => 'warned'];
            session(['user_info' => $data]);
            session(['link' => '/reports']);
            return redirect()->route('user.confirmation');
        }

        return response()->json([
            'success' => false
        ]);
    }
}