# Enhancement Planning Specification

## Requirement Understanding

- Business requirement
  - Work item **87527** requests an **admin module for the booking services website**.
  - The lowest-risk scope is to add an **administrator role and admin-only pages inside the existing PHP application** because the supplied application is a server-rendered PHP site under `src/` (`src/booking.php`, `src/booking_list.php`, `src/booking_history.php`).
  - The admin module must provide operational visibility into the existing core records already present in source queries: **bookings, doctors, and patients** (`src/booking.php`, `src/booking_list.php`, `src/booking_history.php`).

- Functional requirement
  - Add **session-based admin authentication/authorization** aligned to the existing session pattern used for patients and doctors (`$_SESSION['patient_id']`, `$_SESSION['doctor_id']` in `src/booking.php`, `src/booking_list.php`, `src/booking_history.php`).
  - Add an **admin landing page** with summary counts for existing entities.
  - Add **admin list pages** for bookings, doctors, and patients using the current database tables already referenced by application code.
  - Add **admin booking-status management** using the existing booking status behavior already implemented in `src/booking_list.php` (`'canceled'`, `'completed'`).
  - Preserve **existing patient and doctor booking flows** without changing their access model or booking submission behavior.

- Current vs expected behavior
  - Current behavior
    - Patients can book appointments in `src/booking.php`.
    - Patients and doctors can view bookings in `src/booking_list.php`.
    - Doctors can view booking history in `src/booking_history.php`.
    - No admin session or admin-only pages are present in supplied source.
  - Expected behavior
    - Admin users can authenticate through the existing login/session architecture extension.
    - Admin users can access admin-only pages.
    - Admin users can view all bookings, all doctors, and all patients.
    - Admin users can update booking status from the admin module.
    - Non-admin users cannot load admin pages or execute admin actions.

- Assumptions and constraints
  - Decision: implement the feature as **additional PHP pages under `src/`** because all supplied runnable features use direct PHP page controllers in that folder (`src/booking.php`, `src/booking_list.php`, `src/booking_history.php`).
  - Decision: use **session-based role checks** because that is the established authorization mechanism in supplied files.
  - Decision: extend the current database with a minimal **`admins` table** because current authentication is table-backed by role-specific entities (`patients`, `doctors` queried directly in source).
  - Constraint: no explicit description or acceptance criteria were provided in work item 87527, so this specification defines the minimum testable feature set supported by current architecture.
  - Constraint: `login.php` and `base.php` are referenced by supplied source but their contents were not included, so changes must follow the current include and session-setting pattern already used by those files.

## Existing Application Analysis

- Relevant modules/components
  - `src/booking.php` handles patient-only appointment creation.
  - `src/booking_list.php` handles booking listing and booking status changes for existing roles.
  - `src/booking_history.php` handles doctor-only booking history display.
  - `db.php` is the shared database bootstrap included by all supplied pages.
  - `base.php` is the shared navigation/layout include used by all supplied pages.

- Existing business logic
  - Access control is implemented inline with `session_start()` and direct `$_SESSION` checks in each page (`src/booking.php`, `src/booking_list.php`, `src/booking_history.php`).
  - Booking creation inserts into `bookings` with denormalized patient snapshot fields in `src/booking.php`.
  - Booking status updates already exist in `src/booking_list.php` using direct SQL updates on `bookings.status`.
  - Doctor and patient details are loaded with SQL joins from `doctors` and `patients` in `src/booking_list.php` and `src/booking_history.php`.

- Existing APIs
  - No separate API layer is present in supplied files.
  - The application uses **page-controller request handling**:
    - GET `doctor_id` for booking setup in `src/booking.php`.
    - GET `cancel` and `complete` for status updates in `src/booking_list.php`.
    - POST form submit for booking creation in `src/booking.php`.
  - The admin module should use the same server-side page/request style instead of adding a new API surface.

- UI components
  - UI is server-rendered HTML with Bootstrap CSS and JS in all supplied pages.
  - Shared navigation is pulled from `base.php`.
  - Existing listing pages use simple Bootstrap tables (`src/booking_list.php`, `src/booking_history.php`).
  - Existing form handling is inline in the same PHP page as the HTML (`src/booking.php`).

- Database/data model
  - `bookings` is confirmed by `src/booking.php`, `src/booking_list.php`, and `src/booking_history.php` with fields including `id`, `patient_id`, `doctor_id`, `patient_name`, `patient_email`, `patient_phone`, `slot`, `time`, `disease`, `status`.
  - `patients` is confirmed by `src/booking.php`, `src/booking_list.php`, and `src/booking_history.php` with fields including `id`, `name`, `email`, `phone`.
  - `doctors` is confirmed by `src/booking.php` and `src/booking_list.php` with at least fields `id`, `name`.
  - No admin table is present in supplied source references.

- External integrations
  - Bootstrap, jQuery, and Popper are loaded from CDNs in supplied pages.
  - No external service integration is evidenced in the supplied files.
  - Database access is local via PDO through `db.php`.

## Impact Analysis

- Impacted applications/modules
  - The existing PHP web application under `src/` is the only impacted application.
  - Admin capability will be added as new pages and shared login/navigation updates in the same application structure used by current pages.

- Impacted services/components
  - Authentication/session handling will be extended to recognize an admin session alongside patient and doctor sessions, based on the current role-session pattern in `src/booking.php`, `src/booking_list.php`, `src/booking_history.php`.
  - Shared navigation in `base.php` will need admin-specific links and non-admin exclusion.
  - Database connectivity through `db.php` remains reused without architectural change.

- Impacted APIs
  - No standalone API is impacted.
  - Existing page-controller behavior will be extended with admin page routes and admin-triggered booking status updates.
  - `src/booking_list.php` should be reviewed to ensure current GET-based status actions remain restricted to intended non-admin behavior and are not broadened by the admin change.

- Impacted UI
  - `base.php` navigation requires admin menu entries.
  - New admin pages should follow the existing Bootstrap table/card style already used in `src/booking_list.php`, `src/booking_history.php`, and `src/booking.php`.
  - Login UI will require an admin path only through the existing login page implementation that is already referenced by redirects in supplied files.

- Impacted database objects
  - New `admins` table is required for administrator authentication.
  - Existing `bookings` table will be reused for admin status updates and list views.
  - Existing `doctors` and `patients` tables will be reused for admin list views.

- Impacted integrations
  - CDN-loaded UI libraries remain unchanged.
  - No new third-party integration is required.

- Potential downstream impact
  - Existing patient and doctor booking flows must continue to work unchanged because they rely on current session keys and current SQL paths (`src/booking.php`, `src/booking_list.php`, `src/booking_history.php`).
  - `base.php` changes can affect all pages because it is globally included.
  - Login changes can affect all role sign-in flows because all access control redirects point to `login.php`.

## Implementation Specification

- Proposed solution
  - Add a minimal **admin role implementation** using a new `admins` table, an admin session key, and new admin-only PHP pages inside `src/`.
  - Add:
    - an **admin dashboard** page with counts of bookings, doctors, and patients;
    - an **admin bookings** page showing all bookings and allowing status updates;
    - an **admin doctors** page listing all doctors;
    - an **admin patients** page listing all patients.
  - Reuse the existing **session + inline page controller + Bootstrap table** patterns from `src/booking.php`, `src/booking_list.php`, and `src/booking_history.php`.

- Code change areas
  - Update `src/login.php` to authenticate admins and set an admin session key, following the current session conventions referenced by existing files.
  - Update `src/base.php` to show admin navigation links only for admin sessions.
  - Add new admin page files in `src/` using the repository’s current flat PHP page naming convention.
  - Add a database migration or SQL script for the `admins` table in the same location/style used by the repository for schema setup; because no schema file was supplied, place the SQL in a new setup script under the existing source root convention used for direct PHP/database artifacts.

- Detailed implementation approach
  - Authentication and authorization
    - In `src/login.php`, add admin credential lookup against `admins`.
    - Set `$_SESSION['admin_id']` on successful admin login, matching the existing role-specific session key pattern from `$_SESSION['patient_id']` and `$_SESSION['doctor_id']`.
    - Ensure admin login does not overwrite patient or doctor sessions; clear conflicting role session keys during successful admin login.
    - In every new admin page, call `session_start()` and block access unless `$_SESSION['admin_id']` is set, redirecting to `login.php`.
  - Admin dashboard
    - Create `src/admin_dashboard.php`.
    - Query counts:
      - total bookings from `bookings`
      - total doctors from `doctors`
      - total patients from `patients`
    - Render summary cards and links to admin list pages.
  - Admin bookings page
    - Create `src/admin_bookings.php`.
    - Query all bookings joined with doctor and patient names using the same table relationships already used in `src/booking_list.php` and `src/booking_history.php`.
    - Display columns for booking id, patient name/email/phone, doctor name, slot, time, disease, status.
    - Handle booking status updates in this page using explicit GET or POST action parameters aligned to the current page-controller style in `src/booking_list.php`.
    - Allow only supported status transitions already evidenced in source: set `status` to `'canceled'` or `'completed'`.
    - Restrict status update execution to admin session only.
  - Admin doctors page
    - Create `src/admin_doctors.php`.
    - Query all doctors from `doctors`.
    - Display a table with the columns that exist in the current table schema as discoverable from the repository at implementation time; do not invent editable fields.
  - Admin patients page
    - Create `src/admin_patients.php`.
    - Query all patients from `patients`.
    - Display a table with at least id, name, email, and phone because those fields are confirmed in `src/booking.php`.
  - Navigation
    - Update `src/base.php` to include links to `admin_dashboard.php`, `admin_bookings.php`, `admin_doctors.php`, and `admin_patients.php` only for admin sessions.
    - Do not expose these links to patient or doctor sessions.
  - Security hardening within current architecture
    - Use prepared statements for all new queries, matching existing PDO usage.
    - Escape all rendered values with `htmlspecialchars()`, matching current output encoding patterns in supplied pages.
    - Validate booking id inputs as scalar numeric identifiers before executing updates.
    - Scope admin actions to admin session checks before any database write.

- Reuse vs new development
  - Reuse:
    - `db.php` for PDO access.
    - `base.php` for shared navigation/layout.
    - Existing session handling pattern from `src/booking.php`, `src/booking_list.php`, `src/booking_history.php`.
    - Existing bookings status values and SQL update behavior from `src/booking_list.php`.
    - Existing Bootstrap table/card markup style.
  - New development:
    - Admin authentication branch in `src/login.php`.
    - New admin-only pages under `src/`.
    - New `admins` database table.
    - Admin-specific navigation entries in `base.php`.

- Configuration changes
  - No new application service or environment configuration is required by the supplied architecture.
  - Database schema must be extended with an `admins` table containing the fields required by the existing login implementation pattern; minimum columns are `id`, an admin login identifier, and a password field.
  - Password storage must follow the same hashing/checking approach already used by the existing `login.php` implementation for current roles.

- Data migration requirements
  - Create the `admins` table.
  - Seed at least one administrator record for non-production/local verification using the current login password storage convention already used by the application.
  - No migration is required for `bookings`, `patients`, or `doctors` because admin pages are read-only for doctors/patients and reuse the current booking status field.

## Testing & Regression

- Unit test requirements
  - Validate admin session guard logic on each new admin page:
    - redirect to `login.php` without `$_SESSION['admin_id']`
    - allow page render with `$_SESSION['admin_id']`
  - Validate booking status update handler on the admin bookings page:
    - accepted values update `bookings.status`
    - invalid booking id does not update any row
    - non-admin session cannot execute the update path
  - Validate rendered output escapes booking, doctor, and patient values using `htmlspecialchars()`.

- Integration test requirements
  - Admin can log in through the updated `src/login.php` flow and reach `src/admin_dashboard.php`.
  - Admin dashboard count queries return values from existing `bookings`, `doctors`, and `patients` tables.
  - Admin bookings page loads joined booking/patient/doctor data from the existing tables.
  - Admin status changes on `src/admin_bookings.php` persist to `bookings.status` and are visible afterward.
  - Existing patient booking creation in `src/booking.php` still inserts a booking successfully.
  - Existing doctor booking history in `src/booking_history.php` still loads after admin changes.
  - Existing booking list behavior in `src/booking_list.php` still renders for patient and doctor sessions.

- Regression areas
  - `src/login.php` role authentication order and session assignment.
  - `src/base.php` navigation rendering for patient, doctor, and admin sessions.
  - `src/booking.php` patient-only redirect and form submission behavior.
  - `src/booking_list.php` current booking display and existing status actions.
  - `src/booking_history.php` doctor-only access and booking history query.

- Negative scenarios
  - Unauthenticated user requests any admin page and is redirected to `login.php`.
  - Patient session requests any admin page and is denied by redirect.
  - Doctor session requests any admin page and is denied by redirect.
  - Admin submits an invalid booking id for status update and no update occurs.
  - Admin submits an unsupported status action and no update occurs.
  - Empty tables render the same style of “no records found” message used by existing listing pages.

- Acceptance test scenarios
  - Administrator login
    - Given a valid admin account, the user can sign in and obtains access to admin navigation and admin pages.
  - Admin bookings visibility
    - Logged-in admins can see all bookings across all doctors and patients, including patient contact fields and booking status.
  - Admin bookings update
    - Logged-in admins can change a booking status to `canceled` or `completed`, and the changed value is visible on reload.
  - Admin doctors visibility
    - Logged-in admins can see a list of all doctors from the `doctors` table.
  - Admin patients visibility
    - Logged-in admins can see a list of all patients from the `patients` table.
  - Access restriction
    - Unauthenticated users, patients, and doctors cannot access admin pages or execute admin booking status changes.
  - Existing flow preservation
    - Patients can still create bookings in `src/booking.php`.
    - Doctors can still view booking history in `src/booking_history.php`.
    - Patients and doctors can still view their booking lists in `src/booking_list.php`.

## AI Implementation Context

- Implementation tasks
  - Inspect `src/login.php` and extend it to authenticate admins using the same credential verification approach used for current roles.
  - Inspect and update `src/base.php` to add admin-only navigation links.
  - Create `src/admin_dashboard.php` with admin session guard and summary queries.
  - Create `src/admin_bookings.php` with admin session guard, full booking list query, and booking status update handler.
  - Create `src/admin_doctors.php` with admin session guard and doctors list query.
  - Create `src/admin_patients.php` with admin session guard and patients list query.
  - Add SQL/schema artifact for `admins` table and seed data following repository conventions discovered during implementation.
  - Verify patient and doctor flows in `src/booking.php`, `src/booking_list.php`, and `src/booking_history.php` remain unchanged.

- Relevant coding standards
  - Use `session_start()` and direct session-key authorization checks, matching `src/booking.php`, `src/booking_list.php`, `src/booking_history.php`.
  - Use PDO prepared statements for database access, matching supplied source.
  - Use inline page-controller request handling in the PHP page file, matching supplied source.
  - Escape all output with `htmlspecialchars()`, matching existing rendered table cells in supplied files.
  - Keep UI in Bootstrap-based server-rendered HTML and the current flat PHP file structure under `src/`.

- Relevant existing patterns
  - Role guard pattern:
    - redirect to `login.php` and `exit()` when the session role key is missing (`src/booking.php`, `src/booking_history.php`).
  - Query-execute-fetch pattern:
    - `$stmt = $pdo->prepare(...); $stmt->execute([...]); $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);`
    - present in `src/booking.php`, `src/booking_list.php`, `src/booking_history.php`.
  - Shared include pattern:
    - include `db.php` and `base.php` from page files.
  - Table-based rendering pattern:
    - Bootstrap tables with empty-state row in `src/booking_list.php` and `src/booking_history.php`.
  - Inline status mutation pattern:
    - request parameter triggers `UPDATE bookings SET status = ...` in `src/booking_list.php`.

- Files/components to investigate
  - `src/login.php` for existing credential verification and session assignment.
  - `src/base.php` for shared navigation markup and role-based menu rendering.
  - `src/db.php` for connection details and any helper conventions.
  - `src/booking.php` for current patient access and DB coding style.
  - `src/booking_list.php` for existing booking status update behavior and bookings query structure.
  - `src/booking_history.php` for doctor-only access and bookings/history display pattern.
  - Existing schema/setup SQL files, if present in repository, to place the `admins` table creation using the repository’s current convention.

- Acceptance criteria
  - Only users authenticated with an admin session key can access `admin_dashboard.php`, `admin_bookings.php`, `admin_doctors.php`, and `admin_patients.php`.
  - Admin users can see:
    - dashboard summary counts for bookings, doctors, and patients;
    - all bookings with patient and doctor context;
    - all doctors;
    - all patients.
  - Admin users can change booking status only to `canceled` or `completed`.
  - Patient users and doctor users cannot access admin pages and cannot perform admin booking status updates.
  - Existing flows still work:
    - patient appointment creation in `src/booking.php`;
    - patient/doctor booking display in `src/booking_list.php`;
    - doctor booking history in `src/booking_history.php`.
  - The implementation remains inside the existing PHP application structure and reuses current session, PDO, and Bootstrap page patterns.