<?php
use App\Models\Venue;
use App\Http\Controllers\APIController;

Route::get('/test', function () {
    return response()->json(['message' => '✅ Laravel funcionando en Railway']);
});

Route::prefix('olympics')->group(function () {
    Route::post('/login', [APIController::class, 'login']);
    Route::post('/logout', [APIController::class, 'logout']);
    
    Route::post('/events/create', [APIController::class, 'createEvent']);
    Route::get('/events/list', [APIController::class, 'listEvents']);
    Route::put('/events/edit/{id}', [APIController::class, 'editEvent']);

    Route::post('/participants/create', [APIController::class, 'createParticipant']);
    Route::get('/participants/list/{idevent}', [APIController::class, 'listParticipantsByEvent']);
    Route::delete('/participants/delete/{id}', [APIController::class, 'deleteParticipant']);



    Route::get('/venues', [APIController::class, 'getVenues']);

});
