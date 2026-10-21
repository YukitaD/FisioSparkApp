<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('/', 'pages::index')->name('home');
});

Route::livewire('/citas', 'pages::citas-list')->name('citas.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('/agenda', 'pages::agenda')->name('agenda');
});

Route::livewire('/facturas', 'pages::facturas')->name('facturas.index');
    Route::livewire('/pacientes/{paciente}/facturas', 'pages::facturas')->name('paciente.facturas');

    Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('/registrar-paciente', 'pages::registrar-paciente')->name('registrar-paciente');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Para ver los detalles del paciente
    Route::livewire('/pacientes/{paciente}', 'pages::paciente-detalles')
        ->name('paciente-detalles');

    // Para editar al paciente
    Route::livewire('/pacientes/{paciente}/editar', 'pages::paciente-editar')
        ->name('paciente-editar');
});
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';