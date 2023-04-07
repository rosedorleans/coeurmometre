
let projet = window.location.pathname.split("index.php")[0].split('/')[window.location.pathname.split("index.php")[0].split('/').length - 2]
let serverURL = window.location.protocol + "//" + window.location.host + window.location.pathname.split("index.php")[0].split(projet)[0] +"/" ;
console.log('serverURL:', serverURL)

$(document).ready(async function () {
    $('#blockedPage').removeClass('hidden')

    await loadFilters()
    await loadTable()
    stopSpinner()

    $('#category_select, #user_select').off()
    $('#category_select, #user_select').on('change', async function() {
        $('#blockedPage').removeClass('hidden')

        let value = $(this).val()
        if(value){
            let category = $('#category_select').val()
            let user = $('#user_select').val()
            console.log('category:', category)
            console.log('user:', user)

            await loadTable(category, user)
            stopSpinner()
        } else {
            await loadTable()
            stopSpinner()
        }
    })

    
})

async function loadTable(category=null, user=null){
    let url = 'http://localhost/ECV/coeurmometre/index.php/value/getAllValue?category='+category+'&user='+user
    console.log('url:', url)
    
    $.ajax({
        url : url,
        type : 'GET',
        success : async function(response){
            console.log('response.data:', response.data)
            $('table thead').removeClass('hidden')
            $('table tbody').empty()
            if(response.data){
                let T_result_sorted = response.data.sort(function(a,b){
                    return new Date(b.date) - new Date(a.date);
                });

                $.each(T_result_sorted, function(key, result) {
                    let date = new Date(result.date)
                    date = getRightFormatDate(date, "french", true)

                    let userName = ""
                    if(result.user){
                        userName = result.user
                    }

                    $('table tbody').append(
                        '<tr>'+
                            '<td>'+result.value+'</td>'+
                            '<td>'+result.category+'</td>'+
                            '<td>'+date+'</td>'+
                            '<td><input type="text" class="updateValue_js" value="'+userName+'" data-id="'+result.id+'"></td>'+
                        '</tr>'
                    )
                })
            } else {
                $('table thead').addClass('hidden')
                $('table tbody').append('<tr><td>Aucun resultat</td></tr>')
            }

            $('.updateValue_js').off("change")
            $('.updateValue_js').on("change", async function(){
                $('#blockedPage').removeClass('hidden')
                console.log($(this).data('id'))
                let T_data = {
                    "id": $(this).data('id'),
                    "user": $(this).val(),
                }

                console.log('T_data:', T_data)

                $.ajax({
                    url : 'http://localhost/ECV/coeurmometre/index.php/value/',
                    type : 'PUT',
                    data: T_data,
                    success : async function(response){
                        console.log(response.data)

                        await loadFilters()
                        await loadTable()
                        stopSpinner()
                    }
                })
            })
        }
    })
}

function loadFilters(){
    $('#category_select, #user_select').empty()

    $.ajax({
        url : 'http://localhost/ECV/coeurmometre/index.php/value/getDistinctCategory',
        type : 'GET',
        success : function(response){
            console.log(response.data)
            $('#category_select').append(
                '<option selected="true" value="null">Toutes les catégories</option>')

            $.each(response.data, function(key, category) {
                $('#category_select').append(
                    '<option class="category-option" value="'+category.category+'">'+category.category+'</option>'
                )
            })
        }
    })
    $.ajax({
        url : 'http://localhost/ECV/coeurmometre/index.php/value/getDistinctUser',
        type : 'GET',
        success : function(response){
            console.log(response.data)
            $('#user_select').append(
                '<option selected="true" value="null">Tous les utilisateurs</option>')

            $.each(response.data, function(key, user) {
                if(user.user){
                    $('#user_select').append(
                        '<option class="user-option" value="'+user.user+'">'+user.user+'</option>'
                    )
                }
            })
        }
    })
}

function stopSpinner(){
    setTimeout(function() {
        $('#blockedPage').addClass('hidden')
    }, 1000);
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
        if(minutes.toString() == "00"){
            minutes = ""
        }

        time = " " + hours + "h" + minutes

    }
    date = day + "/" + month + "/" + year + time;
    return date;
}

