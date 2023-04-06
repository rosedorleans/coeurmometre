
let projet = window.location.pathname.split("index.php")[0].split('/')[window.location.pathname.split("index.php")[0].split('/').length - 2]
let serverURL = window.location.protocol + "//" + window.location.host + window.location.pathname.split("index.php")[0].split(projet)[0] +"/" ;
console.log('serverURL:', serverURL)

$(document).ready(async function () {
    await $.ajax({
        url : 'http://localhost/ECV/coeurmometre/index.php/value/getDistinctCategory',
        type : 'GET',
        success : function(response){
            console.log(response.data)
            $.each(response.data, function(key, category) {
                $('#category_select').append(
                    '<option class="category-option" value="'+category.category+'">'+category.category+'</option>'
                )
            })
        }
    })
    await $.ajax({
        url : 'http://localhost/ECV/coeurmometre/index.php/value/getDistinctUser',
        type : 'GET',
        success : function(response){
            console.log(response.data)
            $.each(response.data, function(key, user) {
                $('#user_select').append(
                    '<option class="user-option" value="'+user.user+'">'+user.user+'</option>'
                )
            })
        }
    })

    await loadTable()

    $('#category_select, #user_select').off()
    $('#category_select, #user_select').on('change', function() {
        let value = $(this).val()
        if(value){
            let category = $('#category_select').val()
            let user = $('#user_select').val()
            console.log('category:', category)
            console.log('user:', user)

            loadTable(category, user)
        } else {
            loadTable()
        }
    })
})

function loadTable(category=null, user=null){
    let url = 'http://localhost/ECV/coeurmometre/index.php/value/getAllValue?category='+category+'&user='+user
    console.log('url:', url)
    
    $.ajax({
        url : url,
        type : 'GET',
        success : function(response){
            console.log('response.data:', response.data)
            $('table thead').removeClass('hidden')
            $('table tbody').empty()
            if(response.data){
                console.log('ok')
                $.each(response.data, function(key, result) {
                    let date = new Date(result.date)
                    date = getRightFormatDate(date, "french", true)

                    $('table tbody').append(
                        '<tr>'+
                            '<td>'+result.value+'</td>'+
                            '<td>'+result.category+'</td>'+
                            '<td>'+date+'</td>'+
                            '<td>'+result.user+'</td>'+
                        '</tr>'
                    )
                })
            } else {
                console.log('ok2')
                $('table thead').addClass('hidden')
                $('table tbody').append('<tr><td>Aucun resultat</td></tr>')
            }
        }
    })
}

function getRightFormatDate(dateObject, purpose, isTime=false) {
    var d = new Date(dateObject);
    var day = d.getDate();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();
    
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }
    
    let time = "";
    if(isTime){
        let hours = d.getHours();
        let minutes = d.getMinutes();
        if(minutes.toString().length == 1){
            minutes = "0" + minutes
        }

        time = " " + hours + "h" + minutes

    }
    date = day + "/" + month + "/" + year + time;
    return date;
}

