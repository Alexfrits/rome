(function($) {

    $(document).ready(function(){

        var $formidable = $('#reservation');
        if($formidable.length) {

            var $button = $formidable.find('button'),
                fsetPrefix = 'fset--',

                // Get fieldset list in order (order is important for step-by-step form)
                $fsetList = $formidable.find('fieldset'),

                // Array of buttons (two kinds of buttons array: next step / previous step)
                $btnsNext = [],
                $btnsPrev = [];


            // Hide form final button
            $button.hide();


            // Add a 'slug' property to every fieldset Object + add previous/next buttons to the form
            $.each($fsetList, function(i, fset) {

                // Slug (make further things easier)
                $fsetList[i].slug = fset.className.replace(fsetPrefix,'');

                // Previous buttons
                if(i > 0) {
                    $(this)
                        .hide()
                        .append('<a class="button" data-step="' + i + '" data-id="' + $formidable.attr('id') + '-' + $fsetList[i].slug + '" id="' + $formidable.attr('id') + '-' + $fsetList[i].slug + '-previous">Étape précédente (' + i + '/' + $fsetList.length + ')</a>');
                    $btnsPrev.push($fsetList.eq(i).find('#' + $formidable.attr('id') + '-' + $fsetList.eq(i)[0].slug + '-previous'));
                }

                // Next buttons
                if(i < $fsetList.length - 1) {
                    $(this).append('<a class="button" data-step="' + (i + 2) + '" data-id="' + $formidable.attr('id') + '-' + $fsetList[i].slug + '" id="' + $formidable.attr('id') + '-' + $fsetList[i].slug + '-next">Étape suivante (' + (i + 2) + '/' + $fsetList.length + ')</a>');
                    $btnsNext.push($fsetList.eq(i).find('#' + $formidable.attr('id') + '-' + $fsetList.eq(i)[0].slug + '-next'));
                }
            });


        /* LISTENERS ON BUTTONS: includes AJAX + animations + error managements. */
            // note : tous les listeners sont créés dès la page chargée. est-ce optimal ?
            // ne vaudrait-il mieux pas créer et supprimer les listeners à chaque changement de fieldset ?

            // Next step button
            $.each($btnsNext, function(i, b) {

                $(this).on('click', function(e) {
                    e.preventDefault();

                    // get fieldset slug from class like 'fset--visite': if more than one class is on the fieldset, it still works!
                    $parentFieldset = $(this).parent('fieldset').first();
                    fsetSlug = $parentFieldset.attr('class').split(' ');
                    for(var i = 0; i < fsetSlug.length; i++) {
                        if(fsetSlug[i].search(fsetPrefix) != -1){
                            fsetSlug = fsetSlug[i].replace(fsetPrefix,'');
                            break;
                        }
                    }

                    // Get current step number ([data-step])
                    nextStep = $(this).attr('data-step');

                    // reset/delete error boxes & msg
                    $parentFieldset.find('label').children().removeClass('err-field');
                    $parentFieldset.find('.form-no').remove();
                    $parentFieldset.find('.err-msg').remove();

                        // send request for 1st part of the form
                        formVisiteXHR = $.post(
                            '',
                            $parentFieldset.serialize() + '&fset-check=' + fsetSlug + '&ajax=1',
                            function(resp, status) {
                                if(status == 'success') {

                                    if(resp.status === 0) {
                                        // valid fieldset: animate & go to 2nd fieldset
                                        $parentFieldset.animate({ left: '-150%' }, 600, function() {
                                            $(this).hide();
                                            $(this).next('fieldset')
                                                .css({ right: '-150%' })
                                                .show()
                                                .animate({ right: '0%' }, 600, function() {
                                                    if($fsetList.length == nextStep)
                                                        $button.show();
                                                });
                                        });
                                    }
                                    else {
                                        // manage errors
                                        var err_msg = '';
                                        $.each(resp.errors, function(key, err) {
                                            err_msg += '<p class="err-msg">' + err +'</p>';
                                            $('[id="' + key + '"').addClass('err-field');
                                        });
                                        $parentFieldset
                                            .before('<p class="form-no"><strong>' + resp.status +'</strong></p>' + err_msg);
                                    }
                                }
                            },'json');
                });
            });

            // Previous step button
            $.each($btnsPrev, function(i, b) {

                $(this).on('click', function(e) {
                    e.preventDefault();

                    // Get fieldset slug from class like 'fset--visite'
                    $parentFieldset = $(this).parent('fieldset').first();

                    // Animate to previous fieldset
                    $parentFieldset.animate({ right: '-150%' }, 600, function() {
                        $button.hide();
                        $(this)
                            .hide()
                            .prev('fieldset')
                                .css({ left: '-150%' })
                                .show()
                                .animate({ left: '0%' }, 600, function() {
                                });
                    });
                });
            });



        // AJAX form réservation validation
            $formidable.on('submit', function(e) {
                e.preventDefault();

                // reset/delete error boxes & msg
                $(this).find('label').children().removeClass('err-field');
                $(this).find('.form-no').remove();
                $(this).find('.err-msg').remove();

                // Send request
                formXHR = $.post(
                    '',                    
                    $(this).serialize() + '&ajax=1',
                    function(resp, status) {
                        if(status == 'success') {
                            resp = JSON.parse(resp);

                            if(resp.status === 0) {
                                if(resp.mail === 1) {
                                    $fsetList.last().fadeOut(600, function() {
                                        $formidable.before('<p class="form-ok"><strong>' + resp.mail_msg +'</strong></p>');
                                    });
                                }
                            }
                            else { // manage errors
                                    var err_msg = '';
                                    $.each(resp.errors, function(key, err) {
                                        err_msg += '<p class="err-msg">' + err +'</p>';
                                        $('[id="' + key + '"').addClass('err-field');
                                    });
                                    $parentFieldset
                                        .before('<p class="form-no"><strong>' + resp.status +'</strong></p>' + err_msg);
                            }
                        }
                    });
            });
        }
    });

})(jQuery);