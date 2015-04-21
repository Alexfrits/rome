(function($) {

    $(document).ready(function(){

            // console.log($reservation);
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

    // AJAX THINGS
        $reservation.on('submit', function(e) {
            // console.log('io');
            e.preventDefault();
            checkSave = $.post($(this).attr('action'),
                $(this).serialize() + '&ajax=1',
                function(resp, status) {
                if(status == 'success') {
                    // current code <input> value
                    // codeBoxVal = $codeBox.val();
                    resp = JSON.parse(resp);

                    if(resp.status === 1) {
                        // $reservation.after('<p>Wouhou!</p>');
                        // complete me
                    }
                    else {
                        // $codeLabel.html(resp.error + $codeBoxHTML);
                        // $codeBox = $('#code');
                        // $codeBox.val(codeBoxVal);
                    }
                }
            });
            // console.log(checkSave);
        });
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