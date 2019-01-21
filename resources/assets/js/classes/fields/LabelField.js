import Field from "./Field";

export default class LabelField extends Field
{
    constructor(question) {
        super(question);
    }

    build() {
        super.setDefaultProperties();
        this.properties.type = "label";
        this.properties.label = this.question.help_text;
        this.properties.help = null;
        return this.properties;
    }
}