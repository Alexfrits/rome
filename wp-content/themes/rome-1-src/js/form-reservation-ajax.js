(function($) {

    $(document).ready(function(){

        var $reservation = $('#reservation');
        if($reservation.length) {

            // AJAX THINGS
                $reservation.on('submit', function(e) {
                    e.preventDefault();
                    reserve = $.post(
                        $(this).attr('action'),
                        // '/',
                        $(this).serialize() + '&ajax=1',
                        function(resp, status) {
                            if(status == 'success') {
                                resp = JSON.parse(resp);
                                console.log (resp);

                                if(resp.status === 1) {
                                    $reservation.after('<p>Wouhou!</p>');
                                    // complete me
                                }
                                else {
                                    // manage errors
                                    console.log('error management');
                                }
                            }
                        });
                    // console.log(reserve);
                });
            console.log('hi');
        }
    });

})(jQuery);