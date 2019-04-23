import Field from './Field';

export default class CheckboxField extends Field {
    constructor(question) {
        super(question);
    }

    build() {
        super.setDefaultProperties();
        this.properties.type = 'checklist';
        this.properties.values = this.setOptions();
        this.properties.listBox = true;
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