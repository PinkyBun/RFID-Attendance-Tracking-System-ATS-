<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ─── Root redirect ────────────────────────────────────────────────────────────
$routes->get('/', 'Auth\AuthController::login');

// ─── Auth ─────────────────────────────────────────────────────────────────────
$routes->get('login',            'Auth\AuthController::login',        ['as' => 'login']);
$routes->post('login',           'Auth\AuthController::authenticate', ['as' => 'login.post']);
$routes->get('logout',           'Auth\AuthController::logout',       ['as' => 'logout']);

// ─── Profile & Account ────────────────────────────────────────────────────────
$routes->get ('profile',          'Auth\ProfileController::index',          ['as' => 'profile']);
$routes->post('profile/update',   'Auth\ProfileController::update',         ['as' => 'profile.update']);
$routes->post('profile/password', 'Auth\ProfileController::changePassword', ['as' => 'profile.password']);

// ─── Dashboard ────────────────────────────────────────────────────────────────
$routes->get('dashboard', 'Dashboard\DashboardController::index', ['as' => 'dashboard']);

// ─── Sections ─────────────────────────────────────────────────────────────────
$routes->get   ('sections',           'Sections\SectionController::index',   ['as' => 'sections.index']);
$routes->get   ('sections/create',    'Sections\SectionController::create',  ['as' => 'sections.create']);
$routes->post  ('sections',           'Sections\SectionController::store',   ['as' => 'sections.store']);
$routes->get   ('sections/(:num)',    'Sections\SectionController::edit/$1', ['as' => 'sections.edit']);
$routes->post  ('sections/(:num)',    'Sections\SectionController::update/$1', ['as' => 'sections.update']);
$routes->delete('sections/(:num)',    'Sections\SectionController::destroy/$1', ['as' => 'sections.destroy']);

// ─── Subjects ─────────────────────────────────────────────────────────────────
$routes->get   ('subjects',                    'Subjects\SubjectController::index',         ['as' => 'subjects.index']);
$routes->get   ('subjects/create',             'Subjects\SubjectController::create',        ['as' => 'subjects.create']);
$routes->post  ('subjects',                    'Subjects\SubjectController::store',         ['as' => 'subjects.store']);
$routes->get   ('subjects/(:num)/edit',        'Subjects\SubjectController::edit/$1',       ['as' => 'subjects.edit']);
$routes->post  ('subjects/(:num)',             'Subjects\SubjectController::update/$1',     ['as' => 'subjects.update']);
$routes->delete('subjects/(:num)',             'Subjects\SubjectController::destroy/$1',    ['as' => 'subjects.destroy']);
$routes->delete('subjects/schedule/(:num)',    'Subjects\SubjectController::removeSchedule/$1', ['as' => 'subjects.schedule.remove']);

// ─── Students ─────────────────────────────────────────────────────────────────
$routes->get   ('students',                   'Students\StudentController::index',          ['as' => 'students.index']);
$routes->get   ('students/(:num)',            'Students\StudentController::show/$1',        ['as' => 'students.show']);
$routes->get   ('students/create/regular',    'Students\StudentController::createRegular',  ['as' => 'students.create.regular']);
$routes->post  ('students/regular',           'Students\StudentController::storeRegular',   ['as' => 'students.store.regular']);
$routes->get   ('students/create/irregular',  'Students\StudentController::createIrregular',['as' => 'students.create.irregular']);
$routes->post  ('students/irregular',         'Students\StudentController::storeIrregular', ['as' => 'students.store.irregular']);
$routes->get   ('students/(:num)/edit',       'Students\StudentController::edit/$1',        ['as' => 'students.edit']);
$routes->post  ('students/(:num)',            'Students\StudentController::update/$1',      ['as' => 'students.update']);
$routes->delete('students/(:num)',            'Students\StudentController::destroy/$1',     ['as' => 'students.destroy']);

// ─── Attendance Sessions ───────────────────────────────────────────────────────
$routes->get ('attendance/session',             'Attendance\SessionController::index',      ['as' => 'session.index']);
$routes->get ('attendance/session/select',      'Attendance\SessionController::select',     ['as' => 'session.select']);
$routes->post('attendance/session/open',        'Attendance\SessionController::open',       ['as' => 'session.open']);
$routes->post('attendance/session/(:num)/close','Attendance\SessionController::close/$1',   ['as' => 'session.close']);
$routes->get ('attendance/session/current',     'Attendance\SessionController::current',    ['as' => 'session.current']);
$routes->get ('attendance/session/schedules/(:num)', 'Attendance\SessionController::getSchedules/$1');

// ─── RFID Tap (public — no auth filter, exempt in Filters.php) ────────────────
$routes->post('attendance/rfid/tap', 'Attendance\RfidController::tap', ['as' => 'rfid.tap']);
$routes->get ('attendance/rfid',     'Attendance\RfidController::live', ['as' => 'rfid.live']);

// ─── Attendance Records & Manual Entry ────────────────────────────────────────
$routes->get ('attendance',                  'Attendance\AttendanceController::index',       ['as' => 'attendance.index']);
$routes->get ('attendance/manual',           'Attendance\AttendanceController::manual',      ['as' => 'attendance.manual']);
$routes->post('attendance/manual',           'Attendance\AttendanceController::storeManual', ['as' => 'attendance.manual.store']);
$routes->get ('attendance/(:num)/edit',      'Attendance\AttendanceController::edit/$1',     ['as' => 'attendance.edit']);
$routes->post('attendance/(:num)',           'Attendance\AttendanceController::update/$1',   ['as' => 'attendance.update']);

// ─── Reports ──────────────────────────────────────────────────────────────────
$routes->get ('reports',                   'Reports\ReportController::index',    ['as' => 'reports.index']);
$routes->get ('reports/sections/(:any)',    'Reports\ReportController::getSections/$1');
$routes->post('reports/preview',            'Reports\ReportController::preview',  ['as' => 'reports.preview']);
$routes->get ('reports/pdf',                'Reports\ReportController::pdf',      ['as' => 'reports.pdf']);
$routes->get ('reports/export',             'Reports\ReportController::export',   ['as' => 'reports.export']);
