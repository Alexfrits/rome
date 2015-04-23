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
                        .append('<a class="button" data-step="' + i + '" id="' + $reservation.attr('id') + '-' + $fsetList[i].slug + '">Étape précédente (' + i + '/' + $fsetList.length + ')</a>');
                }

                if(i < $fsetList.length - 1) {
                    $(this).append('<a class="button" data-step="' + (i + 2) + '" id="' + $reservation.attr('id') + '-' + $fsetList[i].slug + '">Étape suivante (' + (i + 2) + '/' + $fsetList.length + ')</a>');
                }


                // foutre les listeners en une fois ou bien à chaque validation ?
                $btnsNext.push($fsetList.eq(i).find('#' + $reservation.attr('id') + '-' + $fsetList.eq(i)[0].slug));
                $btnsPrev.push($fsetList.eq(i).find('#' + $reservation.attr('id') + '-' + $fsetList.eq(i)[0].slug));
            });



        // FORM NAVIGATION








            // add nav button + listener to 2nd fieldset and hide the fieldset
            $fsetContact
                .hide();
                // .append('<a class="button" id="' + $reservation.attr('id') + '-contact">Étape précédente (1/2)</a>');

            var $formNavTwo = $fsetContact.find('#' + $reservation.attr('id') + 'reservation-contact');
            $formNavTwo.on('click', function(e) {
                e.preventDefault();

                        // finish 1st part first

                $fsetContact.animate({ right: '-150%' }, 600, function() {
                    $(this).hide();
                    $fsetVisite
                        .css({ left: '-150%' })
                        .show()
                        .animate({ left: '0%' }, 600, function() {});
                    $button.show();
                });
            });






            // add nav button to 1st fieldset + listener
            var $formNavOne = $fsetList.eq(0).find('#' + $reservation.attr('id') + '-' + $fsetList.eq(0)[0].slug);


            $formNavOne.on('click', function(e) {
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

                // reset/delete error boxes & msg
                $parentFieldset.find('label').children().removeClass('err-field');
                $('.form-no').remove();
                $('.err-msg').remove();

                /* START 1ST FIELDSET AJAX VALIDATION */
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
                                        $fsetContact
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
                                    $fsetVisite
                                        .before('<p class="form-no"><strong>' + resp.status +'</strong></p>' + err_msg);
                                }
                            }
                        },
                        'json');

                /* END 1ST FIELDSET AJAX VALIDATION */

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