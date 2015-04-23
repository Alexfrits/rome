(function($) {

    $(document).ready(function(){

        var $reservation = $('#reservation');
        if($reservation.length) {

            var $fsetContact = $reservation.find('.fset--contact'),
                $fsetVisite = $reservation.find('.fset--visite'),
                $button = $reservation.find('button'),
                fsetPrefix = 'fset--',
                $btnsNext = [],
                $btnsPrev = [];

            // hide form button
            $button.hide();


            // get fieldset list in order (order is important for step-by-step form)
            $fsetList = $reservation.find('fieldset');

            // add a 'slug' property to every jQuery fieldset Object
            $.each($fsetList, function(i, fset) {
                $fsetList[i].slug = fset.className.replace(fsetPrefix,'');

                // add previous & next buttons to form
                if(i > 0) {
                    $(this)
                        .hide()
                        .append('<a class="button" data-step="' + i + '" data-id="' + $reservation.attr('id') + '-' + $fsetList[i].slug + '" id="' + $reservation.attr('id') + '-' + $fsetList[i].slug + '-previous">Étape précédente (' + i + '/' + $fsetList.length + ')</a>');
                    $btnsPrev.push($fsetList.eq(i).find('#' + $reservation.attr('id') + '-' + $fsetList.eq(i)[0].slug + '-previous'));
                }

                if(i < $fsetList.length - 1) {
                    $(this).append('<a class="button" data-step="' + (i + 2) + '" data-id="' + $reservation.attr('id') + '-' + $fsetList[i].slug + '" id="' + $reservation.attr('id') + '-' + $fsetList[i].slug + '-next">Étape suivante (' + (i + 2) + '/' + $fsetList.length + ')</a>');
                    $btnsNext.push($fsetList.eq(i).find('#' + $reservation.attr('id') + '-' + $fsetList.eq(i)[0].slug + '-next'));
                }
            });


        // Listener on next buttons: includes AJAX + animations + error managements.
        // note : tous les listeners sont créés dès la page chargée. est-ce optimal ?
        // ne vaudrait-il mieux pas créer et supprimer les listeners à chaque changement de fieldset ?

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

                    // reset/delete error boxes & msg
                    $parentFieldset.find('label').children().removeClass('err-field');
                    $('.form-no').remove();
                    $('.err-msg').remove();

                        // send request for 1st part of the form
                        formVisiteXHR = $.post(
                            '',
                            $parentFieldset.serialize() + '&fset-check=' + fsetSlug + '&ajax=1',
                            function(resp, status) {
                                if(status == 'success') {

                                    if(resp.status === 0) {

                                        // animate & go to 2nd fieldset
                                        $parentFieldset.animate({ left: '-150%' }, 600, function() {
                                            $(this).hide();
                                            $(this).next('fieldset')
                                                .css({ right: '-150%' })
                                                .show()
                                                .animate({ right: '0%' }, 600, function() {});
                                            $button.show();
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
                            },
                            'json');
                });
            });

        // step back §§

            $.each($btnsPrev, function(i, b) {

                $(this).on('click', function(e) {
                    e.preventDefault();

                    // get fieldset slug from class like 'fset--visite'
                    $parentFieldset = $(this).parent('fieldset').first();
                    fsetSlug = $parentFieldset.attr('class').split(' ');
                    for(var i = 0; i < fsetSlug.length; i++) {
                        if(fsetSlug[i].search(fsetPrefix) != -1){
                            fsetSlug = fsetSlug[i].replace(fsetPrefix,'');
                            break;
                        }
                    }

                    // animate & go to previous fieldset
                    $parentFieldset.animate({ right: '-150%' }, 600, function() {
                        $(this).hide();
                        $(this).prev('fieldset')
                            .css({ left: '-150%' })
                            .show()
                            .animate({ left: '0%' }, 600, function() {});
                        $button.show();
                    });
                });
            });



        // AJAX form réservation validation
            $reservation.on('submit', function(e) {
                e.preventDefault();
                // send request
                reservation = $.post(
                    // $(this).attr('action'),
                    '',                    
                    $(this).serialize() + '&ajax=1',
                    function(resp, status) {
                        if(status == 'success') {
                            resp = JSON.parse(resp);
                            console.log (resp);

                            if(resp.status === 1) {
                                $reservation.before('<p class="form-ok"><strong>' + resp.status +'</strong></p>');
                            }
                            else {
                                var err_msg = '';
                                $.each(resp.errors, function(key, err) {
                                    err_msg += '<p class="err-msg">' + err +'</p>';
                                });
                                $reservation
                                    .before('<p class="form-no"><strong>' + resp.status +'</strong></p>')
                                    .find('.fset--visite').prepend(err_msg);
                                // console.log(resp.errors.date);
                            }
                        }
                    });
                // console.log(reservation);
            });
        }
    });

})(jQuery);