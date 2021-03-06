/*---------------------------------------------

Author:         IziPresta
Author Email:   contact@izipresta.com
description: Our custom pambe JS

----------------------------------------------*/

(function ($) {
    "use strict";

    /** Chat is continue */
    $(document).on('click', '#send_admin_message', function(event){
        var btn = $(this);
        createExchange(btn);
    });

    $(window).on('load', function(){
        const url = new URL('http://localhost:3000/.well-known/mercure');
        url.searchParams.append('topic', 'http://monsite.com/chat/user');

        const eventSource = new EventSource(url);

        eventSource.onmessage = event => {
            getExchangesByUser(JSON.parse(event.data).sender);
        }
        window.addEventListener('beforeunload', function(){
            if(eventSource != null){ 
                eventSource.close();
            }
        });
    })
  
    /** Chat with admin */
    $(document).on('click', '.chatbox-open', function(){
        $(".chatbox-popup, .chatbox-close").show().fadeIn();
    });

    $(document).on('click', '.chatbox-close', function(){
        $(".chatbox-popup, .chatbox-close").hide().fadeOut();
    });

    $(document).on('click', '.chatbox-maximize', function(){
        $(".chatbox-popup, .chatbox-open, .chatbox-close").hide().fadeOut();
        $(".chatbox-panel").show().fadeIn();
        $(".chatbox-panel").css({ display: "flex" });
    });

    $(document).on('click', '.chatbox-minimize', function(){
        $(".chatbox-panel").hide().fadeOut();
        $(".chatbox-popup, .chatbox-open, .chatbox-close").show().fadeIn();
    });

    $(document).on('click', '.chatbox-panel-close', function(){
        $(".chatbox-panel").hide().fadeOut();
        $(".chatbox-open").show().fadeIn();
    });

    /** Test mercure functionnalities */
   

    /** Load cities into textfield */
    $(window).on('load', function(){
        var url  = $('#professional_ajax_url').val();
        $("#coordonnee_form_city").autocomplete({
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
                        action: 'get_cities'
                    },
                    success: function(res){
                        var result = $.map(res.data, function(city){
                            return{
                                label: city.name,
                                value: city.name,
                                id: city.id
                            }
                        })
                        cb(result);
                    }
                })
            }
        });
    });
    
    /** Response message to other user */
    $(document).on('click', '#btn_send', function(event){
        var url  = $('#professional_ajax_url').val();
        var btn  = $(this);
        var data = {};
        data['action'] = 'send_message';
        data['ajax'] = 1;
        data['rand'] = new Date().getTime();
        data['sender_id'] = $('#sender_id').val();
        data['recipient_id'] = $('#recipient_id').val();
        data['conversation_id'] = $('#conversation_id').val();
        data['message'] = $('#message_send').val();
        var text = btn.html();
        btn.html('...');
        console.log(data);
        $.ajax({
            type: 'POST',
            url: url,
            data: data, 
            dataType:"json",
            success: function(data) {
                if (data.status === true) {
                    var div = '<div id="message'+ data.value.id +'" class="message-item me">'
                                +'<div class="generic-list-item d-flex align-items-center border-bottom-0 bg-transparent">'
                                    +'<div class="message-bubble ml-2 position-relative p-3 rounded">'
                                        +'<p class="text-color font-size-14 font-weight-medium">'+ data.value.content +'</p>'
                                    +'</div>'
                                    +'<div class="dropdown dot-action-wrap ml-1">'
                                        +'<button class="dot-action-btn dropdown-toggle after-none border-0 font-size-22" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                            +'<i class="la la-ellipsis-v"></i>'
                                        +'</button>'
                                        +'<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">'
                                            +'<a id="delete_message" data-id="'+ data.value.id +'" class="dropdown-item" href="#"><i class="la la-trash mr-1"></i>Supprimer</a>'
                                        +'</div>'
                                    +'</div>'
                                +'</div>'
                            +'</div>';
                    $('#messages').append(div);
                
                    $('#messages').scrollTop($('#messages').get(0).scrollHeight);
                    $('.emojionearea-editor').text('');
                }
                btn.html(text);
            },
            complete: function() {
                btn.html(text);
            },
            error: function(error){
                btn.html(text);
                console.log(error);
                alert('veuillez r??-essayer plutard !');
            }
        });
    });

    /** Delete message */
    $(document).on('click', 'a#delete_message', function(event) {
        event.preventDefault();
        var link = $(this);
        var message_id = link.data('id');
        var url  = $('#professional_ajax_url').val();
        var data = {};
        data['action'] = 'delete_message';
        data['id'] = message_id;
        data['ajax'] = 1;
        data['rand'] = new Date().getTime();
       
        var posting = $.post(url, data);
        posting.always(function(data) {
            if(data.status === true) {
                $('#message' + message_id).fadeOut(200, function(){
                    $('#message' + message_id).remove();
                });
            }else{
                alert(data.message);
            }
        });
        
    });

    /** Send message to professional */
    $(document).on('submit', '#form_message', function(event){
        event.preventDefault();
        var url  = $('#professional_ajax_url').val();
        var btn  = $('#btn_message');
        var data = {};
        data['action'] = 'leave_message';
        data['ajax'] = 1;
        data['rand'] = new Date().getTime();
        data['sender_id'] = $('#sender_id').val();
        data['recipient_id'] = $('#recipient_id').val();
        data['message'] = $('#message').val();
        var text = btn.text();
        btn.text(text + '...');
        $.ajax({
            type: 'POST',
            url: url,
            data: data, 
            dataType:"json",
            success: function(data) {
                if (data.status === true) {
                    setTimeout(function(){
                        $('#messageModal').modal('hide');
                    }, 250);
        
                    $(".modal-backdrop").removeClass('show').hide();
                    
                    alert(data.message);
                    $('#message').val('');
                } else {
                    $('#message').after('<ul><li>'+ data.message +'</li></ul>').show().fadeIn(500).delay(5000);
                }
                btn.text(text);
            },
            complete: function() {
                btn.text(text);
            },
            error: function(error){
                btn.text(text);
                console.log(error);
                alert('veuillez r??-essayer plutard !');
            }
        });
    });

    /** Sort professional values */
    $(document).on('change', '#sort_pro', function(event) {
        this.form.submit();
    });

    /** Change professional availability */
    $(document).on('click', '#available_link', function(event) {
        event.preventDefault();
        var link = $(this);
        var url = link.data('url');
        var professional_id = link.data('id');
        var data = {};
        data['action'] = 'change_availability';
        data['id'] = professional_id;
        data['ajax'] = 1;
        data['rand'] = new Date().getTime();
        link.text(link.text() + '...');
        $.get(url, data, function(data){
            if(data.status === true)
                if(link.hasClass('available-btn')){
                    link.removeClass('available-btn').addClass('absent-btn');
                    link.text(data.locale === 'fr' ? 'indisponible' : 'unavailable');
                }
                else{
                    link.removeClass('absent-btn').addClass('available-btn');
                    link.text(data.locale === 'fr' ? 'disponible' : 'available');
                }
            else
                alert("Quelque chose s'est mal pass??e");
        });

    });

    /** Load more reviews */
    $(document).on('click', '#load_more', function(event) {
        event.preventDefault();
        var link = $(this);

        $(".comments-list .comment-hidden:hidden").slice(0, 2).attr('style', 'display: flex;').fadeIn("slow"); 
        if($(".comments-list .comment-hidden:hidden").length == 0){
            link.fadeOut("slow");
        }
    });

    $(document).ready(function() {
        $(".comments-list .comment-hidden").slice(0, 2).attr('style', 'display: flex;'); 
    });

    /** Share professional page */
    $(document).on('click', 'li a.share_twitter, .post-share-social a.share_twitter', function(event) {
        event.preventDefault();
        var link = $(this);
        var shareUrl = link.attr("href");
        var title = link.data("title");
        
        var url = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(title) + '&via=Pambe&url=' + encodeURIComponent(shareUrl);
        
        popupCenter(url, 'Partagez sur Twitter');
    });

    $(document).on('click', 'li a.share_facebook, .post-share-social a.share_facebook', function(event) {
        event.preventDefault();
        var link = $(this);
        var shareUrl = link.attr("href");
        
        var url = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl);
        
        popupCenter(url, 'Partagez sur Facebook');
    });

    $(document).on('click', 'li a.share_instagram, .post-share-social a.share_instagram', function(event) {
        event.preventDefault();
        var link = $(this);
        var shareUrl = link.attr("href");
        
        var url = 'https://www.instagram.com/sharer.php?u=' + encodeURIComponent(shareUrl);
        
        popupCenter(url, 'Partagez sur Instagram');
    });

    $(document).on('click', 'li a.share_linkedin', function(event) {
        event.preventDefault();
        var link = $(this);
        var shareUrl = link.attr("href");
        
        var url = 'https://www.linkedin.com/shareArticle?url=' + encodeURIComponent(shareUrl);
        
        popupCenter(url, 'Partagez sur Linkedin');
    });

    /** For symfony dynamic forms */
    $(document).on('change', '#coordonnee_form_country', function(event) {
        var field = $(this);
        var form = field.closest('form');
        var data = {};
        data[field.attr('name')] = field.val();
        $.post(form.attr('action'), data).then(function(data){
            var region = $('#coordonnee_form_region')
            region.chosen("destroy");
            region.replaceWith($(data).find('#coordonnee_form_region'));
            $("#coordonnee_form_region").chosen({no_results_text: "Oops, Aucun r??sultat !"}); 
        });
    });

    $(document).ready(function() {
        $("#coordonnee_form_region").chosen({no_results_text: "Oops, Aucun r??sultat !"}); 
    });

    $(document).ready(function() {
        $("#user_form_region").chosen({no_results_text: "Oops, Aucun r??sultat !"}); 
    });


    /** Add or remove professional like */
    $(document).on('click', 'a.js-like', function(event) {
        event.preventDefault(); 
        var link = $(this);
        var url = link.attr("href");
         
        var getting = $.get(url, function(data){
            if(data.code == 200){
                $('.js-likes').html('<i class="la la-heart mr-1"></i>Favoris - ' + data.likes);
                var icon = $('.js-like i');
                if(icon.hasClass('la-heart'))
                    icon.removeClass('la-heart').addClass('la-bookmark');
                else
                    icon.removeClass('la-bookmark').addClass('la-heart');
            }
        }, 'json');
        getting.fail(function() {
            alert('Vous deviez ??tre connect?? !');
        })
    });

    /** Add professional profile */
    $(document).ready(function() {
        $('#need_form_documentFile_file').MultiFile({ 
            accept: 'gif|jpg|png|jpeg|pdf|doc|docx',
            onFileSelect: function(element, value, master_element) {
                console.log(master_element);
            },
            onFileDuplicate: function(element, value, master_element) {
                alert("Ce fichier a d??j?? ??t?? s??lectionn??e !");
            },
        });
    });

    /** Add professional profile */
    $(document).ready(function() {
        $('#information_form_profile_imageFile_file').MultiFile({ 
            accept: 'gif|jpg|png|jpeg',
            onFileSelect: function(element, value, master_element) {
                console.log(master_element);
            },
            onFileDuplicate: function(element, value, master_element) {
                alert("Cette image a d??j?? ??t?? s??lectionn??e !");
            },
        });
    });

    /** Add user profile */
    $(document).ready(function() {
        $('#user_form_profile_imageFile_file').MultiFile({ 
            accept: 'gif|jpg|png|jpeg',
            onFileSelect: function(element, value, master_element) {
                console.log(master_element);
            },
            onFileDuplicate: function(element, value, master_element) {
                alert("Cette image a d??j?? ??t?? s??lectionn??e !");
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
                alert("Cette image a d??j?? ??t?? s??lectionn??e !");
            },
        });
    });

    /** Add professional Gallery */
    $(document).ready(function() {
        $('#gallery_form_gallery').MultiFile({ 
            accept: 'gif|jpg|png|jpeg',
            onFileSelect: function(element, value, master_element) {
                console.log(master_element);
            },
            onFileDuplicate: function(element, value, master_element) {
                alert("Cette image a d??j?? ??t?? s??lectionn??e !");
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
                    if(data.status){
                        link.closest('.gallery').fadeOut(200, function(){
                            link.closest('.gallery').remove();
                        });
                    }else{
                        alert(data.error);
                    }
                }
            });
        }
    });

    /** Get gallery professional image */
    $(document).on('click', '[data-edit]', function(event) {
        event.preventDefault(); 
        var link = $(this);
        var url = link.attr("href");

        $.ajax({
            method: "GET",
            url: url,
            contentType: "application/json",
            dataType: "json",
            success: function(data)
            {
                if(data.value.legend !== null){
                    $("#gallery_description").val(data.value.legend);
                }else{
                    $("#gallery_description").val("");
                }
                $('#gallery_file').val('');
                $("#gallery_id").val(data.value.id);
                $('#galleryModal').modal('show');
            }
        });
    });

    /** Edit Professional Gallery */
    $(document).on('submit', '#gallery_form', function(event) {
        event.preventDefault();
        var btn = $('#gallery_btn');
        var url  = $('#gallery_ajax_url').val();

        var data = new FormData(); 
        var files = $('#gallery_file').prop('files')[0];

        data.append('file', files);
        data.append('id', $('#gallery_id').val());
        data.append('legend', $('#gallery_description').val());
        var text = btn.text();
        btn.text(text + '...');
        
        $.ajax({
            type: 'POST',
            url: url,
            data: data, 
            contentType: false,  
            processData: false,
            cache: false,
            success: function(data) {
                if(data.status === true){
                    $('#gallery_' + data.value.id).fadeIn(1000, function(){
                        $('#gallery_' + data.value.id).prop('style', 'background-image: url("/uploads/images/professional/' + data.value.image + '")');
                    });
                }else{
                    alert("Un probl??me est survenu !");
                }
                setTimeout(function(){
                    $('#galleryModal').modal('hide');
                }, 250);
    
                $(".modal-backdrop").removeClass('show').hide();

                btn.text(text);
            },
            error: function(error) {
                console.log(error);
                alert("Quelque chose s'est mal pass??e");
            }
        });
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
        var text = link.text();
        link.text(text + '...');
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
        getting.always(function(data) { link.text(text); });
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
        var text = btn.text();
        btn.text(text + '...');
        
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
                btn.text(text);
            },
            error: function(error){
                btn.text(text);
                console.log(error);
                alert('veuillez r??-essayer plutard !');
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
        var text = btn.text();
        btn.text(text + '...');
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
                    alert("Quelque s'est mal pass??e !");
                }
                btn.text(text);
            },
            complete: function() {
                btn.text(text);
            },
            error: function(error){
                btn.text(text);
                console.log(error);
                alert('veuillez r??-essayer plutard !');
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
        var text = link.text();
        link.text(text + '...');
        var posting = $.post(url, data);
        posting.always(function(data) {
            if(data.status === true) {
                $('.service_' + service_id).fadeOut(200, function(){
                    $('.service_' + service_id).remove();
                });
            }else{
                alert("Une erreur s'est produite.")
            }
            link.text(text);
        });
    });

    $(document).ready(function() {
        $("#information_form_short_description").jqte({placeholder: "Entrez une br??ve description de vous..."});
        $("#information_form_description").jqte({placeholder: "Entrez une description de vous..."}); 
        $("#professional_form_short_description").jqte({placeholder: "Entrez une br??ve description de vous..."}); 
        $("#professional_form_description").jqte({placeholder: "Entrez une description de vous..."}); 
        $("#proposal_form_note").jqte(); 
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
    var text = link.text();
    link.text(text + '...');
    var getting = $.get(url, data, function(data){
        if(data.status === true) {
            var date_start = data.value.start_date.split('-');
            var date_end = data.value.end_date.split('-');
            $('#' + name + '_id_edit').val(data.value.id);
            $('#' + name + '_title_edit').val(data.value.title);
            $('#' + name + '_place_edit').val(data.value.place);
            $('#' + name + '_start_date_edit').val(data.value.start_date);
            $('#' + name + '_end_date_edit').val(data.value.end_date);
            $('#' + name + '_description_edit').val(data.value.description);
                $('#' + name + '_start_date_edit').dateDropper('setDate', {
                    d: parseInt(date_start[0]),
                    m: parseInt(date_start[1]),
                    y: parseInt(date_start[2])
                });
                $('#' + name + '_end_date_edit').dateDropper('setDate', {
                    d: parseInt(date_end[0]),
                    m: parseInt(date_end[1]),
                    y: parseInt(date_end[2])
                });
            $('#' + name + 'ModalEdit').modal('show');
        }else{
            alert("Une erreur s'est produite.")
        }
    }, 'json');
    getting.always(function(data) { link.text(text); });
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
    var text = btn.text();
    btn.text(text + '...');
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
                                +'<a class="edit" data-id="' + data.value.id + '" href="#">' + (data.locale === 'fr' ? 'Modifier' : 'Edit') + '</a> | <a class="delete" data-id="' + data.value.id + '" href="#">' + (data.locale === 'fr' ? 'Supprimer' : 'Delete') + '</a>'
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
            btn.text(text);
        },
        complete: function() {
            btn.text(text);
        },
        error: function(error){
            btn.text(text);
            console.log(error);
            alert('veuillez r??-essayer plutard !');
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
    var text = btn.text();
    btn.text(text + '...');
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
                                    +'<a class="edit" data-id="' + data.value.id + '" href="#">' + (data.locale === 'fr' ? 'Modifier' : 'Edit') + '</a> | <a class="delete" data-id="' + data.value.id + '" href="#">' + (data.locale === 'fr' ? 'Supprimer' : 'Delete') + '</a>'
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
                alert("Quelque chose s'est mal pass??e !");
            }
            btn.text(text);
        },
        complete: function() {
            btn.text(text);
        },
        error: function(error){
            btn.text(text);
            console.log(error);
            alert('Veuillez r??-essayer plutard !');
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
    var text = link.text();
    link.text(text + '...');
    var posting = $.post(url, data);
    posting.always(function(data) {
        if(data.status === true) {
            $('#' + name + '_' + qualification_id).fadeOut(200, function(){
                $('#' + name + '_' + qualification_id).remove();
            });
        }else{
            alert(data.message);
        }
        link.text(text);
    });
}

function popupCenter(url, title, width, height)
{
    var popupWidth = width || 640;
    var popupheight = height || 640;

    var windowLeft = window.screenLeft || window.screenX;
    var windowTop = window.screenTop || window.screenY;
        
    var windowWidth = window.innerWidth || document.documentElement.clientWidth;
    var windowHeight = window.innerHeight || document.documentElement.clientHeight;

    var popupLeft = windowLeft + windowWidth / 2 - popupWidth / 2;
    var popupTop = windowTop + windowHeight / 2 - popupheight / 2;

    var popup = window.open(url, title, 'scrollbars=yes, width=' + popupWidth + ', height=' + popupheight + ', top=' + popupTop + ', left=' + popupLeft);
    popup.focus();

    return true;
}

function getExchangesByUser(sender)
{
    var url  = $('#professional_ajax_url').val();
    var data = {};
    data['action'] = 'get_exchanges';
    data['ajax'] = 1;
    data['rand'] = new Date().getTime();
    data['user_ip'] = sender;
    $.get(url, data, function(data){
        const html = $.map(data.value, function(exchange){
            return '<div class="message-item '+ (exchange.admin == 0 ? 'me': '') +'">'
                        +'<div class="generic-list-item d-flex align-items-center border-bottom-0 bg-transparent" style="padding: 5px;">'
                            +'<div class="message-bubble ml-2 position-relative p-2 rounded">'
                                +'<p class="text-color font-size-14 font-weight-medium">'+ exchange.content +'</p>'
                            +'</div>'                  
                        +'</div>'
                    +'</div>'
        }).join('');
        $('main.chatbox-popup__main').html(html);
        $('main.chatbox-popup__main').scrollTop($('main.chatbox-popup__main').get(0).scrollHeight);
    }, 'json');
}

function createExchange(btn)
{
    var url  = $('#professional_ajax_url').val();
    var data = {};
    data['action'] = 'create_exchange';
    data['ajax'] = 1;
    data['rand'] = new Date().getTime();
    data['content'] = $('#admin_message_send').val();
    var text = btn.html();
    btn.html('...');
  
    $.ajax({
        type: 'POST',
        url: url,
        data: data, 
        dataType: "json",
        success: function(data) {
            if (data.status === true) {
                getExchangesByUser(data.sender);
                $('#admin_message_send').val('');
            }
            btn.html(text);
        },
        complete: function() { btn.html(text); },
        error: function(error) { btn.html(text); console.log(error); alert('veuillez r??-essayer plutard !');}
    });
}
