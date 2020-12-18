
function global_load_block(url,idblock)
{
    $.ajax({
        url: url,
        cache: false,
        success: function(html){
            $(idblock).html(html);
        },
        error: function(jqXHR, exception) {
            alerts('error', 'Ошибка', 'Ошибка подключения, пожалуйтса попробуйте чуть позже');
        }
    });
}
