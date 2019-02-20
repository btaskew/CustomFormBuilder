/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('flash', require('./components/Flash'));
Vue.component('edit-form', require('./components/EditForm'));
Vue.component('question-list', require('./components/QuestionList'));
Vue.component('question-form', require('./components/QuestionForm'));
Vue.component('form-list', require('./components/FormList'));
Vue.component('form-builder', require('./components/FormBuilder'));
Vue.component('rich-text-editor', require('./components/RichTextEditor'));
Vue.component('question-bank', require('./components/QuestionBank'));
Vue.component('forms-by-folder', require('./components/FormsByFolder'));
Vue.component('form-access-list', require('./components/FormAccessList'));

Vue.config.ignoredElements = ['trix-editor'];

import VueFormGenerator from 'vue-form-generator'
import 'vue-form-generator/dist/vfg.css'

Vue.use(VueFormGenerator);

const app = new Vue({
    el: '#app'
});
