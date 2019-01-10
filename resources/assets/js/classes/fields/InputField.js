import Field from "./Field";

export default class InputField extends Field
{
    constructor(question) {
        super(question);
    }

    build() {
        super.setDefaultProperties();
        this.properties.type = "input";
        this.properties.inputType = this.question.type;
        return this.properties;
    }
}