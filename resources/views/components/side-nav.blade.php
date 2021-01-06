<nav class="l-nav">
    <ul>
        <li class="nav-title">予定稼働</li>
        <li><a href="{{ route('kado.list', [
            'user_id' => $user_id,
            'term_id' => $current_term_id
        ]) }}" class="nav-item">今期の予定稼働</a></li>
        <li><a href="{{ route('kado.list_all', [
            'year' => $current_year,
            'month' => $current_month
        ]) }}" class="nav-item">メンバーの予定稼働</a></li>
        <li class="nav-title">案件</li>
        <li><a href="{{ route('project.index') }}" class="nav-item">案件一覧（未アサイン）</a></li>
        <li><a href="{{ route('project.create') }}" class="nav-item">案件登録</a></li>
    </ul>
</nav>
