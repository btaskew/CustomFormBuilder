import InputField from "./fields/InputField";
import CheckboxField from "./fields/CheckboxField";
import RadioField from "./fields/RadioField";
import DropdownField from "./fields/DropdownField";
import TextAreaField from "./fields/TextAreaField";
import LabelField from "./fields/LabelField";

class FieldFactory
{
    makeField(question) {
        switch (question.type) {
            case "checkbox":
                return new CheckboxField(question);
            case "radio":
                return new RadioField(question);
            case "dropdown":
                return new DropdownField(question);
            case "textarea":
                return new TextAreaField(question);
            case "label":
                return new LabelField(question);
            default:
                return new InputField(question);
        }
    }
}

export default new FieldFactory();