import VueFormGenerator from "vue-form-generator";

export default class Field {
    constructor(question) {
        this.question = question;
        this.properties = {};
    }

    setDefaultProperties() {
        this.properties.id = this.question.id;
        this.properties.model = this.question.id.toString();
        this.properties.label = this.question.title;
        this.properties.required = this.question.required;
        this.properties.help = this.question.help_text;
        this.properties.validator = VueFormGenerator.validators.string.locale({
            fieldIsRequired: "Field is required"
        });
        this.setVisibilityRequirement();
    }

    setVisibilityRequirement() {
        if (this.question.visibility_requirement) {
            const question = this.question.visibility_requirement.required_question_id;
            const value = this.question.visibility_requirement.required_value;
            this.properties.visible = function (model) {
                return model && model[question] == value;
            }
        }
    }
}