/*---------------------------------------------

Author:         IziPresta
Author Email:   contact@izipresta.com
description: Our custom pambe JS

----------------------------------------------*/
 
(function ($) {
    "use strict";

    /** Add or remove professional like */
    $(document).on('click', 'a.js-like', function(event) {
        event.preventDefault(); 
        var link = $(this);
        var url = link.attr("href");

        $.ajax({

        });
 
    });

    /** Add professional profile */
    $(document).ready(function() {
        $('#information_form_profil_imageFile_file').MultiFile({ 
            accept: 'gif|jpg|png|jpeg',
            onFileSelect: function(element, value, master_element) {
                console.log(master_element);
            },
            onFileDuplicate: function(element, value, master_element) {
                alert("Cette image a déjà été sélectionnée !");
            },
        });
    });

    /** Add professional cover */
    $(document).ready(function() {
        $('#information_form_cover_imageFile_file').MultiFile({ 
            accept: 'gif|jpg|png|jpeg',
            onFileSelect: function(element, value, master_element) {
                console.log(master_element);
            },
            onFileDuplicate: function(element, value, master_element) {
                alert("Cette image a déjà été sélectionnée !");
            },
        });
    });

    /** Add professional Gallery */
    $(document).ready(function() {
        $('#gallery_form_gallerie').MultiFile({ 
            accept: 'gif|jpg|png|jpeg',
            onFileSelect: function(element, value, master_element) {
                console.log(master_element);
            },
            onFileDuplicate: function(element, value, master_element) {
                alert("Cette image a déjà été sélectionnée !");
            },
        });
    });

    /** Delete gallery professional image */
    $(document).on('click', '[data-delete]', function(event) {
        event.preventDefault(); 
        var link = $(this);
        var url = link.attr("href");

        if(confirm("Voulez-vous supprimer cette image ?")){
            $.ajax({
                method: "DELETE",
                url: url,
                data: JSON.stringify({"_token": link.data("token")}), 
                contentType: "application/json",
                dataType: "json",
                success: function(data)
                {
                    if(data.success){
                        var firstParent = link.parent();
                        var correctParent = firstParent.parent();
                        correctParent.remove();
                    }else{
                        alert(data.error);
                    }
                }
            });
        }
    });

    $(document).on('submit', '#gallery-form', function(event) {
        event.preventDefault(); 
        var mediaType = $("input[name='gallery_form[videoType]']:checked").val();
        var mediaLink = $("#gallery_form_videoUrl").val();

        if(mediaLink != ''){
            if (mediaType == 0) {
                var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
                var match = mediaLink.match(regExp);
                if (match && match[2].length == 11) {
                    this.submit();
                }else{
                    alert("Entrer une youtube url valide !")
                }
            }else if(mediaType == 1){
                var regExp = '';
                var match = mediaLink.match(regExp);
                this.submit();
            }
        }else{
            alert('Veuillez entrer quelque chose, merci !')
        }
    });

    $('#experienceModalEdit').on('show.bs.modal', function(){
        $('#experience_error').hide();
    })

    $(document).ready(function() {
        $('#experience_error').hide();
        $('#qualification_error').hide();
        $('#certification_error').hide();
        $('#service_error').hide();
    });

    /** Get Professional Experience */
    $(document).on('click', '#experience div.form a.edit', function(event) {
        var link = $(this);
        getQualification(event, 'experience', link);
    });

    /** Add professional experience */
    $(document).on('submit', '#experience_form_add',function(event) {
        addQualification(event, 'experience');
    });

    /** Edit professional Experience */
    $(document).on('submit', '#experience_form_edit',function(event) {
        editQualification(event, 'experience');
    });

    /** Delete professional Experience */
    $(document).on('click', '#experience div.form a.delete', function(event) {
        var link = $(this);
        removeQualification(event, 'experience', link);
    });

    /** Get Professional Qualification */
    $(document).on('click', '#qualification div.form a.edit', function(event) {
        var link = $(this);
        getQualification(event, 'qualification', link);
    });

    /** Add Professional Qualification */
    $(document).on('submit', '#qualification_form_add',function(event) {
        addQualification(event, 'qualification');
    });

    /** Edit Professional Qualification */
    $(document).on('submit', '#qualification_form_edit',function(event) {
        editQualification(event, 'qualification');
    });

    /** Delete Professional Qualification */
    $(document).on('click', '#qualification div.form a.delete', function(event) {
        var link = $(this);
        removeQualification(event, 'qualification', link);
    });

    /** Get Professional Certificate */
    $(document).on('click', '#certification div.form a.edit', function(event) {
        var link = $(this);
        getQualification(event, 'certification', link);
    });

    /** Add Professional Certificate */
    $(document).on('submit', '#certification_form_add',function(event) {
        addQualification(event, 'certification');
    });

    /** Edit Professional Certification */
    $(document).on('submit', '#certification_form_edit',function(event) {
        editQualification(event, 'certification');
    });

    /** Delete Professional Certificate */
    $(document).on('click', '#certification div.form a.delete', function(event) {
        var link = $(this);
        removeQualification(event, 'certification', link);
    });

    /** Get Professional Service */
    $(document).on('click', '#service .link-edit', function(event) {
        event.preventDefault();
        var link = $(this);
        var service_id = link.data('id');
        var url  = $('#professional_ajax_url').val();
        var data = {};
        data['action'] = 'get_service';
        data['id'] = service_id;
        data['ajax'] = 1;
        data['rand'] = new Date().getTime();
        var getting = $.get(url, data, function(data){
            if(data.status === true) {
                $('#service_id_edit').val(data.value.id);
                $('#service_title_edit').val(data.value.title);
                $('#service_price_edit').val(data.value.price);
                $('#service_unit_edit').val(data.value.unit);
                $('#service_description_edit').val(data.value.description);

                $('#service_file_edit').val('');
                
                $('#serviceModalEdit').modal('show');
            }else{
                alert("Une erreur s'est produite.")
            }
        }, 'json');
        getting.always(function(data) {});
    });

    /** Add Professional Service */
    $(document).on('submit', '#service_form_add',function(event) {
        event.preventDefault();
        var btn = $('#service_btn_add');
        var url  = $('#professional_ajax_url').val();

        var data = new FormData(); 
        var files = $('#service_file_add').prop('files')[0];

        data.append('action', 'add_service');
        data.append('ajax', 1);
        data.append('rand', new Date().getTime());
        data.append('file', files);
        data.append('title', $('#service_title_add').val());
        data.append('price', $('#service_price_add').val());
        data.append('unit', $('#service_unit_add').val());
        data.append('description', $('#service_description_add').val());
        btn.text('Chargement...');
        
        $.ajax({
            type: 'POST',
            url: url,
            data: data, 
            contentType: false,  
            processData: false,
            cache: false,
            success: function(data) {
                if (data.status === true) {
                    $('#service').prepend(
                        '<div class="col-lg-4 responsive-column service_'+ data.value.id +'">'
                            +'<div class="mini-list-card">'
                                +'<div class="mini-list-img">'
                                    +'<a href="/uploads/images/service/'+ data.value.thumbnail +'" class="d-block" data-fancybox="gallery">'
                                        +'<img src="/uploads/images/service/'+ data.value.thumbnail +'" alt="'+ data.value.title +'">'
                                    +'</a>'
                                +'</div>'
                                +'<div class="mini-list-body">'
                                    +'<h4 class="mini-list-title"><a href="javascript:void(0)">'+ data.value.title +'</a></h4>'
                                    +'<div class="d-flex align-items-center">'
                                        +'<div class="font-size-12">'
                                            +'<a class="link-edit" data-id="'+ data.value.id +'" href="#">Editer</a>'
                                        +'</div>'
                                    +'</div>'
                                    +'<div class="star-rating-wrap d-flex align-items-center">'
                                        +'<div class="font-size-12">'
                                            +'<a class="link-remove" data-id="'+ data.value.id +'" href="#">Supprimer</a>'
                                        +'</div>'
                                    +'</div>'
                                +'</div>'
                            +'</div>'
                        +'</div>'
                    );

                    setTimeout(function(){
                        $('#serviceModalAdd').modal('hide');
                    }, 250);
        
                    $(".modal-backdrop").removeClass('show').hide();
                } else {
                    $('#service_error').html('<span class="font-weight-semi-bold" style="color: #714141;">'+ data.message +'</span>').show().fadeIn(500).delay(5000);
                }
                btn.text('Enregistrer');
            },
            complete: function() {
                btn.text('Enregistrer');
            },
            error: function(error){
                btn.text('Enregistrer');
                console.log(error);
                alert('veuillez ré-essayer plutard !');
            }
        });
    });

    /** Edit Professional Service */
    $(document).on('submit', '#service_form_edit', function(event) {
        event.preventDefault();
        var btn = $('#service_btn_edit');
        var url  = $('#professional_ajax_url').val();

        var data = new FormData(); 
        var files = $('#service_file_edit').prop('files')[0];

        data.append('action', 'edit_service');
        data.append('ajax', 1);
        data.append('rand', new Date().getTime());
        data.append('file', files);
        data.append('id', $('#service_id_edit').val());
        data.append('title', $('#service_title_edit').val());
        data.append('price', $('#service_price_edit').val());
        data.append('unit', $('#service_unit_edit').val());
        data.append('description', $('#service_description_edit').val());
        btn.text('Chargement...');
        console.log(data);
        $.ajax({
            type: 'POST',
            url: url,
            data: data, 
            contentType: false,  
            processData: false,
            cache: false,
            success: function(data) {
                if (data.status === true) {
                  
                var div =  '<div class="mini-list-card">'
                                +'<div class="mini-list-img">'
                                    +'<a href="/uploads/images/service/'+ data.value.thumbnail +'" class="d-block" data-fancybox="gallery">'
                                        +'<img src="/uploads/images/service/'+ data.value.thumbnail +'" alt="'+ data.value.title +'">'
                                    +'</a>'
                                +'</div>'
                                +'<div class="mini-list-body">'
                                    +'<h4 class="mini-list-title"><a href="javascript:void(0)">'+ data.value.title +'</a></h4>'
                                    +'<div class="d-flex align-items-center">'
                                        +'<div class="font-size-12">'
                                            +'<a class="link-edit" data-id="'+ data.value.id +'" href="#">Editer</a>'
                                        +'</div>'
                                    +'</div>'
                                    +'<div class="star-rating-wrap d-flex align-items-center">'
                                        +'<div class="font-size-12">'
                                            +'<a class="link-remove" data-id="'+ data.value.id +'" href="#">Supprimer</a>'
                                        +'</div>'
                                    +'</div>'
                                +'</div>'
                            +'</div>';

                    $('.service_' + data.value.id).html(div);

                    setTimeout(function(){
                        $('#serviceModalEdit').modal('hide');
                    }, 250);
        
                    $(".modal-backdrop").removeClass('show').hide();
                } else {
                    alert("Quelque s'est mal passée !");
                }
                btn.text('Enregistrer');
            },
            complete: function() {
                btn.text('Enregistrer');
            },
            error: function(error){
                btn.text('Enregistrer');
                console.log(error);
                alert('veuillez ré-essayer plutard !');
            }
        });
    });

    /** Delete Professional Service */
    $(document).on('click', '#service .link-remove', function(event) {
        event.preventDefault();
        var link = $(this);
        var service_id = link.data('id');
        var url  = $('#professional_ajax_url').val();
        var data = {};
        data['action'] = 'remove_service';
        data['id'] = service_id;
        data['ajax'] = 1;
        data['rand'] = new Date().getTime();
        link.text('Supprimer...');
        var posting = $.post(url, data);
        posting.always(function(data) {
            if(data.status === true) {
                $('.service_' + service_id).fadeOut(200, function(){
                    $('.service_' + service_id).remove();
                });
            }else{
                alert("Une erreur s'est produite.")
            }
            link.text('Supprimer');
        });
    });

    $(document).ready(function() {
        $("#information_form_short_description").jqte({placeholder: "Entrez une brève description de vous..."});
        $("#information_form_description").jqte({placeholder: "Entrez une description de vous..."});
    });

})(jQuery);

function getQualification(event, name, link)
{
    event.preventDefault();
    var qualification_id = link.data('id');
    var url  = $('#professional_ajax_url').val();
    var data = {};
    data['action'] = 'get_' + name;
    data['id'] = qualification_id;
    data['ajax'] = 1;
    data['rand'] = new Date().getTime();
    var getting = $.get(url, data, function(data){
        if(data.status === true) {
            var date_start = data.value.start_date.split('/');
            var date_end = data.value.end_date.split('/');
            $('#' + name + '_id_edit').val(data.value.id);
            $('#' + name + '_title_edit').val(data.value.title);
            $('#' + name + '_place_edit').val(data.value.place);
            $('#' + name + '_start_date_edit').val(data.value.start_date);
            $('#' + name + '_end_date_edit').val(data.value.end_date);
            $('#' + name + '_description_edit').val(data.value.description);
                /*$('#experience_start_date_edit').dateDropper('setDate', {
                    d: parseInt(date_start[0]),
                    m: parseInt(date_start[1]),
                    y: parseInt(date_start[2])
                });/*
                $('#experience_end_date.date-dropper-input').dateDropper('setDate', {
                    d: parseInt(date_end[0]),
                    m: parseInt(date_end[1]),
                    y: parseInt(date_end[2])
                });*/
            $('#' + name + 'ModalEdit').modal('show');
        }else{
            alert("Une erreur s'est produite.")
        }
    }, 'json');
    getting.always(function(data) {});
}

function addQualification(event, name)
{
    event.preventDefault();
    var btn = $('#' + name + '_btn_add');
    var url  = $('#professional_ajax_url').val();
    var data = {};
    data['action'] = 'add_' + name;
    data['ajax'] = 1;
    data['rand'] = new Date().getTime();
    data['title'] = $('#' + name + '_title_add').val();
    data['place'] = $('#' + name + '_place_add').val();
    data['start_date'] = $('#' + name + '_start_date_add').val();
    data['end_date'] = $('#' + name + '_end_date_add').val();
    data['description'] = $('#' + name + '_description_add').val();
    btn.text('chargement...');
    $.ajax({
        type: 'POST',
        url: url,
        data: data, 
        dataType:"json",
        success: function(data) {
            if (data.status === true) {
                $('#' + name).prepend(
                    '<div id="' + name + '_' + data.value.id + '" class="card">'
                        +'<div class="card-body row">'
                            +'<div class="list col-lg-6"><i class="la la-certificate text-color-2 mr-2 font-size-18"></i>' + data.value.title + '</div>'
                            +'<div class="list col-lg-6"><i class="la la-map-marker text-color-2 mr-2 font-size-18"></i>' + data.value.place + '</div>'
                            +'<div class="list col-lg-6"><i class="la la-hourglass-start text-color-2 mr-2 font-size-18"></i>' + data.value.start_date + '</div>'
                            +'<div class="list col-lg-6"><i class="la la-hourglass-end text-color-2 mr-2 font-size-18"></i>' + data.value.end_date + '</div>'
                            +'<div class="list col-lg-12 expdesc">' + data.value.description + '</div>'
                            +'<div class="list col-lg-12 form">'
                                +'<a class="edit" data-id="' + data.value.id + '" href="#">Editer</a> | <a class="delete" data-id="' + data.value.id + '" href="#">Supprimer</a>'
                            +'</div>'
                        +'</div>'
                    +'</div>'
                    );
                setTimeout(function(){
                    $('#' + name + 'ModalAdd').modal('hide');
                }, 250);
    
                $(".modal-backdrop").removeClass('show').hide();

                $('#' + name + '_title_add').val('');
                $('#' + name + '_place_add').val('');
                $('#' + name + '_description_add').val('');
            } else {
                $('#' + name + '_error').html('<span class="font-weight-semi-bold" style="color: #714141;">'+ data.message +'</span>').show().fadeIn(500).delay(5000);
            }
            btn.text('Enregistrer');
        },
        complete: function() {
            btn.text('Enregistrer');
        },
        error: function(error){
            btn.text('Enregistrer');
            console.log(error);
            alert('veuillez ré-essayer plutard !');
        }
    });
}

function editQualification(event, name)
{
    event.preventDefault();
    var btn = $('#' + name + '_btn_edit');
    var url  = $('#professional_ajax_url').val();
    var data = {};
    data['action'] = 'edit_' + name;
    data['ajax'] = 1;
    data['rand'] = new Date().getTime();
    data['id'] = $('#' + name + '_id_edit').val();
    data['title'] = $('#' + name + '_title_edit').val();
    data['place'] = $('#' + name + '_place_edit').val();
    data['start_date'] = $('#' + name + '_start_date_edit').val();
    data['end_date'] = $('#' + name + '_end_date_edit').val();
    data['description'] = $('#' + name + '_description_edit').val();
    btn.text('chargement...');
    $.ajax({
        type: 'POST',
        url: url,
        data: data, 
        dataType:"json",
        success: function(data) {
            if (data.status === true) {
                var div = '<div class="card-body row">'
                                +'<div class="list col-lg-6"><i class="la la-certificate text-color-2 mr-2 font-size-18"></i>' + data.value.title + '</div>'
                                +'<div class="list col-lg-6"><i class="la la-map-marker text-color-2 mr-2 font-size-18"></i>' + data.value.place + '</div>'
                                +'<div class="list col-lg-6"><i class="la la-hourglass-start text-color-2 mr-2 font-size-18"></i>' + data.value.start_date + '</div>'
                                +'<div class="list col-lg-6"><i class="la la-hourglass-end text-color-2 mr-2 font-size-18"></i>' + data.value.end_date + '</div>'
                                +'<div class="list col-lg-12 expdesc">' + data.value.description + '</div>'
                                +'<div class="list col-lg-12 form">'
                                    +'<a class="edit" data-id="' + data.value.id + '" href="#">Editer</a> | <a class="delete" data-id="' + data.value.id + '" href="#">Supprimer</a>'
                                +'</div>'
                            +'</div>';

                $('#' + name + '_' + data.value.id).html(div);

                setTimeout(function(){
                    $('#' + name + 'ModalEdit').modal('hide');
                }, 250);
                    
                $(".modal-backdrop").removeClass('show').hide();
    
                $('#' + name + '_title_edit').val('');
                $('#' + name + '_place_edit').val('');
                $('#' + name + '_description_edit').val('');
            } else {
                alert("Quelque chose s'est mal passée !");
            }
            btn.text('Enregistrer');
        },
        complete: function() {
            btn.text('Enregistrer');
        },
        error: function(error){
            btn.text('Enregistrer');
            console.log(error);
            alert('Veuillez ré-essayer plutard !');
        }
    });
}
 
function removeQualification(event, name, link)
{
    event.preventDefault();
    var qualification_id = link.data('id');
    var url  = $('#professional_ajax_url').val();
    var data = {};
    data['action'] = 'remove_' + name;
    data['id'] = qualification_id;
    data['ajax'] = 1;
    data['rand'] = new Date().getTime();
    link.text('Supprimer...');
    var posting = $.post(url, data);
    posting.always(function(data) {
        if(data.status === true) {
            $('#' + name + '_' + qualification_id).fadeOut(200, function(){
                $('#' + name + '_' + qualification_id).remove();
            });
        }else{
            alert("Une erreur s'est produite.")
        }
        link.text('Supprimer');
    });
}