/** OUR JS FILE */

(function() {
    'use strict';
    $(window).on('load', function(){
        var url  = $('#professional_ajax_url').val();
        var data = {};
        data['action'] = 'get_messages';
        data['ajax'] = 1;
        data['rand'] = new Date().getTime();
        $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            startDate: moment().subtract(6, 'days'),
            endDate: moment(),
            locale: {
                "format": "MMMM DD, YYYY",
                "separator": " - ",
                "fromLabel": "De",
                "toLabel": "à",
                "customRangeLabel": "Personnaliser",
                "applyLabel": "Valider",
                "cancelLabel": "Annuler",
                "daysOfWeek": ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
                "monthNames": ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
            },
            ranges: {
                'Aujourd\'hui': [moment(), moment()],
                'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Les 7 derniers jours': [moment().subtract(6, 'days'), moment()],
                'Les 30 derniers jours': [moment().subtract(29, 'days'), moment()],
                'Ce mois': [moment().startOf('month'), moment().endOf('month')],
                'Le mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
            data['startDate'] = start.format('YYYY-MM-DD');
            data['endDate'] = end.format('YYYY-MM-DD');
            console.log(data);
            $.ajax({
                type: 'POST',
                url: url,
                data: data, 
                dataType:"json",
                success: function(data) {
                    if (data.status === true) {
                        $('#stat_nb_message').text(data.nb_of_messages);
                        $('#stat_nb_professional').text(data.nb_of_professionals);
                        $('#stat_nb_user').text(data.nb_of_users);
                        $('#stat_nb_need').text(data.nb_of_needs);
                        $('#stat_nb_visitor').text(data.nb_of_visitors);
                    }else{
                        alert("Quelque chose s'est mal passée");
                    }
                },
                complete: function() {

                },
                error: function(){
                    alert('veuillez ré-essayer plutard !');
                }
            });
        });
    });

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

    $(window).on('load', function(){
        var url  = $('#professional_ajax_url').val();

        $("#Professional_skill").autocomplete({
            autoFocus: false,
            classes: {
                "ui-autocomplete": "highlight"
            },
            source: function(data, cb){
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        q: data.term,
                        rand: new Date().getTime(),
                        ajax: 1,
                        action: 'get_skills'
                    },
                    success: function(res){
                        console.log(res.data);
                        if (res.data.length === 0) {
                            $('#Professional_skill_id').val("0");
                        }
                        var result = $.map(res.data, function(skill){
                            return{
                                label: skill.name,
                                value: skill.name,
                                id: skill.id
                            }
                        })
                        cb(result);
                    }
                })
            },
            select: function(event, ui) {
                $('#Professional_skill_id').val(ui.item.id);
            }
        });
    });
})();