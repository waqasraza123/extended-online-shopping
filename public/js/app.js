/*when the webpage's elemenents are ready*/
$(document).ready(function () {
    /*****************************************************************************
     *
     *  global functions
     *
     * ****************************************************************************/
    function showErrors(error) {
        $('div.alert-danger').attr('style', 'display: block !important')
        $("div.alert-danger ul").html('')
        $.each(error.responseJSON, function (index, value) {
            $("div.alert-danger ul").append('<li>'+value+'</li>')
        });
    }

    function hideAlert() {
        if($('div.alert').length > 0){$('div.alert').delay(5000).slideUp('slow')}
    }

    function spinner() {
        return '<div class="preloader pl-size-xs">'+
            '<div class="spinner-layer pl-red-grey">'+
            '<div class="circle-clipper left">'+
            '<div class="circle"></div>'+
            '</div>'+
            '<div class="circle-clipper right">'+
            '<div class="circle"></div>'+
            '</div>'+
            '</div>'+
            '</div>'
    }

    /*****************************************************************************
     *
     *  global variables
     *
     * ****************************************************************************/
    var shop_register_form = $("#shop_register_form");
    var user_register_form = $("#user_register_form");
    var signCard = $(".signup-box .card")
    /*****************************************************************************
     *
     *  register user
     *
     * ****************************************************************************/
    function userRegisterAjaxRequest(){
        //send the post request to save the user
        $.ajax({
            data: user_register_form.serialize(),
            type: 'post',
            url: '/register',
            success: function (data) {
                user_register_form.addClass('animated bounceOutRight')
                user_register_form.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
                    function () {
                        //hide the user form
                        user_register_form.hide()

                        //slide in the shop registration form
                        shop_register_form.slideDown()
                        shop_register_form.addClass('animated bounceInLeft')
                        shop_register_form
                            .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
                                function () {
                                    shop_register_form.removeClass('bounceInLeft')
                                    signCard.removeClass('jello')
                                    signCard.addClass('jello')

                                });
                    })

            },
            error: function (error) {
                showErrors(error)
            }
        })
    }

    /*****************************************************************************
     *
     *  register shop data via ajax request
     *
     * ****************************************************************************/
    function shopRegisterAjaxRequest(){
        //send the post request to save the user
        $.ajax({
            data: shop_register_form.serialize(),
            type: 'post',
            url: '/register-shop',
            success: function (data) {
                window.location = '/home'
            },
            error: function (error) {
                showErrors(error)
            }
        })
    }

    /**
     * hide the error or success notification
     */
    setInterval(hideAlert, 4000)


    /*******************************************************
     *
     * add the jquery validate plugin with forms
     *
     ********************************************************/
    user_register_form.validate({
        rules: {
            'terms': {
                required: true
            },
            'confirm': {
                equalTo: '[name="password"]'
            }
        },
        submitHandler: function (form) {
            userRegisterAjaxRequest();
            return false; // required to block normal submit since you used ajax
        },
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
            $(element).parents('.form-group').append(error);
        }
    });
    shop_register_form.validate({
        rules: {
            'terms': {
                required: true
            },
            'confirm': {
                equalTo: '[name="password"]'
            }
        },
        submitHandler: function (form) {
            shopRegisterAjaxRequest();
            return false; // required to block normal submit since you used ajax
        },
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
            $(element).parents('.form-group').append(error);
        }
    });

})