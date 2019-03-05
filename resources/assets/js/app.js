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

Vue.component('flash', require('./components/Utils/Flash'));
Vue.component('edit-form', require('./components/EditForm'));
Vue.component('question-list', require('./components/QuestionList'));
Vue.component('form-question-form', require('./components/FormQuestionForm'));
Vue.component('question-bank-form', require('./components/QuestionBankForm'));
Vue.component('form-list', require('./components/FormList'));
Vue.component('form-builder', require('./components/FormBuilder'));
Vue.component('rich-text-editor', require('./components/Utils/RichTextEditor'));
Vue.component('question-bank', require('./components/QuestionBank'));
Vue.component('forms-by-folder', require('./components/FormsByFolder'));
Vue.component('form-access-manager', require('./components/FormAccessManager'));
Vue.component('folder-list', require('./components/FolderList'));
Vue.component('create-folder-form', require('./components/CreateFolderForm'));

Vue.config.ignoredElements = ['trix-editor'];

import BootstrapVue from 'bootstrap-vue'
import VueFormGenerator from 'vue-form-generator'
import 'vue-form-generator/dist/vfg.css'

Vue.use(BootstrapVue);
Vue.use(VueFormGenerator);

require('../../../vendor/jwwebdev/admin/resources/assets/js/app.js');
require('../../../vendor/jwwebdev/bvforms-laravel/resources/assets/js/app.js');

const app = new Vue({
    el: '#app'
});
