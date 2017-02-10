/*when the webpage's elemenents are ready*/
$(document).ready(function () {
    /*****************************************************************************
     *
     *  global variables
     *
     * ****************************************************************************/
    var shop_register_form = $("#shop_register_form");
    var user_register_form = $("#user_register_form");
    var signCard = $(".signup-box .card")
    var signInForm = $("#login_form");
    var loginButton = $("#login_button");
    var navBarContainer = $(".navbar .container-fluid")
    var addMobileForm = $("#add_mobile_form")
    var submitButton = $(".submit")
    var priceField = $(".price")
    var loader = $('<header>'
        +'<div aria-busy="true" aria-label="Loading, please wait." role="progressbar"></div>'
        +'</header>')
    /*stop the preloader*/
    $("#perloader").hide()
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

    /**
     * currency formatter
     */
    function currencyComma(val){
        while (/(\d+)(\d{3})/.test(val.toString())){
            val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
        }
        return val;
    }
    function userRegisterAjaxRequest(){
        $(loader).insertAfter(navBarContainer)
        submitButton.prop('disabled', 'true')
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
                submitButton.removeProp('disabled')
            },
            error: function (error) {
                $(loader).hide()
                submitButton.removeProp('disabled')
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
        $(loader).insertAfter(navBarContainer)
        //send the post request to save the user
        $.ajax({
            data: shop_register_form.serialize(),
            type: 'post',
            url: '/register-shop',
            success: function (data) {
                $(loader).hide()
                window.location = '/home'
            },
            error: function (error) {
                showErrors(error)
            }
        })
    }

    /*****************************************************************************
     *
     *  add new mobile via ajax request
     *
     * ****************************************************************************/
    function addMobileAjaxRequest(form) {
        var formData = new FormData(form)
        $.ajax({
            type : 'POST',
            data: formData,
            url: '/products/mobile',
            success: function (data) {
                alert(data)
            },
            error: function (error) {
                alert(error)
                showErrors(error)
            }
        })
    }
    /**
     * ajax request
     */
    function sendAjax(url, form, redirectUrl){
        //add the spinner
        $(loader).insertAfter(navBarContainer)
        submitButton.prop('disabled', 'true')

        $.ajax({
            data: form.serialize(),
            type: 'post',
            url: url,
            success: function (data) {
                submitButton.removeProp('disabled')
                $(loader).hide()
                alert("hola")
                window.location = redirectUrl
            },
            error: function (error) {
                submitButton.removeProp('disabled')
                $(loader).hide()
                showErrors(error)
            }
        })
    }


    /*******************************************************
     *
     * add the jquery validate plugin with forms
     *
     ********************************************************/
    if(user_register_form.length){
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
                userRegisterAjaxRequest()
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
    }

    /**
     * shop form validation
     */
    if(shop_register_form.length){
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
               shopRegisterAjaxRequest()
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
    }

    /**
     * add sign in validation with jquery
     */
    if(signInForm.length){
        signInForm.validate({
            highlight: function (input) {
                console.log(input);
                $(input).parents('.form-line').addClass('error');
            },
            unhighlight: function (input) {
                $(input).parents('.form-line').removeClass('error');
            },
            submitHandler: function (form) {
                sendAjax('/login', form, "/home")
                return false; // required to block normal submit since you used ajax
            },
            errorPlacement: function (error, element) {
                $(element).parents('.input-group').append(error);
            }
        });
    }

    /**
     * add the validation with add mobile form
     */
    if(addMobileForm.length){
        addMobileForm.validate({
            rules: {
                current_price: {
                    required: true,
                    digits: true
                },
                discount_price: {
                    required: true,
                    digits: true
                },
                stock: {
                    required: true,
                    digits: true
                }
            },
            submitHandler: function (form) {
                /*sendAjax('/products/mobile', form, "")*/
                addMobileAjaxRequest(form)
                return false; // required to block normal submit since you used ajax
            },
            highlight: function (input) {
                $(input).parents('.form-line').addClass('error');
            },
            unhighlight: function (input) {
                $(input).parents('.form-line').removeClass('error');
            },
            errorPlacement: function (error, element) {
                $(element).parents('.form-group').append(error);
            }
        });
    }

    /**
     * custom/extra work
     */
    $('#colors').select2()
    $('#brands').select2()
    setInterval(hideAlert, 1000)
})