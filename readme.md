# Custom form builder
Application for users to create their own custom forms.

Functionality includes:
- Creating a variety of different question types, such as number, date and radio buttons
- Viewing and exporting of form responses
- Grouping of related forms into folders
- Conditionally displaying questions based on answers to other questions
- Adding commonly used questions from a question bank
- Only allowing form responses within a specific date window
- Limiting the maximum number of responses to a form
- Sending an email to a list of addresses whenever a response is given
- Sending a custom email to the user who submitted the form after completion
- Grant other users of the site access to either just view form responses or edit the form themselves
- Expose a rendered version of their form for rendering on other sites

In addition admin users can:
- Manage available folders
- Add or edit questions in the question bank

## Application setup
```bash
git clone https://github.com/btaskew/CustomFormBuilder.git
composer install
php artisan migrate --seed
yarn install
yarn dev
```
An test user with some example forms will be setup. Simply login using 'john@email.com' with the password 'password'
