

let projet = window.location.pathname.split("index.php")[0].split('/')[window.location.pathname.split("index.php")[0].split('/').length - 2]
let serverURL = window.location.protocol + "//" + window.location.host + window.location.pathname.split("index.php")[0].split(projet)[0] +"/" ;
console.log('serverURL:', serverURL)

$(document).ready( function () {

    // $.ajax({
    //     url : 'http://localhost/ECV/coeurmometre/index.php/value/getDistinctCategory',
    //     type : 'GET',
    //     success : function(response){
    //         console.log(response.data)
            

    //     }
    // })

    $.ajax({
        url : 'http://localhost/ECV/coeurmometre/index.php/value/',
        type : 'GET',
        success : function(response){
            console.log(response.data)
            $('#showBetPage .votes-list').empty()

            if(response.data){
                $.each(response.data, function(key, result) {
                    console.log('result:', result)

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
            }
        }
    })
})

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

