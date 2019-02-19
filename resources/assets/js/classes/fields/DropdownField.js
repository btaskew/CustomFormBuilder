import Field from './Field';

export default class DropdownField extends Field {
    constructor(question) {
        super(question);
    }

    build() {
        super.setDefaultProperties();
        this.properties.type = 'select';
        this.properties.values = this.setOptions();
        return this.properties;
    }

    setOptions() {
        const options = [];

        for (const i in this.question.options) {
            const option = this.question.options[i];
            options.push({id: option.value, name: option.display_value});
        }

        return options;
    }
}