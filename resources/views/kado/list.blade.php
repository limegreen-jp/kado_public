@extends('layouts.app')

@section('script_src')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <x-select-user-and-date :user-id="$user_id" :term-id="$term_id" />
    </div>
</div>
<div class="card">
    <div class="card-header">
        <div class="card-header-dates">
            <p><a href="{{ route('kado.list', ['user_id' => $user_id, 'term_id' => $terms_around['term_prev_id']]) }}" class="link">< {{ $terms_around['term_prev_name'] }}</a></p>
            <p class="card-header-current-date">{{ $term->term_name }}</p>
            <p><a href="{{ route('kado.list', ['user_id' => $user_id, 'term_id' => $terms_around['term_next_id']]) }}" class="link">{{ $terms_around['term_next_name'] }} ></a></p>
        </div>
    </div>
    <div class="card-body">
        <p class="user-name">{{ $user->name }}</p>

        <canvas id="myChart"></canvas>

        <table class="table-chart-dates">
            <tr>
                @foreach ($terms_around['term_year_months'] as $term_year_months)
                    <td><a href="{{ route('kado.list_detail', [
                    'user_id' => $user_id, 
                    'year' => $term_year_months[0],
                    'month' => $term_year_months[1]
                    ]) }}" class="link">{{ $term_year_months[0] }}/{{ $term_year_months[1] }}</a></td>
                @endforeach
            </tr>
        </table>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        axios.get('/api/chart/'+{{$user_id}}+'/'+{{$term_id}})
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
                            data: response.data.work_time,
                            borderColor: "rgb(154, 162, 235)",
                            yAxisID: "y-axis-1",
                        }, {
                            label: '社売',
                            data: response.data.syauri,
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