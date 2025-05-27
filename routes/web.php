<?php

use App\Events\UserQueued;
use App\Events\DashboardEvent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\APIController;
use App\Http\Middleware\ValidateUser;
use App\Http\Middleware\IsAdmin;


// Broadcast route
Route::get('/Sacli-Q.com/broadcast', [PublicController::class, 'broadcastEvent'])->name('broadcast.event');

// Main routes
Route::get('/Sacli-Q.com', [MainController::class, 'index'])->name('index'); // Working
Route::get('/Sacli-Q.com/login', [MainController::class, 'index'])->name('index'); // Working
Route::post('/Sacli-Q.com/login', [MainController::class, 'login'])->name('login');
Route::get('/Sacli-Q.com/logout', [MainController::class, 'logout'])->name('logout')->middleware(ValidateUser::class);

// Dashboard and user management routes
Route::get('/Sacli-Q.com/dashboard', [MainController::class, 'dashboard'])->name('dashboard')->middleware(ValidateUser::class);
Route::get('/Sacli-Q.com/users', [MainController::class, 'users'])->name('user.list')->middleware(IsAdmin::class);
Route::post('/Sacli-Q.com/create-account', [MainController::class, 'createAccount'])->name('user.save')->middleware(IsAdmin::class);
Route::delete('/Sacli-Q.com/delete-account/{id}', [MainController::class, 'deleteAccount'])->name('user.delete')->middleware(IsAdmin::class);

// Managing queues (Admin)
Route::get('/Sacli-Q.com/queues', [QueueController::class, 'adminQueues'])->name('admin.queue.list')->middleware(ValidateUser::class);
Route::post('/Sacli-Q.com/create-queue', [QueueController::class, 'createQueue'])->name('admin.queue.create')->middleware(IsAdmin::class);
Route::delete('/Sacli-Q.com/queues/{id}', [QueueController::class, 'deleteQueue'])->name('admin.queue.delete');
Route::get('/Sacli-Q.com/queue/{id}', [QueueController::class, 'viewQueue'])->name('admin.queue.view')->middleware(IsAdmin::class);

// Managing Windows of a queue (Admin)
Route::get('/Sacli-Q.com/window/{id}', [QueueController::class, 'viewWindow'])->name('admin.window.view')->middleware(IsAdmin::class);
Route::post('/Sacli-Q.com/window/add/{id}', [QueueController::class, 'createWindow'])->name('admin.window.create')->middleware(IsAdmin::class);
Route::delete('/Sacli-Q.com/window/remove/{id}', [QueueController::class, 'removeWindow'])->name('admin.window.delete')->middleware(IsAdmin::class);
Route::post('/Sacli-Q.com/window/{id}/assignUser', [QueueController::class, 'assignUserToWindow'])->name('admin.window.user.add')->middleware(IsAdmin::class);
Route::delete('/Sacli-Q.com/window/{id}/removeUser/{user_id}', [QueueController::class, 'removeUserFromWindow'])->name('admin.window.user.remove')->middleware(IsAdmin::class);

//Queue Controls (Admin)
Route::post('/Sacli-Q.com/update-access/{user_id}/{queue_id}', [QueueController::class, 'updateAccess'])->name('update-access');
Route::get('/Sacli-Q.com/queue/settings/{id}', [QueueController::class, 'manageQueue'])->name('queue.manage')->middleware(IsAdmin::class);
Route::post('/Sacli-Q.com/queues/{id}/toggle', [QueueController::class, 'toggleQueue'])->name('queue.toggle')->middleware(ValidateUser::class);
Route::post('/Sacli-Q.com/queues/{id}/clear', [QueueController::class, 'clearQueue'])->name('queue.clear')->middleware(ValidateUser::class);
Route::post('/Sacli-Q.com/window-groups/{id}/toggle', [QueueController::class, 'toggleWindow'])->name('window.toggle')->middleware(ValidateUser::class);
Route::post('/Sacli-Q.com/{id}/update-media-ads;', [QueueController::class, 'updateMediaAds'])->name('queue.advertisement')->middleware(ValidateUser::class);
// Non-admin controls
// User-specific queue routes
Route::get('/Sacli-Q.com/my-queues', [QueueController::class, 'myQueuesAndWindows'])->name('myQueues')->middleware(ValidateUser::class);
Route::get('/Sacli-Q.com/queuing-dashboard/{id}', [QueueController::class, 'queueingDashboard'])->name('QueuingDashboard')->middleware(ValidateUser::class);

// Ticketing routes
Route::get('/Sacli-Q.com/live/{code}', [PublicController::class, 'liveQueue'])->name('liveQueue');
Route::get('/Sacli-Q.com/ticketing/{code}', [PublicController::class, 'ticketing'])->name('ticketing');
Route::post('/Sacli-Q.com/ticketing/submit', [PublicController::class, 'ticketingSubmit'])->name('ticketing.submit');
Route::get('/Sacli-Q.com/ticketing/success/{id}', [PublicController::class, 'ticketingSuccess'])->name('ticketing.success');

// API routes
Route::post('/Sacli-Q.com/api/set-window/{id}', [APIController::class, 'updateWindow'])->name('updateWindowName'); // Update window name
Route::get('/Sacli-Q.com/api/ticket/current/{window_id}', [APIController::class, 'getCurrentTicketForWindow'])->name('getCurrentTicketData'); // Get current ticket for a window
Route::get('/Sacli-Q.com/api/ticket/window/{window_id}/', [APIController::class, 'getWindowUserData'])->name('getWindowUserData'); // Get all tickets for a window group

Route::get('/Sacli-Q.com/api/ticket/next/{window_id}', [APIController::class, 'getNextTicketForWindow'])->name('getNextTicketForWindow'); // Get next ticket for a window
Route::get('/Sacli-Q.com/api/ticket/complete/{window_id}', [APIController::class, 'setToComplete'])->name('setToComplete'); // Set ticket to complete
Route::get('/Sacli-Q.com/api/ticket/on-hold/{window_id}', [APIController::class, 'getNextOnHoldTicket'])->name('getFromTicketsOnHold'); // Get next on-hold ticket
Route::get('/Sacli-Q.com/api/ticket/set-to-hold/{window_id}', [APIController::class, 'putTicketOnHold'])->name('setToHold'); // Set ticket to on hold
Route::get('/Sacli-Q.com/api/ticket/window/{window_id}/ticket/{ticket_id}', [APIController::class, 'handleTicket'])->name('handleTicket');

Route::get('/Sacli-Q.com/api/ticket/all-on-hold/{window_id}', [APIController::class, 'getTicketsOnHold'])->name('getTicketsOnHold'); // Get all on-hold tickets
Route::get('/Sacli-Q.com/api/ticket/upcoming/{window_id}', [APIController::class, 'getUpcomingTickets'])->name('getUpcomingTickets'); // Get all tickets for a window group
Route::get('/Sacli-Q.com/api/ticket/completed/{window_id}', [APIController::class, 'getCompletedTickets'])->name('getCompletedTickets'); // Get all completed tickets for a window group
Route::get('/Sacli-Q.com/api/queue/{id}', [APIController::class, 'getLiveData'])->name('getLiveData'); // Get live data for a queue

Route::get('/Sacli-Q.com/api/window/{window_id}/set-limit/{limit}', [APIController::class, 'setLimit'])->name('window.setLimit'); 

Route::get('/Sacli-Q.com/info', [PublicController::class, 'info'])->name('info'); // Working


// Fallback route
Route::fallback(function () {
    return redirect()->route('info');
});
