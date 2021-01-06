@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <p><a href="{{ route('project.create') }}"><button class="btn btn-primary">追加</button></a></p>
        <table class="tablesorter js-tablesorter">
            <thead>
                <tr>
                    <th>クライアント</th>
                    <th>案件</th>
                    <th>スキル</th>
                    <th>レベル</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->client_name }}</td>
                        <td><a href="{{ route('project.show', ['project_id' => $project->project_id]) }}" class="link">{{ $project->project_name }}</a></td>
                        <td>{{ $project->skill_name }}</td>
                        <td>{{ $project->level_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection