<?php

Route::get('/test-portal', function () {
    if (auth()->check()) {
        return "User: " . auth()->user()->name . " | Role: " . auth()->user()->role . " | Portal: " . session('selected_portal', 'none');
    }
    return "Not authenticated";
});