$(function() {
    changeFormActionByUserSelected('select_user', 'form_user_term');
});

function changeFormActionByUserSelected(select_id, form_id) {
    $('#'+select_id).change(function() {
        const action = $('#'+form_id).attr('action');
        console.log();
    });
}