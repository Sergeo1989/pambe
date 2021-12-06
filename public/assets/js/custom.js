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
                    '<li id="experience-' + data.value.id + '" class="list-group-item d-flex justify-content-between align-items-center">'+
                        '<span> <b>Titre :</b> '+ data.value.title +' <br/> <b>Lieu :</b> '+ data.value.place +' </span>'+
                        '<span> <b>Date de début :</b> '+ data.value.start_date +' <br/> <b>Date de fin :</b> '+ data.value.end_date +' </span>'+
                        '<span class="ml-1"><a style="color:red;" data-id="' + data.value.id + '" href="javascript:"><i class="far fa-trash-alt"></i></a></span>'+
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
                btn.text('valider');
                alert('veuillez ré-essayer plutard !');
            }
        });
    });

    $(document).on('click', '#exp_list li a', function(event) {
        var experience_id = $(this).data('id');
        var url  = $('#professional_ajax_url').val();
        var data = {};
        data['action'] = 'remove_experience';
        data['experience_id'] = experience_id;
        data['ajax'] = 1;
        data['rand'] = new Date().getTime();
        var posting = $.post(url, data);
        posting.always(function(data) {
            if(data.status === true) {
                $('#experience-' + experience_id).fadeOut(200, function(){
                    $('#experience-' + experience_id).remove();
                });
            }else{
                alert("Une erreur s'est produite.")
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
                    '<li id="qualification-' + data.value.id + '" class="list-group-item d-flex justify-content-between align-items-center">'+
                        '<span> <b>Titre :</b> '+ data.value.title +' <br/> <b>Lieu :</b> '+ data.value.place +' </span>'+
                        '<span> <b>Date de début :</b> '+ data.value.start_date +' <br/> <b>Date de fin :</b> '+ data.value.end_date +' </span>'+
                        '<span class="ml-1"><a style="color:red;" data-id="' + data.value.id + '" href="javascript:"><i class="far fa-trash-alt"></i></a></span>'+
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
    });

    $(document).on('click', '#qual_list li a', function(event) {
        var qualification_id = $(this).data('id');
        var url  = $('#professional_ajax_url').val();
        var data = {};
        data['action'] = 'remove_qualification';
        data['qualification_id'] = qualification_id;
        data['ajax'] = 1;
        data['rand'] = new Date().getTime();
        var posting = $.post(url, data);
        posting.always(function(data) {
            if(data.status === true) {
                $('#qualification-' + qualification_id).fadeOut(200, function(){
                    $('#qualification-' + qualification_id).remove();
                });
            }else{
                alert("Une erreur s'est produite.")
            }
        });
    });

})();