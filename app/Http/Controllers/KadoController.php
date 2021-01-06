<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KadoController extends Controller
{
    /**
     * 1人の半期のチャートを表示
     */
    public function list($user_id, $term_id) {
        $user = DB::table('users')
                    ->where('id', '=', $user_id)
                    ->first();

        $users = DB::table('users')
                    ->select('id', 'name')
                    ->get();

        $term = DB::table('terms')
                    ->where('id', '=', $term_id)
                    ->first();

        $terms = DB::table('terms')
                    ->select('id', 'term_name')
                    ->get();

        $term_prev_id = $term_id - 1;
        $term_next_id = $term_id + 1;
        $current_year_month = date('Y/n');
        $current_term_name = $this->convertYearMonthIntoTeam($current_year_month);
        $current_term_id = $this->getTermId($current_term_name);

        $terms_around = [
            'term_prev_id' => $term_prev_id,
            'term_next_id' => $term_next_id,
            'term_prev_name' => $this->getTermName($term_prev_id),
            'term_next_name' => $this->getTermName($term_next_id),
            'term_current_id' => $current_term_id,
            'term_current_name' => $current_term_id,
            'term_dates' => $this->convertTeamIntoYearMonth($this->getTermName($term_id)),
            'term_year_months' => $this->termDatesToYearMonths($this->convertTeamIntoYearMonth($this->getTermName($term_id))),
        ];

        return view('kado.list', compact('user_id', 'term_id', 'user', 'term', 'terms_around', 'users', 'terms'));
    }


    /**
     * 1人の半期のチャートのラベルと値をApiで送信する
     */
    public function setChartApi($user_id, $term_id) {
        $syauris = $this->getChartPrices($user_id, $term_id);
        $labels = $this->convertTeamIntoYearMonth($this->getTermName($term_id));
        $work_times = $this->getChartWorkTimes($user_id, $term_id);
        $datas = [
            'labels' => $labels, 
            'work_time' => $work_times, 
            'syauri' => $syauris
        ];

        return response()->json($datas);
    }


    /**
     * 全員のひと月のチャートを表示
     */
    public function listAll($year, $month) {
        $users = DB::table('users')
                    ->select('id', 'name')
                    ->get();

        foreach ($users as $user) {
            $projects_total_price = DB::table('month_prices')
                        ->leftJoin('projects', 'month_prices.project_id', '=', 'projects.id')
                        ->where('projects.user_id', '=', $user->id)
                        ->where('month_prices.date', '=', $year.'/'.$month)
                        ->sum('month_prices.price');
    
            $projects_total_working_time = DB::table('month_prices')
                        ->leftJoin('projects', 'month_prices.project_id', '=', 'projects.id')
                        ->where('projects.user_id', '=', $user->id)
                        ->where('month_prices.date', '=', $year.'/'.$month)
                        ->sum('month_prices.working_time');
            
            $all_user[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'projects_total_price' => $projects_total_price,
                'projects_total_working_time' => $projects_total_working_time,
            ];
        }

        if ($month == 1) {
            $prev_year = $year - 1;
            $prev_month = 12;
        } else {
            $prev_year = $year;
            $prev_month = $month - 1;
        }
        
        if ($month == 12) {
            $next_year = $year + 1;
            $next_month = 1;
        } else {
            $next_year = $year;
            $next_month = $month + 1;
        }
        $year_months_around = [
            'prev_year' => $prev_year,
            'prev_month' => $prev_month,
            'next_year' => $next_year,
            'next_month' => $next_month,
        ];

        return view('kado.list_all', compact('year', 'month', 'all_user', 'year_months_around'));
    }


    /**
     * 全員のひと月のチャートのラベルと値をApiで送信する
     */
    public function setAllChartApi($year, $month) {
        $users = DB::table('users')
                    ->select('id', 'name')
                    ->get();

        foreach($users as $user){
            $labels[] = $user->name;
            $work_times[] = $this->getChartMonthWorkTime($user->id, $year, $month);
            $syauris[] = $this->getMonthChartPrices($user->id, $year, $month);
        }

        $datasets = [
            'labels' => $labels,
            'work_times' => $work_times,
            'syauris' => $syauris,
        ];

        return response()->json($datasets);
    }


    /**
     * 1人のひと月のチャートを表示
     */
    public function listDetail($user_id, $year, $month) {
        $user = DB::table('users')
                    ->where('id', '=', $user_id)
                    ->first();

        $users = DB::table('users')
                    ->select('id', 'name')
                    ->get();

        $projects = DB::table('month_prices')
                    ->leftJoin('projects', 'month_prices.project_id', '=', 'projects.id')
                    ->select('month_prices.*', 
                            'projects.*', 
                            'month_prices.id as month_price_id',
                            'projects.id as project_id')
                    ->where('projects.user_id', '=', $user_id)
                    ->where('month_prices.date', '=', $year.'/'.$month)
                    ->orderBy('month_price_id', 'desc')
                    ->get();

        $projects_total_price = DB::table('month_prices')
                    ->leftJoin('projects', 'month_prices.project_id', '=', 'projects.id')
                    ->where('projects.user_id', '=', $user_id)
                    ->where('month_prices.date', '=', $year.'/'.$month)
                    ->sum('month_prices.price');

        $projects_total_working_time = DB::table('month_prices')
                    ->leftJoin('projects', 'month_prices.project_id', '=', 'projects.id')
                    ->where('projects.user_id', '=', $user_id)
                    ->where('month_prices.date', '=', $year.'/'.$month)
                    ->sum('month_prices.working_time');

        $projects_sum = array(
            'projects_total_price' => $projects_total_price,
            'projects_total_working_time' => $projects_total_working_time,
        );


        if ($month == 1) {
            $prev_year = $year - 1;
            $prev_month = 12;
        } else {
            $prev_year = $year;
            $prev_month = $month - 1;
        }
        
        if ($month == 12) {
            $next_year = $year + 1;
            $next_month = 1;
        } else {
            $next_year = $year;
            $next_month = $month + 1;
        }
        $year_months_around = [
            'prev_year' => $prev_year,
            'prev_month' => $prev_month,
            'next_year' => $next_year,
            'next_month' => $next_month,
        ];

        return view('kado.list_detail', compact('user_id','year', 'month', 'user', 'users', 'projects', 'projects_sum', 'year_months_around'));
    }


    /**
     * 1人のひと月のチャートのラベルと値をApiで送信する
     */
    public function setDetailChartApi($user_id, $year, $month) {
        $projects = DB::table('month_prices')
                    ->leftJoin('projects', 'month_prices.project_id', '=', 'projects.id')
                    ->select('month_prices.*', 
                            'projects.*', 
                            'month_prices.id as month_price_id',
                            'projects.id as project_id')
                    ->where('projects.user_id', '=', $user_id)
                    ->where('month_prices.date', '=', $year.'/'.$month)
                    ->orderBy('month_price_id', 'desc')
                    ->get();

        $background_colors = [
            '#FDEA6E',
            '#F3B454',
            '#E97D3B',
            '#CF4F29',
            '#753B2C',
            '#FDEA6E',
            '#F3B454',
            '#E97D3B',
            '#CF4F29',
            '#753B2C',
        ];
        $datasets = array();

        foreach ($projects as $index => $project) {
            $label = '【'.$project->client_name.'】'.$project->project_name;
            $data = array($project->price);

            if ($index < count($background_colors)) {
                $background_color = $background_colors[$index];
            } else {
                $background_color = $background_colors[(int) substr($index, -1)];
            }
            $datas = array(
                'label' => $label,
                'data' => $data,
                'backgroundColor' => $background_color,
            );
            $datasets[] = $datas;
        }

        return response()->json($datasets);
    }


    /**
     * チャートの社売を取得
     */
    public function getChartPrices($user_id, $term_id) {
        $term = $this->getTermName($term_id);
        $dates = $this->convertTeamIntoYearMonth($term);

        foreach ($dates as $date) {
            $values[] = DB::table('month_prices')
                        ->leftJoin('projects', 'month_prices.project_id', '=', 'projects.id')
                        ->select('month_prices.*', 
                                'projects.*', 
                                'month_prices.id as month_price_id',
                                'projects.id as project_id')
                        ->where('projects.user_id', '=', $user_id)
                        ->where('month_prices.date', '=', $date)
                        ->sum('month_prices.price');
            // $values[] = DB::table('projects')
            //             ->leftJoin('users', 'projects.user_id', '=', 'users.id')
            //             ->select('projects.*', 
            //                         'users.*', 
            //                         'users.id as user_id',
            //                         'projects.id as project_id')
            //             ->where('user_id', '=', $user_id)
            //             ->where('date', '=', $date)
            //             ->sum('price');
        }
        // dd($values);
        
        return $values;
    }


    /**
     * チャートのひと月の社売を取得
     */
    public function getMonthChartPrices($user_id, $year, $month) {
        $price = DB::table('month_prices')
                    ->leftJoin('projects', 'month_prices.project_id', '=', 'projects.id')
                    ->where('projects.user_id', '=', $user_id)
                    ->where('month_prices.date', '=', $year.'/'.$month)
                    ->sum('month_prices.price');
        
        return $price;
    }


    /**
     * チャートの稼働時間を取得
     */
    public function getChartWorkTimes($user_id, $term_id) {
        $term = $this->getTermName($term_id);
        $dates = $this->convertTeamIntoYearMonth($term);

        $values = array();
        foreach ($dates as $date) {
            $work_times[] = DB::table('month_prices')
                            ->leftJoin('projects', 'month_prices.project_id', '=', 'projects.id')
                            ->select('month_prices.*', 
                                    'projects.*', 
                                    'month_prices.id as month_price_id',
                                    'projects.id as project_id')
                            ->where('projects.user_id', '=', $user_id)
                            ->where('month_prices.date', '=', $date)
                            ->sum('month_prices.working_time');
            // $work_times[] = DB::table('projects')
            //                 ->leftJoin('users', 'projects.user_id', '=', 'users.id')
            //                 ->select('projects.working_time', 'projects.date')
            //                 ->where('user_id', '=', $user_id)
            //                 ->where('date', '=', $date)
            //                 ->get();
        }
        // dd($work_times);

        // foreach ($work_times as $items) {
        //     if (count($items) > 0) {
        //         $working_time = 0;
        //         foreach ($items as $work_time) {
        //             if (isset($work_time->working_time)) {
        //                 $working_time += $work_time->working_time;
        //             }
        //         }

        //         if ($working_time == 0) {
        //             $values[] = null;
        //         } else {
        //             $values[] = $working_time;
        //         }
        //     } else {
        //         $values[] = null;
        //     }
        // }
        
        return $work_times;
    }


    /**
     * チャートのひと月の稼働時間を取得
     */
    public function getChartMonthWorkTime($user_id, $year, $month) {
        $work_time = DB::table('month_prices')
                    ->leftJoin('projects', 'month_prices.project_id', '=', 'projects.id')
                    ->where('projects.user_id', '=', $user_id)
                    ->where('month_prices.date', '=', $year.'/'.$month)
                    ->sum('month_prices.working_time');

        return $work_time;
    }


    /**
     * term_idを渡すと期を返却
     */
    public function getTermName($term_id) {
        $term = DB::table('terms')
                    ->select('term_name')
                    ->where('id', '=', $term_id)
                    ->first();

        return $term->term_name;
    }


    /**
     * 期を渡すとterm_idを返却
     */
    public function getTermId($term_name) {
        $term = DB::table('terms')
                    ->select('id')
                    ->where('term_name', '=', $term_name)
                    ->first();

        return $term->id;
    }


    /**
     * 期の年月を年と月に分割
     */
    public function termDatesToYearMonths($term_dates) {
        foreach ($term_dates as $term_date) {
            $year_month = explode('/', $term_date);
            $term_year_months[] = $year_month;
        }

        return $term_year_months;
    }


    /**
     * 時間を小数に変換する
     */
    public function convertTimeIntoFloat($time) {
        $hour_minute = explode(':', $time);
        $hour = $hour_minute[0];
        $minute = $hour_minute[1];
        $minute_float = round((int) $minute / 60, 1);

        return $hour + $minute_float;
    }


    /**
     * 年月を期に変換する
     */
    public function convertYearMonthIntoTeam($date) {
        $year_month = explode('/', $date);
        $year = (int) $year_month[0];
        $month = (int) $year_month[1];
        $term_h = 0;

        if ($month == 1 || $month == 2 || $month == 3) {
            $year -= 1;
            $term_h = 2;
        } else if ($month == 10 || $month == 11 || $month == 12) {
            $term_h = 2;
        } else {
            $term_h = 1;
        }

        $difference = $year - 2020;
        $term_y = 26 + $difference;

        return $term_y.'Y'.$term_h.'H';
    }


    /**
     * 期を年月に変換する
     */
    public function convertTeamIntoYearMonth($term) {
        $term_y_h = explode('Y', $term);
        $term_y = $term_y_h[0];
        $term_h = explode('H', $term_y_h[1])[0];
        $difference = $term_y - 26;
        $year = 2020 + $difference;
        $dates = array();

        switch ($term_h) {
            case 1:
                for ($i=4; $i<=9; $i++) {
                    $dates[] = $year.'/'.$i;
                }
                break;

            case 2:
                for ($i=10; $i<=12; $i++) {
                    $dates[] = $year.'/'.$i;
                }

                $year += 1;
                for ($i=1; $i<=3; $i++) {
                    $dates[] = $year.'/'.$i;
                }
                break;
        }

        return $dates;
    }
}
