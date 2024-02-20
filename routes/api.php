<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\EventTypeController;
use App\Http\Controllers\Api\MedicalCenterController;
use App\Http\Controllers\Api\MedicalServiceController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\PublicationController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\AwardController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\TestimonyController;
use App\Http\Controllers\Api\SubscribeController;

use App\Http\Controllers\Api\ChatConversationsController;
use App\Http\Controllers\Api\ChatMessagesParticipation;
use App\Http\Controllers\Api\NotificationController;

use App\Models\Role;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route group with middleware and prefinx
// Route::middleware('auth:api')->prefix('v1')->group(function () {
//     // Route::resource('teams', TeamController::class);
//     // Route::resource('people', PersonController::class);
//     // Route::resource('medical_centers', MedicalCenterController::class);
//     // Route::resource('medical_services', MedicalServiceController::class);
//     // Route::resource('publications', PublicationController::class);
//     // Route::resource('comments', CommentController::class);
//     // Route::resource('news', NewsController::class);
//     // Route::resource('event_types', EventTypeController::class);
//     // Route::resource('activities', ActivityController::class);
//     // Route::resource('resources', ResourceController::class);
//     // Route::resource('faqs', FaqController::class);
//     // Route::resource('testimonies', TestimonyController::class);
//     // Route::get('testimonies/slug/{slug}', [TestimonyController::class, 'showSlug']);
//     // Route::resource('images', ImageController::class)->except('store', 'update');
//     Route::resource('doctors', DoctorController::class);
//     Route::resource('news', NewsController::class);
//     Route::resource('teams', TeamController::class);
//     Route::resource('people', PersonController::class);
//     Route::resource('medical_centers', MedicalCenterController::class);
//     Route::resource('medical_services', MedicalServiceController::class);
//     Route::resource('publications', PublicationController::class);
//     Route::resource('comments', CommentController::class);
//     Route::resource('event_types', EventTypeController::class);
//     Route::resource('activities', ActivityController::class);
//     Route::resource('resources', ResourceController::class);
//     Route::resource('faqs', FaqController::class);
//     Route::resource('testimonies', TestimonyController::class);
//     Route::get('testimonies/slug/{slug}', [TestimonyController::class, 'showSlug']);
//     Route::resource('images', ImageController::class)->except('store', 'update');
//     Route::get('get_messages/{chat}', [ChatMessagesParticipation::class, 'obtenerChatMessagesConversation']);
//     Route::post('post_message/{chat}', [ChatMessagesParticipation::class, 'postMessage']);
    
// });
Route::group([
    'prefix' => 'v1',
], function ($router) {

    Route::resource('doctors', DoctorController::class);
    Route::resource('news', NewsController::class);
    Route::resource('teams', TeamController::class);
    Route::resource('people', PersonController::class);
    Route::resource('medical_centers', MedicalCenterController::class);
    Route::resource('medical_services', MedicalServiceController::class);
    Route::resource('publications', PublicationController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('event_types', EventTypeController::class);
    Route::resource('activities', ActivityController::class);
    Route::resource('resources', ResourceController::class);
    Route::resource('faqs', FaqController::class);
    Route::resource('testimonies', TestimonyController::class);
    Route::get('testimonies/slug/{slug}', [TestimonyController::class, 'showSlug']);
    Route::resource('images', ImageController::class)->except('store', 'update');

    Route::delete('/chat_conversations/{chatConversation}', [ChatConversationsController::class, 'destroy']);
    Route::delete('/chat_messages_participation/{chatMensagesParticipation}', [ChatMessagesParticipation::class, 'destroy']);

    Route::post('/subscribe', [SubscribeController::class, 'store']);
    Route::post('/subscribe/{hash}', [SubscribeController::class, 'show'])->name('subscribe.show');
    Route::get('chat_conversations',[ChatConversationsController::class,'obtenerConversacionesUsuario']);
    Route::get('get_messages/{chat}', [ChatMessagesParticipation::class, 'obtenerChatMessagesConversation']);
    Route::post('post_message/{chat}', [ChatMessagesParticipation::class, 'postMessage']);
    Route::get('get_users', [PersonController::class, 'getPeopleToChat']);
    Route::post('register_chat_conversation', [ChatConversationsController::class, 'registerChatConversation']);
    Route::put('check_read_message/{conversationId}', [ChatMessagesParticipation::class, 'checkReadMessage']);
    Route::post('post_publication', [PublicationController::class, 'savePublication']);
    Route::post('example', [PublicationController::class, 'example']);
    Route::post('update_publication/{id}', [PublicationController::class, 'updatePublication']);
    Route::post('update_user/{id}', [PersonController::class, 'updateUser']);
    Route::get('get_my_account', [PersonController::class, 'getMyAccount']);
    Route::get('comments/publication/{publicationId}', [CommentController::class, 'getCommentsByPublication']);
    Route::get('notifications/my-notifications-not-read', [NotificationController::class, 'showMyNotificationsNotRead']);
    Route::delete('delete_my_notification/{notification}',[NotificationController::class, 'deleteNotificationOfMyListNotificacion']);
});


// Route::post('post_1', [MensajesAuxController::class, 'save']);
// Route::get('get_1', [MensajesAuxController::class, 'get_1']);

Route::post('auth/register', [AuthController::class, 'createGeneralPublicUser']);
Route::post('auth/login', [AuthController::class, 'login']);


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth',

], function ($router) {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});


Route::get('routes', function () {
    $routeCollection = Route::getRoutes();

    echo "<table style='width:100%'>";
    echo "<tr>";
    echo "<td width='10%'><h4>HTTP Method</h4></td>";
    echo "<td width='10%'><h4>Route</h4></td>";
    echo "<td width='10%'><h4>Name</h4></td>";
    echo "<td width='70%'><h4>Corresponding Action</h4></td>";
    echo "</tr>";
    foreach ($routeCollection as $value) {
        echo "<tr>";
        echo "<td>" . $value->methods()[0] . "</td>";
        echo "<td>" . $value->uri() . "</td>";
        echo "<td>" . $value->getName() . "</td>";
        echo "<td>" . $value->getActionName() . "</td>";
        echo "</tr>";
    }
    echo "</table>";
});
