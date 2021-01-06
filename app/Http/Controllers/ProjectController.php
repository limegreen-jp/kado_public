<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProjectPost;
use App\Models\Project;
use App\Models\MonthPrice;
use App\Http\Controllers\KadoController;

define('WEBHOOK_URL', '');

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = DB::table('projects')
                        ->leftJoin('skills', 'projects.skill_id', '=', 'skills.id')
                        ->leftJoin('levels', 'projects.level_id', '=', 'levels.id')
                        ->select('projects.*', 
                                    'skills.*', 
                                    'levels.*', 
                                    'skills.id as skill_id',
                                    'levels.id as level_id',
                                    'projects.id as project_id')
                        ->whereNull('user_id')
                        ->orderBy('project_id', 'desc')
                        ->get();
        // dd($projects);

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = DB::table('users')->get();
        $skills = DB::table('skills')->get();
        $levels = DB::table('levels')->get();
        $date = date("Y-n-01");
        for ($i=0; $i<12; $i++) {
            $dates[] = date('Y/n', strtotime($i." month". $date));
        }

        return view('projects.create', compact('users', 'skills', 'levels', 'dates'));
    }

    /**
     * 
     */
    public function levelSkillApi() {
        $level_skills = DB::table('level_skills')
                            ->leftJoin('skills', 'level_skills.skill_id', '=', 'skills.id')
                            ->leftJoin('levels', 'level_skills.level_id', '=', 'levels.id')
                            ->select('level_skills.*', 
                                    'levels.*', 
                                    'skills.*', 
                                    'level_skills.id as level_skill_id',
                                    'levels.id as level_id',
                                    'skills.id as skill_id')
                            ->orderBy('level_skill_id')
                            ->get();

        $skill_id_prev = 1;
        $level_ids = array();
        foreach ($level_skills as $level_skill) {
            $level_skill_id = $level_skill->level_skill_id;
            $skill_id = $level_skill->skill_id;
            $level_id = $level_skill->level_id;
            $unit_price = $level_skill->unit_price;

            if ($skill_id == $skill_id_prev) {
                $level_ids[$level_id] = $unit_price;

            } else {
                $skill_ids[$skill_id_prev] = $level_ids;
                $level_ids = array();
                $level_ids[$level_id] = $unit_price;
                $skill_id_prev = $skill_id;
            }
        }
        $skill_ids[$skill_id_prev] = $level_ids;

        return response()->json($skill_ids);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectPost $request)
    {
        $project = new Project;

        $project->client_name = $request->input('client_name');
        $project->project_name = $request->input('project_name');
        $project->skill_id = $request->input('skill_id');
        $project->level_id = $request->input('level_id');
        $project->user_id = $request->input('user_id');
        $project->description = $request->input('description');
        $project->save();

        $kado_controller = new KadoController;
        foreach ($request->input('working_times') as $date => $working_time) {
            if (isset($working_time)) {
                $month_price = new MonthPrice;
                
                $month_price->project_id = $project->id;
                $month_price->date = $date;
                $month_price->term_id = $kado_controller->getTermId($kado_controller->convertYearMonthIntoTeam($date));
                $month_price->working_time = $working_time;
                $month_price->price = $request->input('prices')[$date];
                $month_price->save();
            }
        }

        $skill_name = DB::table('skills')
                        ->select('skill_name')
                        ->where('id', '=', $project->skill_id)
                        ->first();
                        
        $url = url('/')."/projects/show/".$project->id;
        $text = "案件が追加されました\n\n";
        $text .= "【案件名】".$project->project_name."\n";
        $text .= "【スキル】".$skill_name->skill_name."\n\n";
        $text .= $url;
        $this->post_message($text);

        return redirect('projects/index');
    }

    /**
     * Google Chatにメッセージを送信する
     */
    private function post_message($text)
    {
        $content = json_encode(['text' => $text]);

        $options = [
            'http'=> [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n"
                . "Content-Length: " . strlen($content) . "\r\n" ,
                'content' => $content
                ]
            ];
        $context = stream_context_create($options);
        $resultJson = file_get_contents(WEBHOOK_URL, FALSE, $context);

        if(strpos($http_response_header[0], '200')){
            return true;
        }
        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($project_id)
    {
        $project = DB::table('projects')
                        ->leftJoin('users', 'projects.user_id', '=', 'users.id')
                        ->leftJoin('skills', 'projects.skill_id', '=', 'skills.id')
                        ->leftJoin('levels', 'projects.level_id', '=', 'levels.id')
                        ->leftJoin('month_prices', 'projects.id', '=', 'project_id')
                        ->select('projects.*', 
                                    'users.*', 
                                    'skills.*', 
                                    'levels.*', 
                                    'month_prices.*', 
                                    'users.id as user_id',
                                    'skills.id as skill_id',
                                    'levels.id as level_id',
                                    'month_prices.id as month_price_id',
                                    'projects.id as project_id')
                        ->where('projects.id', '=', $project_id)
                        ->first();

        $prices = DB::table('month_prices')
                    ->where('month_prices.project_id', '=', $project_id)
                    ->get();
        // dd($prices);

        return view('projects.show', compact('project', 'prices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($project_id)
    {
        $project = DB::table('projects')
                        ->leftJoin('users', 'projects.user_id', '=', 'users.id')
                        ->leftJoin('skills', 'projects.skill_id', '=', 'skills.id')
                        ->leftJoin('levels', 'projects.level_id', '=', 'levels.id')
                        ->leftJoin('month_prices', 'projects.id', '=', 'project_id')
                        ->select('projects.*', 
                                    'users.*', 
                                    'skills.*', 
                                    'levels.*', 
                                    'month_prices.*', 
                                    'users.id as user_id',
                                    'skills.id as skill_id',
                                    'levels.id as level_id',
                                    'month_prices.id as month_price_id',
                                    'projects.id as project_id')
                        ->where('projects.id', '=', $project_id)
                        ->first();

        $prices = DB::table('month_prices')
                    ->where('month_prices.project_id', '=', $project_id)
                    ->get();

        // dd($prices);
        $skills = DB::table('skills')->get();
        $levels = DB::table('levels')->get();
        $users = DB::table('users')->get();
        $date = date("Y-n-01");
        for ($i=0; $i<12; $i++) {
            $next_month = date('Y/n', strtotime($i." month". $date));
            $dates[] = $next_month;

            $has_price_date = false;
            foreach ($prices as $price) {
                if ($price->date == $next_month) {
                    $date_working_times[$next_month] = $price->working_time;
                    $has_price_date = true;
                }
            }

            if (!$has_price_date) {
                $date_working_times[$next_month] = null;
            }
        }
        // dd($date_working_times);

        return view('projects.edit', compact('project', 'skills', 'levels', 'users', 'dates', 'date_working_times'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProjectPost $request, $project_id)
    {
        $project = Project::find($project_id);

        $project->client_name = $request->input('client_name');
        $project->project_name = $request->input('project_name');
        $project->skill_id = $request->input('skill_id');
        $project->level_id = $request->input('level_id');
        $project->user_id = $request->input('user_id');
        $project->description = $request->input('description');
        $project->save();

        $kado_controller = new KadoController;
        foreach ($request->input('working_times') as $date => $working_time) {
            if (isset($working_time)) {
                $month_price = MonthPrice::where('project_id', '=', $project->id)
                                        ->where('date', '=', $date)
                                        ->first();

                if (!isset($month_price)) {
                    $month_price = new MonthPrice;
                }
                
                $month_price->project_id = $project->id;
                $month_price->date = $date;
                $month_price->term_id = $kado_controller->getTermId($kado_controller->convertYearMonthIntoTeam($date));
                $month_price->working_time = $working_time;
                $month_price->price = $request->input('prices')[$date];
                $month_price->save();
            }
        }

        return redirect('projects/index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id)
    {
        $project = Project::find($project_id);
        $project->delete();

        return redirect('projects/index');
    }
}
