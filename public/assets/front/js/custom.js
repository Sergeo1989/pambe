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
    $( document ).ready(function() {
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

    $(document).ready(function() {
        $('#experience_error').hide();
        $('#qualification_error').hide();
        $('#certification_error').hide();
    });

    /** Add professional experience */
    $(document).on('submit', '#experience_form',function(event) {
        addQualification(event, 'experience');
    });

    /** Delete professional experience */
    $(document).on('click', '#experience div.delete a', function(event) {
        var link = $(this);
        removeQualification(event, 'experience', link);
    });

    /** Add professional qualification */
    $(document).on('submit', '#qualification_form',function(event) {
        addQualification(event, 'qualification');
    });

    /** Delete professional qualification */
    $(document).on('click', '#qualification div.delete a', function(event) {
        var link = $(this);
        removeQualification(event, 'qualification', link);
    });

    /** Add professional certificate */
    $(document).on('submit', '#certification_form',function(event) {
        addQualification(event, 'certification');
    });

    /** Delete professional certificate */
    $(document).on('click', '#certification div.delete a', function(event) {
        var link = $(this);
        removeQualification(event, 'certification', link);
    });

    /** Add professional service */
    $(document).on('click', 'a#service', function(event) {
        event.preventDefault();
        var collectionHolder = $(event.currentTarget.dataset.collection);
        var prototype = collectionHolder.data('prototype');
        var index = parseInt(collectionHolder.data('index'));

        collectionHolder.append(prototype.replace(/__name__/g, index));
        
        collectionHolder.data('index', index + 1);
        console.log(collectionHolder);
    });

    /** Delete professional service */
    $(document).on('click', '.btn-remove', function(event) {
        event.preventDefault();
        $(this).closest('.item').remove();
    });

})(jQuery);


function addQualification(event, name)
{
    event.preventDefault();
    var btn = $('#' + name + '_btn');
    var url  = $('#professional_ajax_url').val();
    var data = {};
    data['action'] = 'add_' + name;
    data['ajax'] = 1;
    data['rand'] = new Date().getTime();
    data['title'] = $('#' + name + '_title').val();
    data['place'] = $('#' + name + '_place').val();
    data['start_date'] = $('#' + name + '_start_date').val();
    data['end_date'] = $('#' + name + '_end_date').val();
    data['description'] = $('#' + name + '_description').val();
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
                            +'<div class="list col-lg-12 delete"><a data-id="' + data.value.id + '" href="#">Supprimer</a></div>'
                        +'</div>'
                    +'</div>'
                    );
                setTimeout(function(){
                    $('#' + name + 'Modal').modal('hide');
                }, 250);
    
                $(".modal-backdrop").removeClass('show').hide();

                $('#' + name + '_title').val('');
                $('#' + name + '_place').val('');
                $('#' + name + '_description').val('');
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