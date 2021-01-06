@extends('layouts.app')


@section('link_css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
@endsection


@section('script_src')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
@endsection


@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-header-menu">
            <a class="card-header-menu-inner" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <div class="dropdown-menu">
                <a href="{{ route('project.edit', ['project_id' => $project->project_id]) }}" class="dropdown-item">編集</a>
                <form method="post" action="{{ route('project.destroy', ['project_id' => $project->project_id]) }}" id="delete_{{ $project->project_id}}">
                    @csrf
                    <a href="#" class="dropdown-item" data-id="{{ $project->project_id }}" onclick="deletePost(this);" >削除</a>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <dl class="project-info">
            <dt class="project-info-title"><label for="client_name">クライアント名</label></dt>
            <dd class="project-info-body">{{ $project->client_name }}</dd>
        </dl>
        <dl class="project-info">
            <dt class="project-info-title"><label for="project_name">案件名</label></dt>
            <dd class="project-info-body">{{ $project->project_name }}</dd>
        </dl>
        <dl class="project-info">
            <dt class="project-info-title"><label for="skill_id">スキル</label></dt>
            <dd class="project-info-body">{{ $project->skill_name }}</dd>
        </dl>
        <dl class="project-info">
            <dt class="project-info-title"><label for="level_id">レベル</label></dt>
            <dd class="project-info-body">{{ $project->level_name }}</dd>
        </dl>
        <dl class="project-info">
            <dd class="project-info-body">
                <div class="table-scroll">
                    <table class="project-info-table">
                        <tbody>
                            <tr>
                                <th class="table-title">年月</th>
                                @foreach ($prices as $price)
                                    <td>{{ $price->date }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <th>稼働時間</th>
                                @foreach ($prices as $price)
                                    <td>{{ $price->working_time }}h</td>
                                @endforeach
                            </tr>
                            <tr>
                                <th>金額</th>
                                @foreach ($prices as $price)
                                    <td>{{ $price->price }}万</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </dd>
        </dl>
        <dl class="project-info">
            <dt class="project-info-title"><label for="user_id">担当者</label></dt>
            <dd class="project-info-body">{{ $project->name }}</dd>
        </dl>
        <dl class="project-info">
            <dt class="project-info-title"><label for="description">詳細</label></dt>
            <dd class="project-info-body">{!! nl2br(e($project->description)) !!}</dd>
        </dl>
    </div>
</div>
@endsection


@section('script')
<script>
    function deletePost(e) {
        'use strict';
        if (confirm('本当に削除していいですか?')) {
        document.getElementById('delete_' + e.dataset.id).submit();
        }
    }
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