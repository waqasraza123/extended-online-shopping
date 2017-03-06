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
    setInterval(hideAlert, 1000)
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
        $(loader).hide()
        submitButton.prop('disabled', false)
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
                submitButton.prop('disabled', false)
                loader.hide()
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
                submitButton.prop('disabled', false)
                $(loader).hide()
                alert("hola")
                window.location = redirectUrl
            },
            error: function (error) {
                submitButton.prop('disabled', false)
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
    $("#add_mobile_btn").click(function(e){
        addMobileForm.append('<select name="colors_text[]" type="hidden" id="colors_text"></select>')
        $('#colors :selected').each(function() {
            $("#colors_text").append('<option value="'+$(this).text()+'" type="hidden">'+$(this).text()+'</option>')   // using text() here, because the
        });
        $("#colors_text").hide()
    })
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
    var colors = $("#colors")
    var existsVar = false;
    try{
        colors.select2({
            tags: true,
            createTag: function (params) {
                var term = $.trim(params.term);
                var count = 0
                $('#colors option').each(function(){
                    if ($(this).text().toUpperCase() == term.toUpperCase()) {
                        existsVar = true
                        return false;
                    }else{
                        existsVar = false
                    }
                });
                if(existsVar){
                    return null;
                }

                return {
                    id: params.term,
                    text: params.term,
                    newTag: true
                }
            },
            maximumInputLength: 20, // only allow terms up to 20 characters long
            closeOnSelect: true
        })
        $('#brands').select2()
        $('#storage').select2()
    }
    catch (err){
        console.log(err + " select 2 error")
    }

    //show popup before deleting the item
    $(".confirm_delete").click(function (e) {
        showConfirmMessage(e, $(this))
        $(this).unbind('submit').submit()
    })

    //convert colors text into actual color backgrounds
    /*$("#colors_text_box span").each(function(){
        $(this).css({'background-color' : $(this).text(), 'width' : '15px', 'height': '15px', 'border-radius': '2px',
        'display': 'block', 'float': 'left', 'margin-left': '2px', 'margin-top': '5px'})
        $(this).prop('data-toggle', 'tooltip')
        $(this).prop('data-placement', 'top')
        $(this).prop('title', $(this).text())
    })*/

    //show warning message before deleting an item
    function showConfirmMessage(e) {
        e.preventDefault()
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: true
        }, function (isConfirm) {
            if(isConfirm)
                $(".delete_item_form").unbind('submit').submit()
            else
                e.preventDefault()
        });
    }

    //jquery datatables
    try{
        var table = $('.js-exportable').DataTable({
            dom: 'Bfrtip',
            paging: false,
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    }
    catch(err) {
        console.log(err)
    }
    $(".remove_spaces").each(function(){
        $(this).text($.trim($(this).text()).replace(/(\r\n|\n|\r)/gm,''))
    })
    $.fn.unveil = function(threshold, callback) {

        var $w = $(window),
            th = threshold || 0,
            retina = window.devicePixelRatio > 1,
            attrib = retina? "data-src-retina" : "data-src",
            images = this,
            loaded;

        this.one("unveil", function() {
            var source = this.getAttribute(attrib);
            source = source || this.getAttribute("data-src");
            if (source) {
                this.setAttribute("src", source);
                if (typeof callback === "function") callback.call(this);
            }
        });

        function unveil() {
            var inview = images.filter(function() {
                var $e = $(this);
                if ($e.is(":hidden")) return;

                var wt = $w.scrollTop(),
                    wb = wt + $w.height(),
                    et = $e.offset().top,
                    eb = et + $e.height();

                return eb >= wt - th && et <= wb + th;
            });

            loaded = inview.trigger("unveil");
            images = images.not(loaded);
        }

        $w.on("scroll.unveil resize.unveil lookup.unveil", unveil);

        unveil();

        return this;

    };
    $('img').unveil()

    $.ajax({
        url: 'http://localhost:8000/python-data/daraz/mobiles/KH3423K4HPQEQN2342091313K23WDQKDJDDQWJD9804H',
        type: 'POST',
        data: {1: {
            "url": "https://www.daraz.pk/hp-notebook-15-ay015nx-core-i3-5005u-red-6509413.html",
            "color": "Red",
            "image_url": "https://static.daraz.pk/p/hp-6695-3149056-1-catalog_grid_3.jpg",
            "brand": "HP",
            "title_alt": "Notebook - 15-ay015nx - Core-i3-5005U - Red",
            "currency": "Rs.",
            "current_price": "39,700",
            "old_price": "45,000",
            "discount_percent": "12%",
            "rating_percent": 0,
            "total_ratings": "14",
            "stock": "In Stock"
        },
            2: {
                "url": "https://www.daraz.pk/hp-notebook-15-ay015nx-core-i3-5005u-red-6509413.html",
                "color": "Red",
                "image_url": "https://static.daraz.pk/p/hp-6695-3149056-1-catalog_grid_3.jpg",
                "brand": "HP",
                "title_alt": "Notebook - 15-ay015nx - Core-i3-5005U - Red",
                "currency": "Rs.",
                "current_price": "39,700",
                "old_price": "45,000",
                "discount_percent": "12%",
                "rating_percent": 0,
                "total_ratings": "14",
                "stock": "In Stock"
            }
        },
        success: function(data){
            console.log(data)
        },
        error: function(err){
            console.log(err)
        }
    })

})//document ready ends here