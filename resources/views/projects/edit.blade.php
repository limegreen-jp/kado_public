@extends('layouts.app')


@section('link_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/lib/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
@endsection


@section('script_src')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/Price.js') }}"></script>
@endsection


@section('content')
<div class="card">
    <form method="post" action="{{ route('project.update', ['project_id' => $project->project_id]) }}">
        @csrf
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <dl class="project-info">
                <dt class="project-info-title"><label for="client_name">クライアント名</label></dt>
                <dd class="project-info-body"><input type="text" id="client_name" name="client_name" class="form-control-full" value="{{ $project->client_name }}"></dd>
            </dl>
            <dl class="project-info">
                <dt class="project-info-title requied"><label for="project_name">案件名</label></dt>
                <dd class="project-info-body"><input type="text" id="project_name" name="project_name" class="form-control-full" value="{{ $project->project_name }}"></dd>
            </dl>
            <dl class="project-info">
                <dt class="project-info-title"><label for="skill_id">スキル</label></dt>
                <dd class="project-info-body">
                    <div class="select-wrap">
                        <select id="skill_id" name="skill_id" class="select">
                            <option value=""class="option-hidden" hidden>スキルを選択</option>
                            @foreach ($skills as $skill)
                                @if ($skill->id == $project->skill_id)
                                    <option value="{{ $skill->id }}" selected>{{ $skill->skill_name }}</option>
                                @else
                                    <option value="{{ $skill->id }}">{{ $skill->skill_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </dd>
            </dl>
            <dl class="project-info">
                <dt class="project-info-title"><label for="level_id">レベル</label></dt>
                <dd class="project-info-body">
                    <div class="select-wrap">
                        <select id="level_id" name="level_id" class="select" disabled>
                            <option value=""class="option-hidden" hidden>レベルを選択</option>
                            @foreach ($levels as $level)
                                @if ($level->id == $project->level_id)
                                    <option value="{{ $level->id }}" selected>{{ $level->level_name }}</option>
                                @else
                                    <option value="{{ $level->id }}">{{ $level->level_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </dd>
            </dl>
            <dl class="project-info">
                <dt class="project-info-title">人時単価</dt>
                <dd id="hour_unit_price"></dd>
            </dl>
            <dl class="project-info">
                <dt class="project-info-title">人月単価</dt>
                <dd id="month_unit_price"></dd>
            </dl>
            <dl class="project-info">
                <dd class="project-info-body">
                    <div class="table-scroll">
                        <table class="project-info-table">
                            <tbody>
                                <tr>
                                    <th class="table-title">年月</th>
                                    @foreach ($dates as $date)
                                        <td>{{ $date }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th>稼働時間</th>
                                    @foreach ($date_working_times as $date => $date_working_time)
                                            <td><input type="number" name="working_times[{{ $date }}]" id="" class="working_time_month" value="{{ $date_working_time }}" data-date="{{ $date }}" min=0></td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th>金額</th>
                                    @foreach ($dates as $date)
                                        <td class="table-price">
                                            <p class="table-price-text" data-date-text="{{ $date }}"></p>
                                            <input type="hidden" id="" name="prices[{{ $date }}]" class="table-price-value" data-date-value="{{ $date }}">
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </dd>
            </dl>
            <dl class="project-info">
                <dt class="project-info-title"><label for="user_id">担当者</label></dt>
                <dd class="project-info-body">
                    <div class="select-wrap">
                        <select id="user_id" name="user_id" class="select">
                            <option value="">担当者を選択</option>
                            @foreach ($users as $user)
                                @if ($user->id == $project->user_id)
                                    <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                @else
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </dd>
            </dl>
            <dl class="project-info">
                <dt class="project-info-title"><label for="description">詳細</label></dt>
                <dd class="project-info-body"><textarea id="description" name="description" class="form-control-full" rows="10">{{ $project->description }}</textarea></dd>
            </dl>
        </div>
        <div class="card-footer">
            <div class="align-center">
                <button type="submit" class="btn btn-primary">送信</button>
            </div>
        </div>
    </form>
</div>
@endsection


@section('script')
<script>
    $(function() {
        axios.get('/api/project/skill_level')
            .then(function(response) {
                console.log(response.data);

                let price_ins = new Price;

                price_ins.skillId = $('#skill_id').val();
                price_ins.levelId = $('#level_id').val();
                price_ins.workingTime = $('#working_time').val();
                price_ins.abledLevelSelect();
                price_ins.disabledLevelOption(response.data, price_ins.skillId);
                price_ins.monthUnitPriceFuns(response.data);
                price_ins.hourUnitPriceFuns();
                price_ins.priceFuns();
                price_ins.monthPriceFunsEach();
                
                $('#skill_id').change(function() {
                    let skill_id = $(this).val();
                    price_ins.skillId = skill_id;
                    price_ins.abledLevelSelect();
                    price_ins.disabledLevelOption(response.data, price_ins.skillId);
                    price_ins.monthUnitPriceFuns(response.data);
                    price_ins.hourUnitPriceFuns();
                    price_ins.priceFuns();
                    price_ins.monthPriceFunsEach();
                });
                $('#level_id').change(function() {
                    let level_id = $(this).val();
                    price_ins.levelId = level_id;
                    price_ins.monthUnitPriceFuns(response.data);
                    price_ins.hourUnitPriceFuns();
                    price_ins.priceFuns();
                    price_ins.monthPriceFunsEach();
                });

                $('#working_time').on('input', function() {
                    price_ins.workingTime = $(this).val();
                    price_ins.hourUnitPriceFuns();
                    price_ins.priceFuns();
                });

                $('.working_time_month').on('input', function() {
                    price_ins.month_working_time = $(this).val();
                    price_ins.monthPriceFuns($(this).attr('data-date'));
                });
        });

    });
</script>
@endsection


@section('style')
<style>
    .table {
        max-width: 100%;
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }
    .table-title {
        width: 100px;
    }
    .working_time_month {
        width: 100%;
    }
</style>
@endsection