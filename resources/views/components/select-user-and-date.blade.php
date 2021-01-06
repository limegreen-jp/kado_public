<div>
    <form id="form_user_term" action="{{ route('kado.list', [
        'user_id' => $user_id, 
        'term_id' => $term_id
    ]) }}" method="get">
        <div class="select-wrap">
            <select name="" id="select_user" class="select">
                @foreach ($users as $user_item)
                    @if ($user_item->id == $user_id)
                        <option value="{{ $user_item->id }}" selected>{{ $user_item->name }}</option>
                    @else
                        <option value="{{ $user_item->id }}">{{ $user_item->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="select-wrap">
            <select name="" id="select_term" class="select">
                @foreach ($terms as $term_item)
                    @if ($term_item->id == $term_id)
                        <option value="{{ $term_item->id }}" selected>{{ $term_item->term_name }}</option>
                    @else
                        <option value="{{ $term_item->id }}">{{ $term_item->term_name }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">移動</button>
    </form>
</div>

<script>
$(function() {
    changeFormActionByUserSelected('select_user', 'form_user_term');
    changeFormActionByUserSelected('select_term', 'form_user_term');
});

function changeFormActionByUserSelected(select_id, form_id) {
    $('#'+select_id).change(function() {
        let action = $('#'+form_id).attr('action');
        let action_split = action.split('kado');
        let user_term_split = action_split[1].split('/');
        
        switch (select_id) {
            case 'select_user':
                user_term_split[1] = $(this).val();
                break;

            case 'select_term':
                user_term_split[2] = $(this).val();
                break;
        }

        user_term_split = user_term_split.join('/');
        action_split[1] = user_term_split;
        action = action_split.join('kado');
        $('#'+form_id).attr('action', action);
    });
}
</script>