export default class Field
{
    constructor(question) {
        this.question = question;
        this.properties = {};
    }

    setDefaultProperties() {
        this.properties.id = this.question.id;
        this.properties.model = this.question.id;
        this.properties.label = this.question.title;
        this.properties.required = this.question.required;
        this.properties.help = this.question.help_text;
    }
}