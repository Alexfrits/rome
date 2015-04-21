(function($) {

    $(document).ready(function(){

            // console.log($reservation);
        var $reservation = $('#reservation');
        if($reservation.length) {


            var $fset_contact = $reservation.find('.fset--contact'),
                $fset_visite = $reservation.find('.fset--visite'),
                $button = $reservation.find('button');
            
        // FORM NAVIGATION


            // hide form <button>
            $button.hide();

            // add nav button + listener to 2nd fieldset and hide the fieldset
            $fset_contact
                .append('<a class="button" id="reservation-2">Étape précédente (1/2)</a>')
                .hide();

            var $form_nav_2 = $fset_contact.find('#reservation-2');
            $form_nav_2.on('click', function(e) {
                e.preventDefault();

                        // finish 1st part first

                $fset_contact.animate({ right: '-150%' }, 400, function() {
                    $button.hide();
                    $(this).hide();
                    $fset_visite
                        .css({
                            left: '0'
                        })
                        .fadeIn(400, function() {});

                });
            });

            // add nav button to 1st fieldset + listener
            $fset_visite.append('<a class="button" id="reservation-1">Étape suivante (2/2)</a>');
            var $form_nav_1 = $fset_visite.find('#reservation-1');

            $form_nav_1.on('click', function(e) {
                e.preventDefault();
                // à faire : valider la première partie du formulaire

                // à faire : récupérer les valeurs avant de supprimer

                $fset_visite.animate({ left: '-150%' }, 400, function() {
                    $(this).hide();
                    $fset_contact
                        .css({
                            right: '0'
                        })
                        .fadeIn(400, function() {});
                    $button.show();
                });
            });

            // 2nd fieldset "next" pseudo-button





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
                // console.log(reserve);
            });
        }
    });

})(jQuery);