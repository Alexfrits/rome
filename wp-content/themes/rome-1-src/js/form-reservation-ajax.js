(function($) {

    var $reservation = $('#reservation');

            // console.log($reservation);


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

})(jQuery);