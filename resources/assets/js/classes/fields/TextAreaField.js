import Field from './Field';

export default class TextAreaField extends Field {
    constructor(question) {
        super(question);
    }

    build() {
        super.setDefaultProperties();
        this.properties.type = 'textArea';
        return this.properties;
    }
}