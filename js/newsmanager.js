$(document).ready(function () {
    console.log(window.location.href);
    var url = window.location.href;
    url = url.slice(0, -11);
    $("#checkAll").click(function () {
        if (role == "moderateur") {
            $.ajax({
                url: url + 'ajoutmessage',
                type: 'POST', // Le type de la requête HTTP, ici devenu POST
                data: 'message=' + $(".add-todo").val(),
                success: function (data) {
                    createrow($(".add-todo").val(), data[0][0]["id_info"], data[0][0]["auteur"], data[0][0]["date"]);
                    $(".add-todo").val("");
                }
            });
        } else {
            $.ajax({
                url: url + 'ajoutmessageattente',
                type: 'POST', // Le type de la requête HTTP, ici devenu POST
                data: 'message=' + $(".add-todo").val(),
                success: function (data) {
                    createrowattente($(".add-todo").val(), data[0][0]["id_info"], data[0][0]["auteur"], data[0][0]["date"]);
                    $(".add-todo").val("");
                }
            });
        }
    });
    $(document).on("click", ".valid-item", function () {
        console.log("greg");
        $(this).closest("li").find(".message").attr("contenteditable", false);
        console.log($(this).closest("li").find(".message").html());
        var li = $(this).closest("li");
        $.ajax({
            url: url + 'valider',
            type: 'POST', // Le type de la requête HTTP, ici devenu POST
            data: {
                'id': $(this).closest("li").attr("data-id")
            },
            success: function (data) {
                createrow(data[0][0]["contenu"], data[0][0]["id_info"], data[0][0]["auteur"], data[0][0]["date"]);
                li.remove();
            }
        });
    });
    $(document).on("click", ".remove-item", function () {
        console.log($(this).closest("li").attr("data-id"));
        var li = $(this).closest("li");
        $.ajax({
            url: url + 'supprimermessage',
            type: 'POST', // Le type de la requête HTTP, ici devenu POST
            data: 'id=' + $(this).closest("li").attr("data-id"),
            success: function (data) {
                li.remove();
            }
        });
    });
    $(document).on("click", ".modify-item", function () {
        $(this).closest("li").find(".message").attr("contenteditable", true);
        $(this).closest("li").append("<button class='send-modify'>Envoyer</button>")
        $(this).hide();
        /*
        $.ajax({
            url: url + 'supprimermessage',
            type: 'POST', // Le type de la requête HTTP, ici devenu POST
            data: 'id=' + $(this).closest("li").attr("data-id"),
            success: function (data) {
                li.remove();
            }
        });*/
    });
    $(document).on("click", ".send-modify", function () {
        $(this).closest("li").find(".message").attr("contenteditable", false);
        console.log($(this).closest("li").find(".message").html());
        var li = $(this).closest("li");
        $.ajax({
            url: url + 'modifiermessage',
            type: 'POST', // Le type de la requête HTTP, ici devenu POST
            data: {
                'message': $(this).closest("li").find(".message").html(),
                'id': $(this).closest("li").attr("data-id")
            },
            success: function (data) {
                li.find(".modify-item").show();
                li.find(".send-modify").hide();
            }
        });
    });
});
function createrowattente(contenu, id, auteur,date) {
    $("#sortable").append("<li data-id=" + id + "><span class='message'>" + contenu + "</span> - " + auteur +" - "+ date +"</p><button class='valid-item btn btn-default btn-xs pull-right'><span class='glyphicon glyphicon-ok'></span></button></li>");
}
function createrow(contenu, id, auteur, date) {
    $("#done-items").append("<li data-id=" + id + "><span class='message'>" + contenu + "</span> - " + auteur + " - " + date + "</p><button class='remove-item btn btn-default btn-xs pull-right'><span class='glyphicon glyphicon-remove'></span></button><button class='modify-item btn btn-default btn-xs pull-right'><span class='glyphicon glyphicon-pencil'></span></button></li>");
}