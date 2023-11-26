# Rentalbook-frontend setup with steps.(latest branch is 'paraskevas')
1. download project .
2. download node.js .
3. run on git bash(install git) 'npm install -g @angular/cli' on project.
4. run npm install.
5. ng serve to run the server on localhost:4200 .

6. note: first time go to laravel hash generator(online website) and make a known password that you update on db
run 'php artisan storage:link' (on laravel api folder) for documents and images.

if you have different listening(e.g :http://localhost:8000/carrentalApi/public) for your api server go to folder enviroments/enviroment.ts
and change the url, do not forget has to end with 'api'.
# RentalbookFrontend

This project was generated with [Angular CLI](https://github.com/angular/angular-cli) version 11.0.6.

## Development server

Run `ng serve` for a dev server. Navigate to `http://localhost:4200/`. The app will automatically reload if you change any of the source files.

## Code scaffolding

Run `ng generate component component-name` to generate a new component. You can also use `ng generate directive|pipe|service|class|guard|interface|enum|module`.

## Build

Run `ng build` to build the project. The build artifacts will be stored in the `dist/` directory. Use the `--prod` flag for a production build.

## Running unit tests

Run `ng test` to execute the unit tests via [Karma](https://karma-runner.github.io).

## Running end-to-end tests

Run `ng e2e` to execute the end-to-end tests via [Protractor](http://www.protractortest.org/).

## Further help

To get more help on the Angular CLI use `ng help` or go check out the [Angular CLI Overview and Command Reference](https://angular.io/cli) page.
