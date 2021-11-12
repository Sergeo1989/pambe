/** OUR JS FILE */

(function() {
    'use strict';

    $(document).on('click', '#exp_btn_add',function(event) {
        event.preventDefault();
        var btn = $(this);
        var url  = $('#professional_ajax_url').val();
        var data = {};
        data['action'] = 'add_experience';
        data['ajax'] = 1;
        data['rand'] = new Date().getTime();
        data['professional_id'] = $('#professional_id').val();
        data['title'] = $('#exp_title').val();
        data['place'] = $('#exp_place').val();
        data['date_start'] = $('#exp_date_start').val();
        data['date_end'] = $('#exp_date_end').val();
        data['description'] = $('#exp_description').val();
        btn.text('chargement...');
        
        $.ajax({
            type: 'POST',
            url: url,
            data: data, 
            dataType:"json",
            success: function(data) {
                if (data.status === true) {
                   $('#exp_list').append(
                    '<li class="list-group-item d-flex justify-content-between align-items-center">'+
                        '<span> <b>Titre :</b> '+ data.value.title +' <br/> <b>Lieu :</b> '+ data.value.place +' </span>'+
                        '<span> <b>Date de début :</b> '+ data.value.date_start +' <br/> <b>Date de fin :</b> '+ data.value.date_end +' </span>'+
                        '<span style="color:red;" class="ml-1"><i class="far fa-trash-alt"></i></span>'+
                    '</li>'
                    );

                    setTimeout(function(){
                        $("#exp_modal").modal('hide');
                    }, 250);
    
                    $(".modal-backdrop").removeClass('show').hide();
                } else {
                    $('#exp_error').html(data.message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>').addClass('alert-danger show').fadeIn().trigger('change');
                }
                btn.text('valider');
            },
            complete: function() {
                btn.text('valider');
            },
            error: function(){
                alert('veuillez ré-essayer plutard !');
            }
        });
    });

    $(document).on('click', '#qual_btn_add',function(event) {
        event.preventDefault();
        var btn = $(this);
        var url  = $('#professional_ajax_url').val();
        var data = {};
        data['action'] = 'add_qualification';
        data['ajax'] = 1;
        data['rand'] = new Date().getTime();
        data['professional_id'] = $('#professional_id').val();
        data['title'] = $('#qual_title').val();
        data['place'] = $('#qual_place').val();
        data['date_start'] = $('#qual_date_start').val();
        data['date_end'] = $('#qual_date_end').val();
        data['description'] = $('#qual_description').val();
        btn.text('chargement...');
        
        $.ajax({
            type: 'POST',
            url: url,
            data: data, 
            dataType:"json",
            success: function(data) {
                if (data.status === true) {
                   $('#qual_list').append(
                    '<li class="list-group-item d-flex justify-content-between align-items-center">'+
                        '<span> <b>Titre :</b> '+ data.value.title +' <br/> <b>Lieu :</b> '+ data.value.place +' </span>'+
                        '<span> <b>Date de début :</b> '+ data.value.date_start +' <br/> <b>Date de fin :</b> '+ data.value.date_end +' </span>'+
                        '<span style="color:red;" class="ml-1"><i class="far fa-trash-alt"></i></span>'+
                    '</li>'
                    );

                    setTimeout(function(){
                        $("#qual_modal").modal('hide');
                    }, 250);
    
                    $(".modal-backdrop").removeClass('show').hide();
                } else {
                    $('#qual_error').html(data.message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>').addClass('alert-danger show').fadeIn()
                }
                
                btn.text('valider');
            },
            complete: function() {
                btn.text('valider');
            },
            error: function(){
                alert('veuillez ré-essayer plutard !');
            }
        });
    })

})();