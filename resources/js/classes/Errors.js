export default class Errors {
    /**
     * Create a new Errors instance.
     */
    constructor() {
        this.errors = {};
    }

    /**
     * Determine if an errors exists for the given field.
     *
     * @param {string} field
     * @return {boolean}
     */
    has(field) {
        return this.errors.hasOwnProperty(field);
    }

    /**
     * Returns the valid state for the given field
     *
     * @param {string} field
     * @return {boolean|null}
     */
    state(field) {
        return this.errors.hasOwnProperty(field) ? false : null;
    }

    /**
     * Determine if we have any errors.
     */
    any() {
        return Object.keys(this.errors).length > 0;
    }

    /**
     * Retrieve the error message for a field.
     *
     * @param {string} field
     */
    get(field) {
        if (this.errors[field]) {
            return this.errors[field][0];
        }
    }

    /**
     * Record the new errors.
     *
     * @param {object} errors
     */
    record(errors) {
        this.errors = errors;
    }

    /**
     * Clear one or all error fields.
     *
     * @param {string|object|null} field
     */
    clear(field = null) {
        if (!field) {
            return this.errors = {};
        }

        if (field.target !== undefined) {
            field = field.target.name;
        }

        delete this.errors[field];
    }
}

