//config key

function pregString(val){
    var re = /[\w_@]+$/mg;
    return re.test(val)
}

$(".config_field").blur(function () {
    var val = $(this).val().trim()
    if(! pregString(val)){
        alert('只允许字母、数字、下划线')
    }
});
