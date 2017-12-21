<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psy\Util\Json;

class SystemAdminController extends Controller
{
    /**
     * Class constructor.
     * get current route name for page title.
     */
    public function __construct()
    {
        $this->page_title = \Request::route()->getName();
    }

    /**
     * System Admin page
     * show today, weekly, monthly, yearly visitor count.
     *
     * @return HTML view Response.
     */
    public function SystemAdminHomePage()
    {

        $data['page_title'] = $this->page_title;
        $today = date('Y-m-d');
        $today_count=\DB::table('access_log')
             ->where('created_at','like',$today."%")
            ->select('access_client_ip')
            ->groupBy('access_client_ip')
            ->get();
        $data['today_count']=count($today_count);
        $from = date('Y-m-d')." 00:00:00";
        $last_week = date("Y-m-d", strtotime("-1 week"))." 23:59:59";
        $weekly_count=\DB::table('access_log')
             ->whereBetween('access_log.created_at',array($last_week,$from))
             ->select('access_client_ip')
             ->groupBy('access_client_ip')
             ->get();
        $data['weekly_count']=count($weekly_count);
        $last_month = date("Y-m-d", strtotime("-1 month"))." 23:59:59";
        $monthly_count=\DB::table('access_log')
             ->whereBetween('access_log.created_at',array($last_month,$from))
             ->select('access_client_ip')
             ->groupBy('access_client_ip')->get();
        $data['monthly_count']=count($monthly_count);
        $last_year= date("Y-m-d", strtotime("-1 year"))." 23:59:59";
        $yearly_count=\DB::table('access_log')
             ->whereBetween('access_log.created_at',array($last_year,$from))
             ->select('access_client_ip')
            ->groupBy('access_client_ip')->get();
        $data['yearly_count']=count($yearly_count);
        return \View::make('dashboard.common-blade.system-admin-home',$data);
    }

    /**
     * Visitor count weekly, monthly, yearly basis.
     *
     * @param Request $request
     * @return JSON encoded data.
     */
    public function CountVisitor(Request $request)
    {
        $data = $request->all();
        if ($data['para'] == 'w') {
            $arrayResult = array();
            $from = date('Y-m-d')." 00:00:00";
            $lastWeek = date("Y-m-d", strtotime("-1 week"))." 23:59:59";
            $data=\DB::table('access_log')
                ->whereBetween('access_log.created_at',array($lastWeek,$from))
                ->select(\DB::raw('CAST(created_at AS DATE) as d, count(distinct(`access_client_ip`)) as ip'))
                ->groupBy('d')
                ->get();
            foreach ($data as $d) {
                $arrayResult[] = array(
                    'd' => $d->d,
                    'ip' => $d->ip
                );
            }
            echo json_encode($arrayResult);
        } else if($data['para'] == 'm') {
            $arrayResult = array();
            $from = date('Y-m-d')." 00:00:00";
            $lastMonth = date("Y-m-d", strtotime("-2 month"))." 23:59:59";
            $data=\DB::table('access_log')
                ->whereBetween('access_log.created_at',array($lastMonth,$from))
                ->select(\DB::raw('CAST(created_at AS DATE) as d, count(distinct(`access_client_ip`)) as ip'))
                ->groupBy('d')
                ->get();
            foreach ($data as $d) {
                $arrayResult[] = array(
                    'd' => $d->d,
                    'ip' => $d->ip
                );
            }
            echo json_encode($arrayResult);
        } else if ($data['para'] == 'y') {
            $arrayResult = array();
            $from = date('Y-m-d')." 00:00:00";
            $lastYear= date("Y-m-d", strtotime("-1 year"))." 23:59:59";
            $data=\DB::table('access_log')
                ->whereBetween('access_log.created_at',array($lastYear,$from))
                ->select(\DB::raw('CAST(created_at AS DATE) as d, count(distinct(`access_client_ip`)) as ip'))
                ->groupBy('d')
                ->get();

            foreach ($data as $d) {
                $arrayResult[] = array(
                    'd' => $d->d,
                    'ip' => $d->ip
                );
            }
            echo json_encode($arrayResult);
        }
    }
}
