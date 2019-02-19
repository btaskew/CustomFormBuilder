import Errors from '../classes/Errors';
import Success from '../classes/Success';
import axios from 'axios';

export default class Form {
    /**
     * Create a new Form instance.
     *
     * @param {object} data
     */
    constructor(data) {
        this.originalData = data;

        for (let field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();
        this.success = new Success();
    }

    /**
     * Fetch all relevant data for the form.
     */
    data() {
        let data = {};

        for (let property in this.originalData) {
            if (this[property] !== null) {
                data[property] = this[property];
            }
        }

        return data;
    }

    /**
     * Reset the form fields.
     */
    reset() {
        this.errors.clear();
    }

    /**
     * Send a GET request to the given URL.
     * .
     * @param {string} url
     */
    get(url) {
        return this.submit('get', url);
    }

    /**
     * Send a POST request to the given URL.
     * .
     * @param {string} url
     */
    post(url) {
        return this.submit('post', url);
    }


    /**
     * Send a PUT request to the given URL.
     * .
     * @param {string} url
     */
    put(url) {
        return this.submit('put', url);
    }


    /**
     * Send a PATCH request to the given URL.
     * .
     * @param {string} url
     */
    patch(url) {
        return this.submit('patch', url);
    }

    /**
     * Send a DELETE request to the given URL.
     * .
     * @param {string} url
     */
    delete(url) {
        return this.submit('delete', url);
    }

    /**
     * Submit the form.
     *
     * @param {string} requestType
     * @param {string} url
     */
    submit(requestType, url) {
        this.errors = new Errors();
        return new Promise((resolve, reject) => {
            axios[requestType](url, this.data())
                .then(response => {
                    this.onSuccess(response.data);
                    resolve(response.data);
                })
                .catch(({response}) => {
                    this.onFail(response.data.errors);
                    reject(response);
                });
        });
    }

    /**
     * Handle a successful form submission.
     *
     * @param {object} data
     */
    onSuccess(data) {
        this.success = new Success();
        this.success.setMessage(data.message);
        this.reset();
    }

    /**
     * Handle a failed form submission.
     *
     * @param {object} errors
     */
    onFail(errors) {
        if (errors === '') {
            return;
        }
        this.errors = new Errors();
        this.errors.record(errors);
    }
}