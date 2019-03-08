import Field from './Field';
import VueFormGenerator from 'vue-form-generator';

export default class InputField extends Field {
    constructor(question) {
        super(question);
    }

    build() {
        super.setDefaultProperties();
        this.properties.type = 'input';
        this.properties.inputType = this.question.type;
        this.setValidation();
        return this.properties;
    }

    setValidation() {
        switch (this.question.type) {
            case 'email':
                this.properties.validator = VueFormGenerator.validators.email;
                break;
            case 'number':
                this.properties.validator = VueFormGenerator.validators.number;
                break;
            case 'url':
                this.properties.validator = VueFormGenerator.validators.url.locale({
                    invalidURL: "Invalid URL! Must start with http:// or https://"
                });;
                break;
            case 'date':
                this.properties.validator = VueFormGenerator.validators.date;
                break;
        }
    }
}