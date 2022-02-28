<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');;

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dashboard', 'UserController@dashboard')->name('dashboard')->middleware('TempPassword:false');
Route::resource('users','UserController')->middleware('TempPassword:false');
Route::resource('dorms','DormController')->middleware('TempPassword:false');
Route::resource('rooms','RoomController')->middleware('TempPassword:false');
Route::resource('colleges','CollegeController')->middleware('TempPassword:false');
Route::resource('students','StudentController')->middleware('TempPassword:false');
Route::resource('breakages', 'BreakageController')->middleware('TempPassword:false');//izbrisi edit
Route::resource('overnights','OvernightStayController')->middleware('TempPassword:false');
Route::resource('items','ItemsController')->middleware('TempPassword:false');
Route::resource('invoices/template','Invoices\TemplateController')->middleware('TempPassword:false');
Route::resource('invoices/dorm','Invoices\DormInvoiceController')->middleware('TempPassword:false');
Route::resource('invoices/room','Invoices\RoomInvoiceController')->middleware('TempPassword:false');
Route::resource('invoices/category','Invoices\CategoryInvoiceController')->middleware('TempPassword:false');
Route::resource('userbills','Invoices\UserBillController')->middleware('TempPassword:false');
Route::get('loaneditems','LoanedItemController@index')->name('loaneditems')->middleware('TempPassword:false');
Route::post('loaneditems/borrow','LoanedItemController@borrow')->name('loaneditems.borrow')->middleware('TempPassword:false');
Route::post('loaneditems/return','LoanedItemController@return')->name('loaneditems.return')->middleware('TempPassword:false');
Route::get('loaneditems/borrowing/{user_id}','LoanedItemController@borrowing')->name('loaneditems.borrowing')->middleware('TempPassword:false');
Route::get('loaneditems/returning/{user_id}','LoanedItemController@returning')->name('loaneditems.returning')->middleware('TempPassword:false');
Route::get('loaneditems/studentsitems/{user_id}','LoanedItemController@show_students_items')->name('loaneditems.studentsitems')->middleware('TempPassword:false');
Route::post('laundryman/student_borrowing','LaundrymanController@student_borrowing')->name('laundryman.student_borrowing')->middleware('TempPassword:false');
Route::post('laundryman/returning','LaundrymanController@student_returning')->name('laundryman.student_returning')->middleware('TempPassword:false');
Route::post('laundryman/items','LaundrymanController@student_items')->name('laundryman.student_items')->middleware('TempPassword:false');
Route::get('breakages/answer/{id}', 'BreakageController@answer')->middleware('TempPassword:false')->name('breakages.answer')->middleware('TempPassword:false');
Route::get('password/change', 'Auth\ChangePasswordController@create')->name('password.change')->middleware('TempPassword:true');
Route::post('password/store', 'Auth\ChangePasswordController@store')->name('password.store')->middleware('TempPassword:true');

Route::post('invoices/log','Invoices\InvoiceLogController@retry')->name('invoice_log.retry')->middleware('TempPassword:false');
Route::get('invoices/log','Invoices\InvoiceLogController@index')->name('invoice_log.index')->middleware('TempPassword:false');
Route::post('invoices/log/new','Invoices\InvoiceLogController@new')->name('invoice_log.new')->middleware('TempPassword:false');

Route::resource('workers', 'DormWorkersController')->middleware('TempPassword:false');
Route::resource('dormstudents', 'DormStudentsController')->middleware('TempPassword:false');
Route::resource('cards', 'CardController')->middleware('TempPassword:false');
Route::resource('studentcards', 'StudentCardController')->middleware('TempPassword:false');
Route::post('studentcard/renew','StudentCardController@renew')->name('studentcard.renew')->middleware('TempPassword:false');
Auth::routes(['register'=>false]);
