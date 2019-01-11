import FieldFactory from './FieldFactory';

class FormBuilder
{
    buildModel(questions) {
        const model = {};

        for (const i in questions) {
            model[questions[i].id.toString()] = null;
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
        const field = FieldFactory.makeField(question);
        return field.build();
    }
}

export default new FormBuilder();