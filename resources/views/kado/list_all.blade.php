@extends('layouts.app')

@section('script_src')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-header-dates">
            <p><a href="{{ route('kado.list_all', [
                'year' => $year_months_around['prev_year'],
                'month' => $year_months_around['prev_month']
            ]) }}" class="link">< {{ $year_months_around['prev_year'] }}/{{ $year_months_around['prev_month'] }}</a></p>

            <p class="card-header-current-date">{{ $year }}/{{ $month }}</p>

            <p><a href="{{ route('kado.list_all', [
                'year' => $year_months_around['next_year'],
                'month' => $year_months_around['next_month']
            ]) }}" class="link">{{ $year_months_around['next_year'] }}/{{ $year_months_around['next_month'] }} ></a></p>
        </div>
    </div>
    <div class="card-body">
        <canvas id="myChart"></canvas>

        <table class="table js-tablesorter">
            <thead>
                <tr>
                    <th>社員名</th>
                    <th>金額</th>
                    <th>稼働時間</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($all_user as $index => $user_item)
                    <tr>
                        <td><a href="{{ route('kado.list_detail', [
                        'user_id' => $user_item['user_id'], 
                        'year' => $year,
                        'month' => $month
                        ]) }}" class="link">{{ $user_item['user_name'] }}</a></td>
                        <td>{{ $user_item['projects_total_price'] }}</td>
                        <td>{{ $user_item['projects_total_working_time'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        axios.get('/api/chart/all/'+{{$year}}+'/'+{{$month}})
            .then(function(response) {
                console.log(response);

                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: response.data.labels,
                        datasets: [{
                            label: '稼働時間',
                            type: "line",
                            fill: false,
                            data: response.data.work_times,
                            borderColor: "rgb(154, 162, 235)",
                            yAxisID: "y-axis-1",
                        }, {
                            label: '社売',
                            data: response.data.syauris,
                            borderColor: "rgb(255, 99, 132)",
                            backgroundColor: "rgba(255, 99, 132, 0.2)",
                            yAxisID: "y-axis-2",
                        }]
                    },
                    options: {
                        tooltips: {
                            mode: 'nearest',
                            intersect: false,
                        },
                        responsive: true,
                        scales: {
                            yAxes: [{
                                id: "y-axis-1",
                                type: "linear",
                                position: "left",
                                ticks: {
                                    max: 200,
                                    min: 0,
                                },
                            }, {
                                id: "y-axis-2",
                                type: "linear",
                                position: "right",
                                ticks: {
                                    max: 200,
                                    min: 0,
                                },
                                gridLines: {
                                    drawOnChartArea: false,
                                },
                            }],
                        },
                    }
                });
            });
    </script>
@endsection