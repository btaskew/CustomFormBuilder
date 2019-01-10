class FormBuilder
{
    buildModel(questions) {
        const model = {};

        for (const i in questions) {
            model[questions[i].id] = null;
        }

        return model;
    }

    buildFields(questions) {
        const fields = [];

        for (const i in questions) {
            fields.push(this.buildField(questions[i]));
        }

        fields.push({type:"submit"});

        return fields;
    }

    buildField(question) {
        return {
            id: question.id,
            type: "input",
            inputType: question.type,
            model: question.id,
            label: question.title,
            required: question.required,
            help: question.help_text
        };
    }
}

export default new FormBuilder();