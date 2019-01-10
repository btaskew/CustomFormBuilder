import Field from "./Field";

export default class RadioField extends Field
{
    constructor(question) {
        super(question);
    }

    build() {
        super.setDefaultProperties();
        this.properties.type = "radios";
        this.properties.values = this.setOptions();
        return this.properties;
    }

    setOptions() {
        const options = [];

        for(const i in this.question.options) {
            const option = this.question.options[i];
            options.push({value: option.value, name: option.display_value});
        }

        return options;
    }
}