$(document).ready(function(){

    $('[data-toggle="tooltip"]').tooltip();
    supprimerMessage();
});

var ajax_message = null;

$('.pseudo-input').on('keyup',function(){
    validerForm();
    supprimerMessage();
});

$('.message-input').on('keyup',function(){
    validerForm();
    supprimerMessage();
});

function validerForm(){
    if($('.pseudo-input').val().length >= 3 && $('.message-input').val().length >= 3){
        $('#message-submit').prop('disabled',false);
    }
    else {
        $('#message-submit').prop('disabled', true);
    }
}

$('#message-submit').on('click',function(){
    if(ajax_message === null){
        ajax_message = $.ajax({
            url: Routing.generate('message_ajouter'),
            type: 'POST',
            data: {
                'pseudo': $('.pseudo-input').val(),
                'corps': $('.message-input').val()
            },
            success: function(results){
                $('#data-message').html(results);
                $('.message-input').val('');
                // $('.pseudo-input').val('');
                supprimerMessage();
                ajax_message = null;
            }
        });
    }
});

function supprimerMessage() {
    $('.suppr-message').click(function () {
        var id = $(this).data('value');
        swal({
            title: "Voulez-vous supprimer ce message ?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Confirmer",
            cancelButtonText: "Annuler",
            closeOnConfirm: false,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                if(ajax_message === null){
                    ajax_message = $.ajax({
                        url: Routing.generate('message_supprimer',{'id': id}),
                        type: 'POST',
                        success: function(results){
                            $('#data-message').html(results);
                            supprimerMessage();
                            ajax_message = null;

                        }
                    });
                    swal("Supprimer!", "Le message à bien été supprimer", "success");
                }
            }
        });
    });
}
