
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

Vue.component('example', require('./components/Example.vue'));

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

const app = new Vue({
    el: '#app',

    method: {
        registerUser: function () {
            alert("got you");
        }
    }/*methods of vue ends*/
});

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
        console.log('got it')
        if($('div.alert').length > 0){$('div.alert').delay(5000).slideUp('slow')}
    }

    /*****************************************************************************
     *
     *  global variables
     *
     * ****************************************************************************/
    var shop_register_form = $("#shop_register_form");
    var user_register_form = $("#user_register_form");
    /*****************************************************************************
     *
     *  register user
     *
     * ****************************************************************************/
    $("#register_user").click(function (event) {
        event.preventDefault()

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
                                shop_register_form.addClass('jello')

                            });
                })

            },
            error: function (error) {
                showErrors(error)
            }
        })
    })

    /*****************************************************************************
     *
     *  register shop
     *
     * ****************************************************************************/
    $("#register_shop").click(function (event) {
        event.preventDefault()

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
    })

    /**
     * hide the error or success notification
     */
    setInterval(hideAlert, 4000)

    //add google maps search

})