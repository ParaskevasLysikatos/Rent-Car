<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::for('home', function($trail, $locale) {
    $trail->push(__('Αρχική'), route('home', $locale));
});

Breadcrumbs::for('categories', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('categories', $locale));
});

Breadcrumbs::for('characteristics', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('characteristics', $locale));
});

Breadcrumbs::for('options', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('options', $locale));
});

Breadcrumbs::for('types', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('types', $locale));
});

Breadcrumbs::for('languages', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('languages', $locale));
});

Breadcrumbs::for('locations', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('locations', $locale));
});

Breadcrumbs::for('stations', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('stations', $locale));
});

Breadcrumbs::for('places', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('places', $locale));
});

Breadcrumbs::for('users', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('users', $locale));
});

Breadcrumbs::for('roles', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('roles', $locale));
});

Breadcrumbs::for('cars', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('cars', $locale));
});

Breadcrumbs::for('drivers', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('drivers', $locale));
});
Breadcrumbs::for('companies', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('companies', $locale));
});

Breadcrumbs::for('transitions', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('transitions', $locale));
});

Breadcrumbs::for('affiliates', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('affiliates', $locale));
});

Breadcrumbs::for('documentTypes', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('documentTypes', $locale));
});

Breadcrumbs::for('documents', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('documents', $locale));
});

Breadcrumbs::for('brands', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('brands', $locale));
});

Breadcrumbs::for('visits', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('visits', $locale));
});

Breadcrumbs::for('bookings', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('bookings', $locale));
});

Breadcrumbs::for('payments', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('payments', $locale));
});

Breadcrumbs::for('invoices', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('invoices', $locale));
});

Breadcrumbs::for('status', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('status', $locale));
});

Breadcrumbs::for('scanners', function($trail, $locale) {
    $trail->parent('home', $locale);
    $trail->push(__('Λίστα Οχημάτων'), route('scanners', $locale));
});
