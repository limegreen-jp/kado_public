@extends('layouts.app')

@section('script_src')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-header-dates">
            <p><a href="{{ route('kado.list_detail', [
                'user_id' => $user_id, 
                'year' => $year_months_around['prev_year'],
                'month' => $year_months_around['prev_month']
            ]) }}" class="link">< {{ $year_months_around['prev_year'] }}/{{ $year_months_around['prev_month'] }}</a></p>

            <p class="card-header-current-date">{{ $year }}/{{ $month }}</p>

            <p><a href="{{ route('kado.list_detail', [
                'user_id' => $user_id, 
                'year' => $year_months_around['next_year'],
                'month' => $year_months_around['next_month']
            ]) }}" class="link">{{ $year_months_around['next_year'] }}/{{ $year_months_around['next_month'] }} ></a></p>
        </div>
    </div>
    <div class="card-body">
        <p class="user-name">{{ $user->name }}</p>

        <canvas id="myChart"></canvas>

        <table class="table js-tablesorter">
            <thead>
                <tr>
                    <th>クライアント</th>
                    <th>案件名</th>
                    <th>金額</th>
                    <th>稼働時間</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->client_name }}</td>
                        <td><a href="{{ route('project.show', ['project_id' => $project->project_id]) }}" class="link">{{ $project->project_name }}</a></td>
                        <td>{{ $project->price }}</td>
                        <td>{{ $project->working_time }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">合計</th>
                    <th>{{ $projects_sum['projects_total_price'] }}</th>
                    <th>{{ $projects_sum['projects_total_working_time'] }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        axios.get('/api/chart/'+{{$user_id}}+'/'+{{$year}}+'/'+{{$month}})
            .then(function(response) {
                console.log(response);

                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [{{$year}}+'/'+{{$month}}],
                        datasets: response.data,
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                stacked: true, //積み上げ棒グラフにする設定
                                categoryPercentage: 0.4 //棒グラフの太さ
                            }],
                            yAxes: [{
                                stacked: true, //積み上げ棒グラフにする設定
                                ticks: {
                                    max: 200,
                                    min: 0,
                                },
                            }]
                        },
                        legend: {
                            display: false
                        },
                        tooltips:{
                            mode:'index' //マウスオーバー時に表示されるtooltip
                        }
                    }
                });
            });
    </script>
@endsection