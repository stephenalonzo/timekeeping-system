<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Punch;
use Illuminate\Support\Str;
use App\Http\Requests\PunchRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class PunchController extends Controller
{
    public function index()
    {
        // Define baseline
        $firstPeriodStart = Carbon::create(now()->year, 9, 29);
        $periodLength = 14;
        $payLag = 7;

        // Compute current pay period
        $periodNumber = floor($firstPeriodStart->diffInDays(now()) / $periodLength);
        $currentPeriodStart = $firstPeriodStart->copy()->addDays($periodNumber * $periodLength);
        $currentPeriodEnd = $currentPeriodStart->copy()->addDays($periodLength - 1)->endOfDay();

        // Compute punch data
        $totals = Punch::where('employeeId', Auth::user()->employeeId)->where('time_worked', '>', 0)->whereBetween('day_in', [session('currentPeriodStart'), session('currentPeriodEnd')])->orderBy('day_in')->get();
        $timeWorked = $totals->sum('time_worked');
        $workDays = $totals->count();
        $normalHours = 8;
        $regularHours = min($timeWorked, $workDays * $normalHours);
        $overTime = max($timeWorked - $regularHours, 0);

        // Set the currentPeriodStart/End to keep track of the current month of timesheets
        Session::put('currentPeriodStart', $currentPeriodStart);
        Session::put('currentPeriodEnd', $currentPeriodEnd);

        $punches = Punch::where('employeeId', Auth::user()->employeeId)->whereBetween('day_in', [session('currentPeriodStart'), session('currentPeriodEnd')])->orderBy('day_in')->get();

        // Get row where current punch exist using user refNo
        $current = Punch::where('refNo', Auth::user()->refNo)->whereDate('day_in', now())->first();

        return view('dashboard', compact(['punches', 'current', 'regularHours', 'overTime']));
    }

    public function show(User $user)
    {
        // Define baseline
        $firstPeriodStart = Carbon::create(now()->year, 9, 29);
        $periodLength = 14;

        // Compute current pay period
        $periodNumber = floor($firstPeriodStart->diffInDays(now()) / $periodLength);

        // Compute punch data
        $totals = Punch::where('employeeId', $user->employeeId)
            ->where('time_worked', '>', 0)
            ->whereBetween('day_in', [session('currentPeriodStart'), session('currentPeriodEnd')])
            ->orderBy('day_in')
            ->get();
        $timeWorked = $totals->sum('time_worked');
        $workDays = $totals->count();
        $normalHours = 8;
        $regularHours = min($timeWorked, $workDays * $normalHours);
        $overTime = max($timeWorked - $regularHours, 0);

        $punches = Punch::where('employeeId', $user->employeeId)
            ->whereBetween('day_in', [session('currentPeriodStart'), session('currentPeriodEnd')])
            ->orderBy('day_in')
            ->get();

        // Get the employee's past punches
        $archives = Punch::where('employeeId', $user->employeeId)
            ->whereNotBetween('day_in', [session('currentPeriodStart'), session('currentPeriodEnd')])
            ->orderBy('day_in', 'desc')
            ->get()
            ->groupBy(fn($punch) => Carbon::parse($punch->day_in)->format('F Y'))
            ->map(function ($group, $month) {
                $archiveWorked = $group->sum('time_worked');
                $archiveDays = $group->count();

                $archiveNormal = 8;
                $archiveRegular = min($archiveWorked, $archiveDays * $archiveNormal);
                $archiveOT = max($archiveWorked - $archiveRegular, 0);

                return [
                    'month' => $month,
                    'punches' => $group,
                    'archiveRegular' => $archiveRegular,
                    'archiveOT' => $archiveOT,
                    'uniqueId' => Str::slug($month)
                ];
            });

        return view('timesheets.show', compact([
            'punches',
            'archives',
            'regularHours',
            'overTime'
        ]));
    }

    public function store(PunchRequest $request)
    {
        $validated = $request->validated();

        switch ($validated['mode']) {
            case 1:
                $punch = Punch::where('refNo', Auth::user()->refNo)->whereDate('day_in', now())->get();

                // Check if row exists
                if (count($punch) == 0) {
                    // Generate reference number
                    $refNo = rand(1000000, 9999999);
                    Session::put('refNo', $refNo);
                    User::where('employeeId', Auth::user()->employeeId)->update([
                        'refNo' => session('refNo')
                    ]);

                    // Create punch
                    Punch::create([
                        'employeeId' => Auth::user()->employeeId,
                        'day_in' => now(),
                        'refNo' => session('refNo')
                    ]);
                }
                break;
            case 2:
                // Update row to insert lunch_out
                Punch::where('employeeId', '=', Auth::user()->employeeId)->where('refNo', '=', Auth::user()->refNo)->whereDate('day_in', now())->whereNull('lunch_out')->latest()->limit(1)->update([
                    'lunch_out' => now()
                ]);
                break;
            case 3:
                // Update row to insert lunch_in
                Punch::where('employeeId', '=', Auth::user()->employeeId)->where('refNo', '=', Auth::user()->refNo)->whereDate('day_in', now())->whereNotNull('lunch_out')->whereNull('lunch_in')->latest()->limit(1)->update([
                    'lunch_in' => now()
                ]);
                break;
            case 4:
                $punch = Punch::where('employeeId', '=', Auth::user()->employeeId)->where('refNo', '=', Auth::user()->refNo)->whereDate('day_in', now())->latest()->first();

                $hours = Carbon::parse($punch->day_in)->diffInMinutes(now());
                $lunchTime = 0;

                if ($punch->lunch_out && $punch->lunch_in) {
                    $lunchTime = Carbon::parse($punch->lunch_out)->diffInMinutes($punch->lunch_in);
                }

                $netMinutes = max($hours - $lunchTime, 0);
                $timeWorked = round($netMinutes / 60, 2);

                // Update row to insert day_out
                Punch::where('employeeId', '=', Auth::user()->employeeId)->where('refNo', '=', Auth::user()->refNo)->whereDate('day_in', now())->whereNotNull('day_in')->whereNull('time_worked')->latest()->limit(1)->update([
                    'day_out' => now(),
                    'time_worked' => $timeWorked
                ]);

                // Remove session reference number
                User::where('employeeId', Auth::user()->employeeId)->update([
                    'refNo' => null,
                ]);
                Session::forget(['refNo', 'currentPeriodStart', 'currentPeriodEnd']);
                break;
            default:
                return back()->withErrors(['mode' => 'Undefined action. Please contact your admin for help.'])->onlyInput('mode');
                break;
        }
        return back();
    }

    public function export()
    {
        // Define baseline
        $firstPeriodStart = Carbon::create(now()->year, 9, 29);
        $periodLength = 14;

        // Compute current pay period
        $periodNumber = floor($firstPeriodStart->diffInDays(now()) / $periodLength);

        $currentPeriodStart = $firstPeriodStart->copy()->addDays($periodNumber * $periodLength);
        $currentPeriodEnd = $currentPeriodStart->copy()->addDays($periodLength - 1)->endOfDay();

        $punches = Punch::where('employeeId', Auth::user()->employeeId)->whereBetween('day_in', [$currentPeriodStart, $currentPeriodEnd])->orderBy('day_in')->get();
        $csvData = "Day,In,Out,In,Out,Total Hours\n";

        // Build CSV
        // Create headers
        foreach ($punches as $punch) {
            $csvData .= sprintf(
                "%s,%s,%s,%s,%s,%s\n",
                date('d', strtotime($punch->day_in)),
                $punch->day_in ? date('h:i A', strtotime($punch->day_in)) : '--:-- --',
                $punch->lunch_out ? date('h:i A', strtotime($punch->lunch_out)) : '--:-- --',
                $punch->lunch_in ? date('h:i A', strtotime($punch->lunch_in)) : '--:-- --',
                $punch->day_out ? date('h:i A', strtotime($punch->day_out)) : '--:-- --',
                $punch->time_worked ?? '0'
            );
            foreach ($punch->users as $user) {
                $fileName = '' . $user->name . '_EmployeeId-' . $punch->employeeId . '_' . $currentPeriodStart->format('Ymd') . '-' . $currentPeriodEnd->format('Ymd') . '_TIMESHEET.csv';
            }
        }

        // Compute totals
        $totalWorked = $punches->sum('time_worked');
        $totalDays = $punches->count();
        $normalHours = 8;
        $regularHours = min($totalWorked, $totalDays * $normalHours);
        $overTime = max($totalWorked - $regularHours, 0);

        $csvData .= "\nTotal Regular Hours:,$regularHours\n";
        $csvData .= "Total Overtime Hours:,$overTime\n";

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\""
        ]);
    }

    public function archiveExport($employeeId, $month)
    {
        // Find employee
        $user = User::where('employeeId', $employeeId)->firstOrFail();

        // Parse the month (e.g. "September 2025")
        try {
            $monthDate = Carbon::parse($month);
        } catch (\Exception $e) {
            return back()->with('error', 'Invalid month format.');
        }

        // Define start and end of month
        $startOfMonth = $monthDate->copy()->startOfMonth();
        $endOfMonth = $monthDate->copy()->endOfMonth();

        // Get all punches for that employee and month
        $punches = Punch::where('employeeId', $user->employeeId)
            ->whereBetween('day_in', [$startOfMonth, $endOfMonth])
            ->orderBy('day_in')
            ->get();

        if ($punches->isEmpty()) {
            return back()->with('error', 'No punches found for this archived month.');
        }

        // Build CSV
        // Create headers
        $csvData = "Day,In,Out,In,Out,Total Hours\n";

        foreach ($punches as $punch) {
            $csvData .= sprintf(
                "%s,%s,%s,%s,%s,%s\n",
                date('d', strtotime($punch->day_in)),
                $punch->day_in ? date('h:i A', strtotime($punch->day_in)) : '--:-- --',
                $punch->lunch_out ? date('h:i A', strtotime($punch->lunch_out)) : '--:-- --',
                $punch->lunch_in ? date('h:i A', strtotime($punch->lunch_in)) : '--:-- --',
                $punch->day_out ? date('h:i A', strtotime($punch->day_out)) : '--:-- --',
                $punch->time_worked ?? '0'
            );
        }

        // Compute totals
        $totalWorked = $punches->sum('time_worked');
        $totalDays = $punches->count();
        $normalHours = 8;
        $regularHours = min($totalWorked, $totalDays * $normalHours);
        $overTime = max($totalWorked - $regularHours, 0);

        $csvData .= "\nTotal Regular Hours:,$regularHours\n";
        $csvData .= "Total Overtime Hours:,$overTime\n";

        // File name
        $fileName = sprintf(
            '%s_EmployeeId-%s_%s-%s_TIMESHEET.csv',
            str_replace(' ', '_', $user->name),
            $user->employeeId,
            $startOfMonth->format('Ymd'),
            $endOfMonth->format('Ymd')
        );

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ]);
    }

    public function employees()
    {
        // Get employees
        $users = User::select('name', 'employeeId', 'refNo')->get();

        return view('employees', compact('users'));
    }
}
