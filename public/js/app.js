/*when the webpage's elemenents are ready*/
$(document).ready(function () {
    /*****************************************************************************
     *
     *  global variables
     *
     * ****************************************************************************/
    var shop_register_form = $("#shop_register_form");
    var user_register_form = $("#user_register_form");
    var signCard = $(".card")
    var signInForm = $("#login_form");
    var loginButton = $("#login_button");
    var navBarContainer = $(".navbar .container-fluid")
    var addMobileForm = $("#add_mobile_form")
    var submitButton = $(".submit")
    var priceField = $(".price")
    var shopLoginForm = $("#shop_login_form")
    var selectShopForm = $("#select_shop_form")
    var emailVerificationForm = $("#email_verification_form")
    var saleShopItemsSearch = $("#sale-shop-items-search")
    var priceOfItemBeingSold = $("#price-of-item-being-sold")
    var quantityOfItemBeingSold = $("#quantity-of-item-being-sold")
    var sellProductForm = $("#sell-product-form")
    var basePrice = $("#base-price")
    var searchBar = $("#search_box")
    var searchForm = $("#search_form")
    var shopLocationMap = $("#shop_location_map")
    var getDirectionsButton = $(".get_directions_button")
    var loader = $('<header>'
        +'<div aria-busy="true" aria-label="Loading, please wait." role="progressbar"></div>'
        +'</header>')
    /*stop the preloader*/
    $("#perloader").hide()
    setInterval(hideAlert, 5000)
    var storage = $(".storage")
    /*****************************************************************************
     *
     *  global functions
     *
     * ****************************************************************************/

    function spinningPreloader(){
        return '<div class="preloader">'+
                    '<div class="spinner-layer pl-blue">'+
                        '<div class="circle-clipper left">'+
                            '<div class="circle"></div>'+
                            '</div>'+
                        '<div class="circle-clipper right">'+
                            '<div class="circle"></div>'+
                        '</div>'+
                    '</div>'+
                '</div>';
    }
    function dotsLoader(c){
        var color = c == '' ? '#333' : c
        return '<div class="loader">'+
            '<div class="dot dot1" style="background: ' + color + '" ></div>'+
            '<div class="dot dot2" style="background: ' + color + '"></div>'+
            '<div class="dot dot3" style="background: ' + color + '"></div>'+
            '<div class="dot dot4" style="background: ' + color + '"></div>'+
            '</div>';
    }
    function showErrors(error) {
        $('div.alert-danger').attr('style', 'display: block !important')
        $("div.alert-danger ul").html('')
        if(typeof error == 'string'){
            $("div.alert-danger ul").append('<li>'+error+'</li>')
        }

        else{
            $.each(error.responseJSON, function (index, value) {
                $("div.alert-danger ul").append('<li>'+value+'</li>')
            });
        }
        $(loader).hide()
        submitButton.removeAttr('disabled')
    }

    function hideAlert() {
        if($('div.alert').length > 0){$('div.alert').slideUp('slow')}
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
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                user_register_form.addClass('animated bounceOutRight')
                user_register_form.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
                    function () {
                        //hide the user form
                        user_register_form.hide()

                        //slide in the email verification form
                        emailVerificationForm.slideDown()
                        $("#token_verifiation_email").val(data)
                        emailVerificationForm.addClass('animated bounceInLeft')
                        emailVerificationForm
                            .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
                                function () {
                                    emailVerificationForm.removeClass('bounceInLeft')
                                    signCard.removeClass('jello')
                                    signCard.addClass('jello')

                                });
                    })
                submitButton.removeAttr('disabled')
                loader.hide()
            },
            error: function (error) {
                console.log(error)
                showErrors(error)
            }
        })
    }

    /*****************************************************************************
     *
     *  email verification ajax request
     *
     * ****************************************************************************/
    $("#email_verification_button").click(function (e) {
        e.preventDefault()
        //send the ajax request
        $.ajax({
            url: '/register/users/email-verification',
            data: $("#email_verification_form").serialize(),
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                console.log("inside success")
                if(data == 'success'){

                    //redirect the user to login page
                    window.location = '/login'

                    //hide the token verification form
                    emailVerificationForm.addClass('animated bounceOutRight')
                    emailVerificationForm.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
                        function () {
                            emailVerificationForm.hide()

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
                }
                else if (data == 'error'){
                    showErrors("Token Does no match.")
                }
            },
            error: function (error) {
                console.log("inside errors")
                console.log(error)
                showErrors(error)
            }
        })
    })


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
                if(data.success){
                    window.location = '/login'
                }
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
                submitButton.removeAttr('disabled')
                $(loader).hide()
                window.location = redirectUrl
            },
            error: function (error) {
                submitButton.removeAttr('disabled')
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
               setTimeout(function () {
                   if($("#lat").val() != "" && $("#long").val() != ""){
                       shopRegisterAjaxRequest()
                   }
               }, 1000)
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
        $('.colors :selected').each(function() {
            $(".colors_text").append('<option value="'+$(this).text()+'" type="hidden">'+$(this).text()+'</option>')   // using text() here, because the
        });
        $(".colors_text").hide()
    })
    if(addMobileForm.length){
        addMobileForm.validate({
            rules: {
                current_price: {
                    required: true,
                    digits: true
                },
                old_price: {
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

    //convert colors text into actual color backgrounds
    /*$(".colors_text_box span").each(function(){
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
    var title = $(".title")
    function getTitles(brandId) {
        $.ajax({
            url: '/mobiles/get-titles',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {'brand-id': brandId},
            success: function (data) {
                var arr = [];
                arr.push({id: 0, text: 'Select Mobile'})
                $.each(data, function (index, value) {
                    arr.push({
                        id: index,
                        text: value
                    })
                })
                title.empty()
                title.select2({
                    data: arr
                })
            },
            error: function () {
                console.log("error in titles ajax")
            }
        })
    }


    /**
     * get colors function
     * @param e
     */
    function getColors(mobileId) {
        $.ajax({
            url: '/mobiles/get-colors',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {'mobile-id': mobileId},
            success: function (data) {
                var arr = []
                $.each(data, function (index, value) {
                    arr.push({
                        id: index,
                        text: value
                    })
                })
                colors.empty()
                colors.select2({
                    data: arr
                })
            },
            error: function () {
                console.log("error in colors ajax")
            }
        })
    }

    /**
     * get storages function
     * @param mobileId
     */

    function getStorages(mobileId) {
        $.ajax({
            url: '/mobiles/get-storages',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {'mobile-id': mobileId},
            success: function (data) {
                var arr = [];
                $.each(data, function (index, value) {
                    arr.push({
                        id: index,
                        text: value
                    })
                })
                storage.empty()
                storage.select2({
                    data: arr
                })
            },
            error: function () {
                console.log("error in storages ajax")
            }
        })
    }

    function getBulkData(brandId){
        if($(".bulk-data-row").length){
            $(".bulk-data-row").slideUp()
        }
        $(".preloader.circle").css('display', 'inline-block')
        $.ajax({
            url: '/mobiles/get-bulk',
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {'brand-id': brandId},
            success: function (data) {
                $.each(data, function (index, value) {
                    //console.log(data)


                    /*$.each(value.colors, function (index, value) {
                        colorsArr.push({'id': index, 'text': value})
                    })

                    $.each(value.storages, function (index, value) {
                        storagesArr.push({'id': index, 'text': value})
                    })*/
                    $('<div class="row clearfix bulk-data-row">'+
                    '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">'+
                    '   <div class="form-group">'+
                    '       <div class="form-line">'+
                    '           <input type="hidden" value="'+value.id+'" name="mobile_id[]">'+
                    '           <input type="text" readonly name="title[]" class="form-control" required value="'+value.title+'">'+
                    '       </div>'+
                    '   </div>'+
                    '</div>'+
                    /*'<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">'+
                    '   <div class="form-group">'+
                    '       <div class="form-line">'+
                    '           <input type="file" id="product_image" name="product_image" class="form-control" required>'+
                    '       </div>'+
                    '   </div>'+
                    '</div>'+*/
                    '<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">'+
                    '    <div class="form-group">'+
                    '       <div class="form-line">'+
                    '          <input type="text" required id="current_price" name="current_price[]" class="form-control" placeholder="Price">'+
                    '    </div>'+
                    '</div>'+
                    '</div>'+
                    '<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">'+
                    '   <div class="form-group">'+
                    '      <div class="form-line">'+
                    '           <input type="text" required id="stock" name="stock[]" class="form-control" placeholder="Quantity">' +
                    '      </div>'+
                    '   </div>'+
                    '</div>'+
                    '<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6">'+
                    '    <div class="form-group">'+
                    '       <div class="colors_storage_outer">'+
                                '<select data-id="'+value.id+'" data-color-storage-name="colors" name="colors '+value.title+'[]" multiple class="form-control colors_storage bulk-colors" data-placeholder="Colors">' +

                                '</select>'+
                    '       </div>'+
                    '   </div>'+
                    '</div>'+
                    '<div class="col-lg-2 col-md-2 col-sm-6 col-xs-6 col-xs-7">'+
                    '   <div class="form-group">'+
                    '      <div class="colors_storage_outer">'+
                                '<select data-id="'+value.id+'" data-color-storage-name="storage" name="storage '+value.title+'[]" multiple class="form-control colors_storage bulk-storages" data-placeholder="Storage">' +

                                '</select>'+
                    '      </div>'+
                    '   </div>'+
                    '</div>'+
                    '<div class="col-lg-1 col-md-1 col-sm-6 col-xs-6 col-xs-7">'+
                    '   <div class="form-group">'+
                    '       <input id="'+value.id+'" value="'+value.id+'" type="checkbox" name="checkbox_mobile_add[]" class="filled-in chk-col-blue checkbox_mobile_add">'+
                    '       <label for="'+value.id+'"></label>'+
                    '   </div>'+
                    '</div>'+
                    '</div>').insertAfter($("#bulk-data-area .row:first-child"))
                })
                $(".bulk-colors").select2()
                $(".bulk-storages").select2()
                $(".preloader.circle").css('display', 'none')
            }
            ,error: function () {

            }
        })
    }

    /**
     *save the bulk data
     */
    $("#add_bulk_mobile_btn").click(function (e) {
        e.preventDefault()

        data = $("#bulk-data-form").serializeArray()

        /* Because serializeArray() ignores unset checkboxes and radio buttons: */
        /*data = data.concat(
            jQuery('#bulk-data-form input[type=checkbox]:not(:checked)').map(
                function() {
                    return {"name": this.name, "value": "off"}
                }).get()
        );*/
        data = data.concat(
            jQuery('#bulk-data-form select:not(:selected)').map(
                function() {
                    return {"name": this.name, "value": this.value}
                }).get()
        );
        submitButton.prop('disabled', 'true')
        $.ajax({
            url: '/mobiles/save/bulk-data',
            type: 'POST',
            data: data,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                submitButton.removeAttr('disabled')
            },
            error: function (error) {
                showErrors(error)
            }
        })
    })

    /**
     * made the mobile form dynamic
     * by changing the options by selected brand
     */
    var hideFields = $('.hide-field')
    var selectedBrand = $(".brands-class option:selected")
    if(selectedBrand.val() == '') {
        //hide the fields
        hideFields.hide()
        console.log("null")
    }


    /**
     * load the titles based on brand
     */
    $(".brands-class").on('change', function () {

        //default option was selected
        //no need to fetch the data
        if($(this).val() == '') {
            hideFields.hide()
            console.log("null")
        }
        else {

            //get the brand id
            var brandId = $(this).val()

            //send the ajax request to get the
            //titles of the mobiles
            //for the selected brand

            $(".hide-field.title-field").show()
            getTitles(brandId)
        }
    })

    /**
     * show remaining fields once title is selected
     */
    var titleValue = $(".title option:selected").val()
    if(titleValue == 0 || titleValue == undefined){
        console.log($(".title option:selected").val())
        hideFields.not("hide-fields.title-field").hide()
    }
    else{
        hideFields.show()
    }
    title.on('change', function () {
        if($(this).val() != 0){
            hideFields.show()
            /**
             * load the colors for the mobile
             */
            $("#single_mobile_id").val($(this).val())
            getColors($(this).val())
            getStorages($(this).val())
        }
        else{
            hideFields.not('.title-field').hide()
        }
    })


    /**
     * bulk select data
     */
    $("#bulk-brands").on("change", function () {
        var brandId = $(this).val()
        getBulkData(brandId)
    })

    /*$.ajax({
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
    })*/

    /**
     * custom/extra work
     */
    /**
     * add the ability for the user to add
     * colors or storages if not there already
     * @type {any}
     */
    var colors = $(".colors, .colors_storage")
    var existsVar = false;
    try{
        colors.select2({
            tags: true,
            createTag: function (params) {
                var term = $.trim(params.term);
                var count = 0
                $('.colors option, .colors_storage option').each(function(){
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
        $('.brands-class').select2()
        $('.storage').select2()
        $("#bulk-brands").select2()
        storage.select2()
        colors.select2()
        $("#bulk-title").select2()
        searchBar.select2({
            ajax: {
                url: "/search/live/results/",
                dataType: 'json',
                headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                delay: 250,
                type: 'GET',
                data: function (params) {
                    return {
                        q: params.term, // search term
                    };
                },
                processResults: function (data) {
                    var arr = []
                    $.each(data, function (index, value) {
                        arr.push({
                            id: value,
                            text: value
                        })
                    })
                    return {
                        results: arr
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; },
            minimumInputLength: 1,
            placeholder: 'Search Mobiles'
        });
    }
    catch (err){
        console.log(err + " select 2 error")
    }

    //show popup before deleting the item
    $(".confirm_delete").click(function (e) {
        showConfirmMessage(e, $(this))
        $(this).unbind('submit').submit()
    })
    $(".local-store").click(function (e) {
        var text = $(this).text()
        var target = $(this)
        $(this).html(dotsLoader('#fff'))
        var shopId = $(this).attr('data-shop-id')
        $.ajax(
            {
                type: "post",
                url: "/shops/shop/single/info",
                headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: "shop_id=" + shopId,
                success: function (data) {
                    target.html(text)
                    swal({
                        title: data.shop_name,
                        text: data.phone+"\n"+data.market_plaza+"\n"+data.location,
                        imageUrl: "/shop.png"
                    }, function () {

                        }
                    )
                },
                error: function (data) {
                    target.html(text)
                    swal("Oops", "We couldn't connect to the server!", "error");
                }
            }
        )
    })

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

    //call lazy load function
    $('img').unveil()

    //skip shop registration and redirect to home
    $("#skip_shop_reg").click(function () {
        window.location = '/home'
    })

    /**
     * show hide the forms based on the buttons
     * in the mobile adding page
     */
    var addOneMobileButton = $('.add-one')
    var importExcelFileButton = $('.excel-add')
    var bulkAddButton = $('.bulk-add')
    addOneMobileButton.click(function () {
        $('.add-one-form').slideToggle()
    })
    importExcelFileButton.click(function () {
        $('.excel-add-form').slideToggle()
    })
    bulkAddButton.click(function () {
        $('.bulk-add-form').slideToggle()
    })
    $("body").on('click', ".colors_storage_outer input", function (event) {
        var parent = $(this).closest('.colors_storage_outer').find('> select')
        var mobileId = parent.attr('data-id')
        var colorOrStorage = parent.attr('data-color-storage-name')
        var className = '.' + (parent.attr('class')).split(' ').join('.')

        var exists = true;
        if($($(this).closest('.colors_storage_outer').find('> select option')).length == 0){
            exists = false;
        }
        console.log("options does not exist" + exists)
        if(colorOrStorage.includes('storage') && exists == false){
            console.log('getting storages')
            $.ajax({
                url: '/mobiles/get-storages',
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {'mobile-id': mobileId},
                success: function (data) {
                    console.log('storages data')
                    var arr = []
                    $.each(data, function (index, value) {
                        arr.push({
                            id: index,
                            text: value
                        })
                    })
                    $(parent).select2({data: arr})
                },
                error: function () {
                    console.log("error in colors ajax")
                }
            })
        }
        console.log(colorOrStorage + " " + exists)
        if(colorOrStorage.includes('colors') && exists == false){
            console.log("getting colors data")
            $.ajax({
                url: '/mobiles/get-colors',
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {'mobile-id': mobileId},
                success: function (data) {
                    console.log('colors data')
                    var arr = []
                    $.each(data, function (index, value) {
                        arr.push({
                            id: index,
                            text: value
                        })
                    })
                    $(parent).select2({data: arr})
                },
                error: function () {
                    console.log("error in colors ajax")
                }
            })

        }
    })

    //show selected items count instead of showing all the items
    $('#bulk-data-area').on('select2:close', 'select.colors_storage', function (evt) {
        var uldiv = $(this).siblings('span.select2').find('ul')
        var count = $(this).select2('data').length
        if(count==0){
            uldiv.html("")
        }
        else{
            uldiv.html("<li>"+count+" items selected</li>")
        }
    });

    //send the ajax request for shop login
    $("#shop_login_button").click(function (e) {
        e.preventDefault()
        loader.show()
        submitButton.prop('disabled', true)
        $.ajax({
            url: '/login/shop',
            type: 'POST',
            data: $("#shop_login_form").serialize(),
            dataType: 'json',
            success: function (data) {
                if(data.errors){
                    showErrors(data.errors)
                }
                if(data.shops || data.shop){
                    var arr = []
                    if(data.shops){
                        $.each(data.shops, function (index, value) {
                            arr.push({
                                id: value.id,
                                text: value.shop_name
                            })
                        })
                    }
                    else if (data.shop){
                        arr.push({
                            id: data.shop.id,
                            text: data.shop.shop_name
                        })
                    }
                    shopLoginForm.addClass('animated bounceOutRight')
                    shopLoginForm.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
                        function () {
                            //hide the user form
                            shopLoginForm.hide()
                            loader.hide()
                            //slide in the shop registration form
                            selectShopForm.slideDown()
                            selectShopForm.addClass('animated bounceInLeft')
                            selectShopForm
                                .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
                                    function () {
                                        selectShopForm.removeClass('bounceInLeft')
                                        signCard.removeClass('jello')
                                        signCard.addClass('jello')
                                        $("#shop_id_selector").select2({data: arr})
                                    });
                        })
                    submitButton.removeAttr('disabled')
                }
            },
            error: function (error) {
                console.log(error)
                showErrors(error)
            }
        })
    })
    if($("#market_location").length){
        console.log("market location exists")
        function init() {
            var input = document.getElementById('market_location');
            var autocomplete = new google.maps.places.Autocomplete(input);

            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                //document.getElementById('city2').value = place.name;
                document.getElementById('lat').value = place.geometry.location.lat();
                document.getElementById('long').value = place.geometry.location.lng();
            });
        }
        google.maps.event.addDomListener(window, 'load', init);
    }

    if($("#user_location").length){
        console.log("user location exists")
        function init2() {
            var input = document.getElementById('user_location');
            var autocomplete = new google.maps.places.Autocomplete(input);

            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                //document.getElementById('city2').value = place.name;
                document.getElementById('user_lat').value = place.geometry.location.lat();
                document.getElementById('user_long').value = place.geometry.location.lng();
            });
        }
        google.maps.event.addDomListener(window, 'load', init2);
    }


    if(saleShopItemsSearch.length){
        saleShopItemsSearch.select2()
    }
    if(quantityOfItemBeingSold.length){
        quantityOfItemBeingSold.on('change', function () {
            priceOfItemBeingSold.val(basePrice.val() * quantityOfItemBeingSold.val())
        })
    }
    basePrice.on('change', function () {
        priceOfItemBeingSold.val(basePrice.val() * quantityOfItemBeingSold.val())
    })
    if(sellProductForm.length){
        sellProductForm.validate({
            rules: {
                price: {
                    required: true,
                    digits: true
                },
                base_price: {
                    required: true,
                    digits: true
                },
                quantity: {
                    required: true,
                    digits: true
                }
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

    searchBar.on('change', function () {
        if($.trim(searchBar.val()).length == 0){
            $(".search_box_outer .select2-container").css('border', '1px solid red')
        }
        else{
            $(".search_box_outer .select2-container").css('border', '0px solid red')
        }
    })
    var searchBoxOuter = $(".search_box_outer")
    if(searchForm.length){
        searchForm.submit(function (event) {
            if(($("#search_box option:selected").val()) == undefined){
                event.preventDefault()
                $(".search_box_outer .select2-container").css('border', '1px solid red')
                searchBoxOuter.addClass('jello')
                searchBoxOuter
                    .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
                        function () {
                            $(".search_box_outer").removeClass('jello')
                        });
                searchBar.focus()
            }
            else{
                $(".search_box_outer .select2-container").css('border', '0px solid #fff')
            }
        })
    }

    /**
     * if user is on the single
     * product form and the map div
     * exists
     */
    if(shopLocationMap.length){
        console.log("sending the lat long request")
        //get the shop lat and long
        //when get directions button is clicked
        var temp = [{
            lat: 33.7294,
            lon: 73.0931,
            title: 'Islamabad',
            html: '<h3>Islamabad, Pakistan</h3>',
            icon: '/img/marker.png',
            animation: google.maps.Animation.DROP
        }]
        var map = new Maplace();
        map.Load({
            locations: temp,
            map_div: '#shop_location_map',
            generate_controls: true,
            show_markers: true,
            draggable: false,
            visualRefresh: true,
            map_options: {
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                zoom: 12
            },
            directions_options: {
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC,
                optimizeWaypoints: true,
                provideRouteAlternatives: true,
                avoidHighways: false,
                avoidTolls: false
            }
        });
        getDirectionsButton.click(function () {
            var text = $(this).text()
            var target = $(this)
            target.html(dotsLoader('#fff'))
            var shopId = target.attr('data-shop-id')

            $.ajax({
                url: '/shops/shop/lat/long',
                type: 'POST',
                data: {
                    'shop_id': shopId
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    var userLat = $("#phone_user_lat").val()
                    var userLong = $("#phone_user_long").val()
                    target.html(text)
                    var LocsA = [
                        {
                            lat: userLat == "" ? 33.7294 : userLat,
                            lon: userLong == "" ? 73.0931 : userLong,
                            title: 'Your Location',
                            icon: '/img/user-marker.png',
                            animation: google.maps.Animation.DROP
                        },
                        {
                            lat: data.lat,
                            lon: data.long,
                            title: 'Shop Location',
                            icon: '/img/shop-marker.png',
                            show_infowindow: false,
                            animation: google.maps.Animation.DROP
                        }
                    ]

                    if (map) {
                        map.RemoveLocations(1);
                        map.Load({
                            show_markers: true,
                            locations: LocsA,
                            type: 'directions',
                        });
                    }
                },
                error: function (error) {
                    target.html(text)
                    console.log(error)
                }
            })
        })
    }
    var modalMap = null;
    function initializeMap(lat, long) {
        var mapOptions = {
            center: new google.maps.LatLng(lat, long),
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        modalMap = new google.maps.Map(document.getElementById("map_modal_show"),
            mapOptions);
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, long)
        });
        marker.setMap(modalMap);
    }
    var mapModalButton = $('.shop_map_modal')
    var modal = $("#map_modal")
    if(mapModalButton.length){
        mapModalButton.click(function () {
            modal.modal('toggle')
            var target = $(this)
            var lat = target.attr('data-shop-lat')
            var long = target.attr('data-shop-long')
            initializeMap(lat, long)
            //show map on modal
            modal.on('shown.bs.modal', function () {
                var currentCenter = modalMap.getCenter();  // Get current center before resizing
                google.maps.event.trigger(modalMap, "resize");
                modalMap.setCenter(currentCenter); // Re-set previous center
                $(this).find('.modal-title').text(target.attr('data-original-title'))
            });
        })
    }
    /**********************************************************************
     *
     * Extra Work
     *
     * ********************************************************************
     */
    var radiusSlider = $('.radius-slider')
    if(radiusSlider.length){
        radiusSlider.slider()
    }
    var rangeSlider = function(){
        var slider = $('.range-slider'),
            range = $('.range-slider__range'),
            value = $('.range-slider__value');

        range.on('input', function(){
            range.val(this.value)
            console.log(this.value);
            $(this).parent().next().find(".range-slider__value").html(this.value);
        });
    };

    rangeSlider();
    /*if(searchBar.length){
        searchBar.select2({
            placeholder: 'Search',
            ajax: {
                url: "/search/live/results",
                type: 'POST',
                dataType: 'json',
                delay: 0,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {

                },
                cache: true
            }
        });
    }*/
})//document ready ends here