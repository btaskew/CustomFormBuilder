import "@babel/polyfill";

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('flash', require('./components/Utils/Flash').default);
Vue.component('edit-form', require('./components/EditForm').default);
Vue.component('question-list', require('./components/QuestionList').default);
Vue.component('form-question-form', require('./components/FormQuestionForm').default);
Vue.component('question-bank-form', require('./components/QuestionBankForm').default);
Vue.component('form-list', require('./components/FormList').default);
Vue.component('form-builder', require('./components/FormBuilder').default);
Vue.component('rich-text-editor', require('./components/Utils/RichTextEditor').default);
Vue.component('question-bank', require('./components/QuestionBank').default);
Vue.component('manage-question-bank', require('./components/ManageQuestionBank').default);
Vue.component('forms-by-folder', require('./components/FormsByFolder').default);
Vue.component('form-access-manager', require('./components/FormAccessManager').default);
Vue.component('folder-list', require('./components/FolderList').default);
Vue.component('create-folder-form', require('./components/CreateFolderForm').default);

Vue.config.ignoredElements = ['trix-editor'];

import BootstrapVue from 'bootstrap-vue';
import VueFormGenerator from 'vue-form-generator';
import DdJsPackageComponents from 'dd-js-package-components';

Vue.use(BootstrapVue);
Vue.use(VueFormGenerator);
Vue.use(DdJsPackageComponents);

require('../../../vendor/jwwebdev/admin/resources/assets/js/app.js');
require('../../../vendor/jwwebdev/bvforms-laravel/resources/assets/js/app.js');

const app = new Vue({
    el: '#app'
});
