<?php

namespace App\Http\Controllers\Admin;

use App\Config;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\jDate;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $statistics = $this->statistics($request, 'week');

        return view('fp::admin.dashboard')
            ->with('activeMenu', 'dashboard')
            ->with('statistics', $statistics);
    }

    public function live(Request $request)
    {
        if ($request->ajax()) {
            $statistics = $this->statistics($request, 'week');

            return response()->json([
                'statistics' => $statistics
            ]);
        }

        return abort(404);
    }

    public function toggleLive()
    {
        $config = Config::where('key', '=', 'live_stats')->first();
        if ($config) {
            $config->update(['value' => !$config->value]);
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $type
     * @param null $fromDate
     * @param null $toDate
     * @return array
     */
    private function statistics(Request $request, $type, $fromDate = null, $toDate = null)
    {
        switch ($type) {
            case 'now' :
                $transactions = [
                    $this->transactions($request, date('Y-m-d', strtotime('now'))),
                ];
                break;
            case 'week' :
                $transactions = [
                    $this->transactions($request, date('Y-m-d', strtotime('-6 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-5 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-4 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-3 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-2 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-1 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('now'))),
                ];
                break;
            case 'month' :
                $transactions = [
                    $this->transactions($request, date('Y-m-d', strtotime('-29 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-28 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-27 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-26 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-25 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-24 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-23 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-22 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-21 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-20 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-19 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-18 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-17 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-16 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-15 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-14 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-13 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-12 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-11 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-10 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-9 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-8 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-7 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-6 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-5 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-4 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-3 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-2 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('-1 days'))),
                    $this->transactions($request, date('Y-m-d', strtotime('now'))),
                ];
                break;
            case 'period' :
                $transactions = [];
                $dates = create_date_range($fromDate, $toDate);
                foreach ($dates as $date) {
                    array_push($transactions, $this->transactions($request, $date));
                }
                break;
            default:
                $transactions = [
                    $this->transactions($request, date('Y-m-d', strtotime('now'))),
                ];
                break;
        }
        $incomes = [];
        foreach ($transactions as $key => $value) {
            $day = count($transactions) - $key - 1;
            $count = $value->count();
            if ($count) {
                $amount = $value->sum('amount');
            } else {
                $amount = 0;
            }
            $income = [
                'date' => jDate::forge(date('Y-m-d', strtotime('-' . $day . ' days')))->format('date'),
                'income' => $amount,
                'count' => $count,
            ];
            array_push($incomes, $income);
        }

        return $incomes;
    }

    /**
     * @param Request $request
     * @param $date
     * @return mixed
     */
    private function transactions(Request $request, $date)
    {
        try {
            return DB::transaction(function () use ($request, $date) {
                return Transaction::whereBetween('created_at', [$date . ' 00:00:00', $date . ' 23:59:59'])->where('status', '=', 1)->where('verified', '=', 1);
            }, 10);
        } catch (\Exception $e) {
            return [];
        }
    }
}
