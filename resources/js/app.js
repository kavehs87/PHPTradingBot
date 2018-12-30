/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');
//
// /**
//  * The following block of code may be used to automatically register your
//  * Vue components. It will recursively scan this directory for the Vue
//  * components and automatically register them with their "basename".
//  *
//  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
//  */
//
// // const files = require.context('./', true, /\.vue$/i)
// // files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))
//
// Vue.component('example-component', require('./components/ExampleComponent.vue'));
//
// /**
//  * Next, we will create a fresh Vue application instance and attach it to
//  * the page. Then, you may begin adding components to this application
//  * or customize the JavaScript scaffolding to fit your unique needs.
//  */
//
// const app = new Vue({
//     el: '#app'
// });
$(document).ready(function () {

    $('.toggleFavorite').on('mouseenter', function () {
        var symbol = $(this).attr('data-symbol');
        if ($(this).hasClass('fa-star')) {
            $(this).addClass('fa-star-o');
            $(this).removeClass('fa-star');
        }
        else {
            $(this).addClass('fa-star');
            $(this).removeClass('fa-star-o');
        }
    });
    $('.toggleFavorite').on('mouseleave', function () {
        var changed = $(this).attr('data-changed');
        if (changed == 1) {
            $(this).attr('data-changed', 0);
            $('.toggleFavorite').attr('class', $(this).attr('class'));
            return;
        }
        var symbol = $(this).attr('data-symbol');
        if ($(this).hasClass('fa-star')) {
            $('.toggleFavorite').addClass('fa-star-o');
            $('.toggleFavorite').removeClass('fa-star');
        }
        else {
            $('.toggleFavorite').addClass('fa-star');
            $('.toggleFavorite').removeClass('fa-star-o');
        }
    });
    $('.toggleFavorite').on('click', function () {
        var symbol = $(this).attr('data-symbol');
        $('.toggleFavorite').attr('data-changed', 1);
        axios.get('/toggleFavorite/' + symbol).then(function (response) {
            favorites = [];
            for (var i in response.data)
                favorites.push(response.data[i]);
            updateMenuFavorites();
        }).then(function () {

        });
    });
});
