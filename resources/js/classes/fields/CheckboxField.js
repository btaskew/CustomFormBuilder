import Field from './Field';
import VueFormGenerator from 'vue-form-generator';

export default class CheckboxField extends Field {
    constructor(question) {
        super(question);
    }

    build() {
        super.setDefaultProperties();
        this.properties.type = 'checklist';
        this.properties.values = this.setOptions();
        this.properties.listBox = true;
        this.properties.validator = VueFormGenerator.validators.array.locale({
            fieldIsRequired: 'Field is required',
            thisNotArray: 'Field is required',
        });
        return this.properties;
    }

    setOptions() {
        const options = [];

        for (const i in this.question.options) {
            const option = this.question.options[i];
            options.push({value: option.value, name: option.display_value});
        }

        return options;
    }
}