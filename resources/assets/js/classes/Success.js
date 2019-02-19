export default class Success {
    /**
     * Create a new Errors instance.
     */
    constructor() {
        this.message = '';
    }

    setMessage(message) {
        this.message = message;
    }

    hasMessage() {
        if (this.message != '') {
            return true;
        }

        return false;
    }
}
