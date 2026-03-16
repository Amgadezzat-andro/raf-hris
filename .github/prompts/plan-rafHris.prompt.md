# Plan: RAF HRIS Full System Implementation

## TL;DR
Build a complete Laravel 12 + PostgreSQL HRIS system with 16 modules (Auth, HR Core, Attendance, Shifts, Leave, Overtime, Notifications, Swap Requests, Recruitment, Onboarding, Planning, Competency, Performance, Training, Payroll, Reporting). Single senior developer, 3-6 month timeline. Layer foundation infrastructure (migrations, seeders, base policies, Actions pattern) first, then implement modules in dependency order. Automated tests throughout. No production data seeding; demo seeders only.

---

## Sprint 1 Execution Checklist (Jira-Aligned)

### Ticket Order
1. `HRM-S1-BE-001` Foundation baseline
2. `HRM-S1-BE-002` Authentication APIs
3. `HRM-S1-BE-003` RBAC and scope enforcement
4. `HRM-S1-BE-004` Branches, departments, job titles
5. `HRM-S1-BE-005` Employees and contracts

### HRM-S1-BE-001: Laravel Baseline
- Laravel boots with PostgreSQL and successful DB connection.
- Sanctum, Spatie Permission, and audit-log package installed and configured.
- `Employee` is the single auth model for permissions.
- Stable module layout exists: `Actions`, `Http`, `Policies`, `Services`, `Support`.
- Shared API response contract exists for success, pagination, validation, and forbidden envelopes.
- `GET /api/v1/health` implemented.
- Local setup and environment variables documented.

### HRM-S1-BE-002: Authentication APIs
- `POST /api/v1/auth/login`, `POST /api/v1/auth/logout`, `GET /api/v1/auth/me` implemented.
- `POST /api/v1/auth/refresh` implemented only if refresh flow is enabled.
- Login logic stays in `LoginAction`/service, not controller.
- `auth/me` returns identity, active roles, permissions, and visible scopes.
- Inactive, suspended, and offboarded employees are rejected through one domain rule path.
- Invalid credential errors do not reveal whether email or password failed.

### HRM-S1-BE-003: Core RBAC and Scope
- Core permissions and roles seeded idempotently.
- `GET/POST /api/v1/roles` and `GET /api/v1/permissions` implemented.
- `POST /api/v1/employees/{employee}/roles` implemented for employee role bundles.
- Branch and department scope sync endpoints implemented.
- Policies enforce authorization; list visibility uses reusable branch/department query scopes.
- Role assignment and scope sync operations are transactional.
- Permission cache invalidates on role/permission changes.
- Unauthorized access returns the contract `403` response shape.

### HRM-S1-BE-004: Organization Master Data APIs
- Branch endpoints: list/create/update.
- Department endpoints: list/create/update with parent-child hierarchy.
- Job title endpoints: list/create/update linked to departments.
- Dedicated Form Requests per command.
- Domain rule prevents department tree cycles.
- API Resources return selector-friendly list payloads and detail payloads as needed.
- Audit actor fields come from auth context, never client payload.

### HRM-S1-BE-005: Employee Directory and Contracts
- Employee endpoints: list/create/detail/update.
- Contract endpoints: `GET /api/v1/contracts` module surface, employee contracts list/create, and contract detail/update.
- Employee code generation isolated in dedicated race-safe service.
- Employee lifecycle status values validated by domain rules.
- List endpoints are filterable and paginated.
- API Resources hide internal fields (password hashes, raw pivots, audit internals).

### Sprint 1 Definition of Done
- Controllers remain thin; business logic is in Actions/Services.
- Every write endpoint uses dedicated Form Requests.
- Policies and scope filters enforce all authorization and data visibility.
- Responses follow standard API envelope.
- Seeders are idempotent.
- Feature tests cover happy path, validation, and forbidden cases for all five tickets.

### Contract Non-Negotiables (Applied Globally)
- All timestamps are serialized as ISO 8601 UTC.
- Date-only fields use `YYYY-MM-DD`.
- Enum-like values are snake_case strings.
- Pagination envelope is standardized with default `per_page=20` and maximum `per_page=100`.
- Error semantics are fixed: `422` validation, `403` forbidden, `404` not found, `500` unexpected failure without internal trace leakage.
- Controllers never return raw Eloquent models; API Resources/transformers are mandatory.
- Authorization is enforced in policies and visibility scopes, never hard-coded in controllers.

## Implementation Phases

### Phase 1: Foundation & Infrastructure (Weeks 1-2)
**Goal**: Set up Laravel project structure, database schema, authentication, and base patterns.

**Steps**:
1. Laravel 12 project initialization with PostgreSQL config
2. Install dependencies: Sanctum, Spatie Laravel Permission, iamfarhad/laravel-audit-log
3. Create all migrations (ordered per contract: permissions → roles → org → employees → operations → recruitment → planning)
4. Seed base reference data: PermissionSeeder, RoleSeeder, RolePermissionSeeder (use findOrCreate pattern for idempotence)
5. Set up Spatie Permission caching clearance in seeders
6. Create base Policy class patterns for consistent authorization checks
7. Create base API Resource and API Request patterns with pagination and validation
8. Create base Action class scaffolding (with transaction support for multi-table operations)
9. Create custom exceptions for standard responses (ValidationException, ForbiddenException, NotFoundException)
10. Set up automated test structure: Feature tests for endpoints, Unit tests for Actions, factories for all models

**Key files to create**:
- `database/migrations/` — All migrations in order per Database Migration and Seeder Matrix
- `database/seeders/` — PermissionSeeder, RoleSeeder, RolePermissionSeeder, BranchSeeder, DepartmentSeeder, JobTitleSeeder
- `app/Policies/BasePolicy.php` — Common auth scaffold
- `app/Http/Requests/Api/V1/BaseFormRequest.php` — Common validation scaffold
- `app/Http/Resources/Api/V1/BaseResource.php` — Common pagination/serialization
- `app/Actions/BaseAction.php` — Common transaction/error handling
- `tests/Feature/AuthControllerTest.php` — Template
- `tests/Unit/Actions/LoginActionTest.php` — Template
- `database/factories/` — ModelFactory for each entity

**Verification**:
- All migrations run against test database without error
- Seeder idempotency verified: re-run produces no duplicates
- 20+ baseline tests pass (auth login/logout, role/permission structure)
- API envelope response (success, paginated, error) matches contract exactly

---

### Phase 2: System & Authentication Module (Week 2-3, parallel with Phase 1 completion)
**Goal**: Deliver health check, login, logout, token refresh, auth profile endpoints.

**Steps** (*depends on Phase 1*):
1. Create LoginRequest, RefreshTokenRequest, HealthResource, AuthSessionResource, AuthProfileResource
2. Implement LoginAction (verify credentials, generate Sanctum token, cache permissions)
3. Implement LogoutAction (revoke token)
4. Implement RefreshTokenAction (validate existing token, return new token)
5. Implement GetCurrentEmployeeProfileAction (retrieve auth employee with roles/permissions, serialize via AuthProfileResource)
6. Create AuthController with endpoints: POST /auth/login, POST /auth/logout, GET /auth/me, POST /auth/refresh, GET /health
7. Create AuthPolicy for self-access checks
8. Test all auth flows: successful login, invalid credentials, expired token refresh, logout

**Key files**:
- `app/Http/Controllers/Api/V1/AuthController.php`
- `app/Http/Requests/Api/V1/Auth/LoginRequest.php`, `RefreshTokenRequest.php`
- `app/Http/Resources/Api/V1/Auth/AuthSessionResource.php`, `AuthProfileResource.php`
- `app/Actions/Auth/LoginAction.php`, `LogoutAction.php`, `RefreshTokenAction.php`, `GetCurrentEmployeeProfileAction.php`
- `app/Policies/AuthPolicy.php`
- `tests/Feature/AuthControllerTest.php` (10+ scenarios)

**Verification**:
- Login returns valid bearer token
- Auth profile includes roles and permissions
- Logout revokes token (subsequent request fails)
- Token refresh issues new token without re-auth
- Health endpoint returns 200 without auth

---

### Phase 3: HR Core Module (Week 3-4)
**Goal**: Deliver organization structure endpoints (branches, departments, job titles, employees, contracts).

**Steps** (*depends on Phase 1*):
1. Create IndexBranchRequest, StoreBranchRequest, UpdateBranchRequest → BranchResource
2. Implement CreateBranchAction, UpdateBranchAction
3. Create BranchController with GET/POST /branches, PUT /branches/{branch}
4. Repeat for Departments (parent-child support), Job Titles, Employees
5. For Employees: unique employee_code generation via GenerateEmployeeCodeService; include summary (branch, dept, job_title nested)
6. Create IndexEmployeeRequest (with branch/dept filters), StoreEmployeeRequest, UpdateEmployeeRequest
7. Implement CreateEmployeeAction, UpdateEmployeeAction
8. Create Contracts endpoints: `GET /contracts` (module surface), `GET /employees/{employee}/contracts`, `POST /employees/{employee}/contracts`, `GET /contracts/{contract}`, `PUT /contracts/{contract}` with IndexEmployeeContractRequest, StoreContractRequest, UpdateContractRequest → ContractResource
9. Implement CreateContractAction, UpdateContractAction
10. Create BranchPolicy, DepartmentPolicy, EmployeePolicy, ContractPolicy (managers see own branch, admins see all)
11. Write comprehensive Feature tests for all CRUD endpoints with permission checks

**Key files**:
- `app/Http/Controllers/Api/V1/HrCore/{BranchController, DepartmentController, JobTitleController, EmployeeController, ContractController}.php`
- `app/Http/Requests/Api/V1/HrCore/{IndexBranchRequest, StoreBranchRequest, ...}.php` (20+ request classes)
- `app/Http/Resources/Api/V1/HrCore/{BranchResource, DepartmentResource, EmployeeDetailResource, EmployeeSummaryResource, ContractResource}.php`
- `app/Actions/HrCore/{CreateBranchAction, UpdateBranchAction, ...}.php` (8+ action classes)
- `app/Services/HrCore/GenerateEmployeeCodeService.php`
- `app/Policies/{BranchPolicy, DepartmentPolicy, EmployeePolicy, ContractPolicy}.php`
- `tests/Feature/HrCore/{BranchControllerTest, EmployeeControllerTest, ContractControllerTest}.php`

**Verification**:
- All GET endpoints return paginated results with filters working
- All POST/PUT endpoints validate required fields, return 422 on validation error
- Policy enforcement: non-admin cannot update other branches
- Employee code auto-generation works and is unique
- Contract uniqueness enforced (only one active per employee)

---

### Phase 4: RBAC & Employee Scope Module (Week 4)
**Goal**: Deliver role and permission management, employee scope assignment (branches, departments).

**Steps** (*depends on Phase 3*):
1. Create IndexRoleRequest, StoreRoleRequest → RoleResource
2. Create IndexPermissionRequest → PermissionResource
3. Implement CreateRoleAction (with permission assignment)
4. Create RoleController with GET/POST /roles, GET /permissions
5. Create AssignEmployeeRolesRequest → EmployeeRoleAssignmentResource
6. Implement AssignRolesToEmployeeAction
7. Create EmployeeRoleController with POST /employees/{employee}/roles
8. Create SyncEmployeeBranchesRequest, SyncEmployeeDepartmentsRequest
9. Implement SyncEmployeeBranchScopeAction, SyncEmployeeDepartmentScopeAction
10. Create EmployeeScopeController with PUT endpoints for scope sync
11. Create RBAC Policy (only super_admin, org_admin can manage roles and scopes)
12. Test: grant/revoke permissions, assign roles, sync branch/dept scopes

**Key files**:
- `app/Http/Controllers/Api/V1/RbacAndScope/{RoleController, PermissionController, EmployeeRoleController, EmployeeScopeController}.php`
- `app/Http/Requests/Api/V1/RbacAndScope/{IndexRoleRequest, StoreRoleRequest, AssignEmployeeRolesRequest, SyncEmployeeBranchesRequest}.php`
- `app/Http/Resources/Api/V1/RbacAndScope/{RoleResource, PermissionResource, EmployeeRoleAssignmentResource, EmployeeScopeResource}.php`
- `app/Actions/RbacAndScope/{CreateRoleAction, AssignRolesToEmployeeAction, SyncEmployeeBranchScopeAction, SyncEmployeeDepartmentScopeAction}.php`
- `app/Policies/RbacPolicy.php`
- `tests/Feature/RbacAndScope/RoleControllerTest.php`, `EmployeeScopeControllerTest.php`

**Verification**:
- Roles CRUD works with permission attachment
- Employee role assignment cascades to auth profile
- Scope sync updates branch/department access filters
- Only admins can manage roles and scopes

---

### Phase 5: Attendance & Shift Management Module (Weeks 5-6)
**Goal**: Deliver shift templates, shift assignments, check-in/check-out, attendance corrections.

**Steps** (*depends on Phase 3*):
1. Create ShiftTemplateResource, IndexShiftTemplateRequest, StoreShiftTemplateRequest, UpdateShiftTemplateRequest
2. Implement CreateShiftTemplateAction, UpdateShiftTemplateAction
3. Create ShiftTemplateController with GET/POST/PUT endpoints
4. Create ShiftAssignmentResource, IndexShiftAssignmentRequest, StoreShiftAssignmentRequest
5. Implement AssignShiftAction (assigns shift to employee on date, ensure no duplicates)
6. Create ShiftAssignmentController with GET/POST endpoints
7. Create CheckInAttendanceRequest, CheckOutAttendanceRequest
8. Implement CheckInAttendanceAction (record with timestamp, location if available)
9. Implement CheckOutAttendanceAction (validation: check_out > check_in)
10. Create AttendanceController with POST /attendance/check-in, POST /attendance/check-out, GET /attendance (paginated with filters)
11. Create CorrectAttendanceRequest, AttendanceResource
12. Implement CorrectAttendanceAction (as approval-based action, audit trail)
13. Create AttendancePolicy (employees check in/out own, managers correct, HR audits)
14. Create EmployeeShiftController for GET /employees/{employee}/shifts
15. Test: shift lifecycle, duplicate prevention, attendance record creation, corrections audit

**Key files**:
- `app/Http/Controllers/Api/V1/Attendance/{ShiftTemplateController, ShiftAssignmentController, AttendanceController, EmployeeShiftController}.php`
- `app/Http/Requests/Api/V1/Attendance/{IndexShiftTemplateRequest, StoreShiftTemplateRequest, CheckInAttendanceRequest, CheckOutAttendanceRequest, CorrectAttendanceRequest}.php`
- `app/Http/Resources/Api/V1/Attendance/{ShiftTemplateResource, ShiftAssignmentResource, AttendanceResource}.php`
- `app/Actions/Attendance/{CreateShiftTemplateAction, UpdateShiftTemplateAction, AssignShiftAction, CheckInAttendanceAction, CheckOutAttendanceAction, CorrectAttendanceAction}.php`
- `app/Policies/AttendancePolicy.php`
- `tests/Feature/Attendance/AttendanceControllerTest.php`, `ShiftManagementTest.php`

**Verification**:
- Shift templates CRUD works
- Shift assignment prevents duplicate employee+date
- Check-in records timestamp
- Check-out validates time ordering
- Corrections create audit trail
- Employees see only own records, managers see team per scope

---

### Phase 6: Leave Management Module (Weeks 6-7)
**Goal**: Deliver leave types, balances, leave requests with approval workflow.

**Steps** (*depends on Phase 3*):
1. Create LeaveTypeResource, IndexLeaveTypeRequest + LeaveTypeSeeder
2. Create LeaveBalanceResource, IndexLeaveBalanceRequest
3. Create AdjustLeaveBalanceRequest, LeaveBalanceSeeder
4. Implement AdjustLeaveBalanceAction (atomic balance update with audit)
5. Create EmployeeLeaveBalanceController for GET /employees/{employee}/leave-balances, POST adjust
6. Create StoreLeaveRequestRequest, ApproveLeaveRequest, RejectLeaveRequest → LeaveRequestResource
7. Implement SubmitLeaveRequestAction (validate date range, sufficient balance, create request)
8. Implement ApproveLeaveRequestAction (deduct balance atomically, audit)
9. Implement RejectLeaveRequestAction (decline request)
10. Create LeaveRequestController with GET/POST /leave-requests, POST approve/reject
11. Create LeaveRequestPolicy (employees submit own, managers in scope approve, HR audits)
12. Write tests: balance checks, date validation, approval workflow, balance deduction

**Key files**:
- `app/Http/Controllers/Api/V1/Leave/{LeaveTypeController, LeaveBalanceController, LeaveRequestController}.php`
- `app/Http/Requests/Api/V1/Leave/{IndexLeaveTypeRequest, StoreLeaveRequestRequest, ApproveLeaveRequest, RejectLeaveRequest, AdjustLeaveBalanceRequest}.php`
- `app/Http/Resources/Api/V1/Leave/{LeaveTypeResource, LeaveBalanceResource, LeaveRequestResource}.php`
- `app/Actions/Leave/{SubmitLeaveRequestAction, ApproveLeaveRequestAction, RejectLeaveRequestAction, AdjustLeaveBalanceAction}.php`
- `app/Policies/LeavePolicy.php`
- `database/seeders/LeaveTypeSeeder.php`
- `tests/Feature/Leave/LeaveRequestWorkflowTest.php`, `LeaveBalanceTest.php`

**Verification**:
- Leave balance created per employee + leave type
- Leave request validates available balance
- Approval atomically deducts balance
- Rejection keeps balance intact
- Employees see own, managers approve in scope

---

### Phase 7: Overtime & Notifications Module (Weeks 7-8)
**Goal**: Deliver overtime request workflow and notification infrastructure.

**Steps** (*depends on Phase 5, partial Phase 6*):
1. Create StoreOvertimeRequest, ApproveOvertimeRequest, RejectOvertimeRequest → OvertimeRequestResource
2. Implement SubmitOvertimeRequestAction (validate hours > 0, record request)
3. Implement ApproveOvertimeRequestAction (approval action), RejectOvertimeRequestAction
4. Create OvertimeRequestController with GET /overtime-requests, POST submit, POST approve/reject
5. Create MonthlyOvertimeSummaryRequest, OvertimeSummaryResource
6. Implement BuildMonthlyOvertimeSummaryAction (aggregate approved overtime by month)
7. Create OvertimeSummaryController with GET /overtime-summaries/monthly
8. Create StoreDeviceTokenRequest → DeviceTokenResource
9. Implement RegisterDeviceTokenAction (associate mobile/web device with employee for push notifications)
10. Create DeviceTokenController with POST /device-tokens
11. Create NotificationResource, IndexNotificationRequest
12. Implement MarkNotificationReadAction, MarkAllNotificationsReadAction
13. Create NotificationController with GET /notifications, POST /notifications/{id}/read, POST /notifications/read-all
14. Create NotificationPolicy (employees read own, no delete)
15. Test: overtime workflow, device token registration, notification retrieval, read status

**Key files**:
- `app/Http/Controllers/Api/V1/Overtime/{OvertimeRequestController, OvertimeSummaryController}.php`
- `app/Http/Controllers/Api/V1/Notifications/{DeviceTokenController, NotificationController}.php`
- `app/Http/Requests/Api/V1/Overtime/{StoreOvertimeRequest, ApproveOvertimeRequest, RejectOvertimeRequest}.php`
- `app/Http/Requests/Api/V1/Notifications/{StoreDeviceTokenRequest, MarkNotificationReadRequest, MarkAllNotificationsReadRequest}.php`
- `app/Http/Resources/Api/V1/Overtime/{OvertimeRequestResource, OvertimeSummaryResource}.php`
- `app/Http/Resources/Api/V1/Notifications/{NotificationResource, DeviceTokenResource}.php`
- `app/Actions/Overtime/{SubmitOvertimeRequestAction, ApproveOvertimeRequestAction, RejectOvertimeRequestAction, BuildMonthlyOvertimeSummaryAction}.php`
- `app/Actions/Notifications/{RegisterDeviceTokenAction, MarkNotificationReadAction, MarkAllNotificationsReadAction}.php`
- `app/Policies/{OvertimePolicy, NotificationPolicy}.php`
- `tests/Feature/Overtime/OvertimeWorkflowTest.php`, `Notifications/NotificationTest.php`

**Verification**:
- Overtime request workflow functions
- Monthly summary aggregates correctly
- Device tokens persist
- Notifications retrieve per employee
- Read status updates atomically

---

### Phase 8: Shift Swap & Task Management Module (Week 8)
**Goal**: Deliver peer-to-peer shift swap requests and task assignment.

**Steps** (*depends on Phase 5, Phase 3*):
1. Create StoreSwapRequestRequest, ApproveSwapRequest, RejectSwapRequest → SwapRequestResource
2. Implement SubmitSwapRequestAction (requester ≠ target, validate shifts exist, create request)
3. Implement ApproveSwapRequestAction (atomic swap, validate both employees still eligible)
4. Implement RejectSwapRequestAction
5. Create SwapRequestController with GET/POST /swap-requests, POST approve/reject
6. Create StoreTaskRequest, UpdateTaskRequest → TaskResource
7. Implement CreateTaskAction, UpdateTaskAction (task assignment with optional shift)
8. Create TaskController with GET/POST /tasks, PUT /tasks/{task}
9. Create TaskPolicy (creators/assignees modify, shift managers see shift tasks)
10. Test: swap request validation, approval swap logic, task CRUD, permission filtering

**Key files**:
- `app/Http/Controllers/Api/V1/SwapAndTasks/{SwapRequestController, TaskController}.php`
- `app/Http/Requests/Api/V1/SwapAndTasks/{StoreSwapRequestRequest, ApproveSwapRequest, RejectSwapRequest, StoreTaskRequest, UpdateTaskRequest}.php`
- `app/Http/Resources/Api/V1/SwapAndTasks/{SwapRequestResource, TaskResource}.php`
- `app/Actions/SwapAndTasks/{SubmitSwapRequestAction, ApproveSwapRequestAction, RejectSwapRequestAction, CreateTaskAction, UpdateTaskAction}.php`
- `app/Policies/{SwapRequestPolicy, TaskPolicy}.php`
- `tests/Feature/SwapAndTasks/SwapRequestWorkflowTest.php`, `TaskManagementTest.php`

**Verification**:
- Swap request validates different employees
- Approval atomically swaps shift assignments
- Tasks CRUD with shift linkage
- Scope-based filtering works

---

### Phase 9: Recruitment Module (Weeks 9-10)
**Goal**: Deliver job posting, applicant tracking, application workflow with stage progression.

**Steps** (*depends on Phase 3*):
1. Create IndexJobPostingRequest, StoreJobPostingRequest, UpdateJobPostingRequest → JobPostingResource
2. Implement CreateJobPostingAction, UpdateJobPostingAction
3. Create JobPostingController with GET/POST/PUT endpoints
4. Create IndexApplicantRequest, StoreApplicantRequest → ApplicantResource
5. Implement CreateApplicantAction
6. Create ApplicantController with GET/POST endpoints
7. Create IndexApplicationRequest, StoreApplicationRequest → ApplicationResource, HiredApplicationResource (different serialization)
8. Implement CreateApplicationAction (validate job posting exists, applicant exists, create with initial stage)
9. Create MoveApplicationStageRequest, MarkApplicationHiredRequest
10. Implement MoveApplicationStageAction (create application_stage_history audit row)
11. Implement MarkApplicationHiredAction (transition to hired, create contract)
12. Create ApplicationController with GET/POST /applications, POST move-stage, POST mark-hired
13. Create ApplicationStageLookupSeeder (define stages)
14. Create RecruitmentPolicy (recruiters manage, hr_managers view)
15. Test: applicant creation, application flow, stage progression, contract generation on hire

**Key files**:
- `app/Http/Controllers/Api/V1/Recruitment/{JobPostingController, ApplicantController, ApplicationController}.php`
- `app/Http/Requests/Api/V1/Recruitment/{IndexJobPostingRequest, StoreJobPostingRequest, StoreApplicantRequest, StoreApplicationRequest, MoveApplicationStageRequest, MarkApplicationHiredRequest}.php`
- `app/Http/Resources/Api/V1/Recruitment/{JobPostingResource, ApplicantResource, ApplicationResource, HiredApplicationResource}.php`
- `app/Actions/Recruitment/{CreateJobPostingAction, CreateApplicantAction, CreateApplicationAction, MoveApplicationStageAction, MarkApplicationHiredAction}.php`
- `app/Policies/RecruitmentPolicy.php`
- `database/seeders/ApplicationStageLookupSeeder.php`
- `tests/Feature/Recruitment/ApplicationWorkflowTest.php`

**Verification**:
- Job postings CRUD
- Applicants/applications creation with validation
- Stage history logged on transitions
- Employee creation on hire with contract generation
- Recruiter scope enforcement

---

### Phase 10: Onboarding & Offboarding Module (Week 10)
**Goal**: Deliver checklist and task management for employee onboarding and offboarding.

**Steps** (*depends on Phase 3, Phase 9*):
1. Create IndexOnboardingChecklistRequest, StoreOnboardingChecklistRequest → OnboardingChecklistResource
2. Implement CreateOnboardingChecklistAction
3. Create OnboardingChecklistController with GET/POST endpoints
4. Create StoreOnboardingTaskRequest, CompleteOnboardingTaskRequest → OnboardingTaskResource
5. Implement AddOnboardingTaskAction, CompleteOnboardingTaskAction (with completion_at, completed_by tracking)
6. Create OnboardingTaskController with GET/POST /checklists/{checklist}/tasks, POST tasks/{task}/complete
7. Repeat workflow for Offboarding (OffboardingChecklistController, OffboardingTaskController)
8. Create OnboardingPolicy, OffboardingPolicy (HR managers create, employees + managers complete)
9. Test: checklist CRUD, task creation, completion tracking, audit trail

**Key files**:
- `app/Http/Controllers/Api/V1/Onboarding/{OnboardingChecklistController, OnboardingTaskController}.php`
- `app/Http/Controllers/Api/V1/Offboarding/{OffboardingChecklistController, OffboardingTaskController}.php`
- `app/Http/Requests/Api/V1/Onboarding/{IndexOnboardingChecklistRequest, StoreOnboardingChecklistRequest, StoreOnboardingTaskRequest, CompleteOnboardingTaskRequest}.php`
- `app/Http/Resources/Api/V1/Onboarding/{OnboardingChecklistResource, OnboardingTaskResource}.php`
- `app/Actions/Onboarding/{CreateOnboardingChecklistAction, AddOnboardingTaskAction, CompleteOnboardingTaskAction}.php`
- `app/Policies/{OnboardingPolicy, OffboardingPolicy}.php`
- `tests/Feature/Onboarding/OnboardingWorkflowTest.php`, `OffboardingWorkflowTest.php`

**Verification**:
- Checklists and tasks CRUD
- Completion records completed_at and completed_by
- Scope-based access control
- Audit trail for all transitions

---

### Phase 11: Workforce Planning & Competency Module (Weeks 11)
**Goal**: Deliver headcount planning, forecasting, and competency assessment.

**Steps** (*depends on Phase 3*):
1. Create IndexHeadcountPlanRequest, StoreHeadcountPlanRequest → HeadcountPlanResource
2. Implement CreateHeadcountPlanAction
3. Create HeadcountPlanController with GET/POST endpoints
4. Create IndexWorkforceForecastRequest, StoreWorkforceForecastRequest → WorkforceForecastResource
5. Implement CreateWorkforceForecastAction
6. Create WorkforceForecastController with GET/POST endpoints
7. Create IndexCompetencyRequest, StoreCompetencyRequest → CompetencyResource + CompetencySeeder
8. Implement CreateCompetencyAction
9. Create CompetencyController with GET/POST endpoints
10. Create IndexCompetencyAssessmentRequest, StoreCompetencyAssessmentRequest → CompetencyAssessmentResource
11. Implement CreateCompetencyAssessmentAction, BuildEmployeeCompetencyProfileAction
12. Create CompetencyAssessmentController with GET/POST endpoints
13. Create EmployeeCompetencyProfileController with GET /employees/{employee}/competency-profile
14. Create PlanningPolicy (hr_managers, learning_officers create/view)
15. Test: planning CRUD, assessment creation, profile building

**Key files**:
- `app/Http/Controllers/Api/V1/Planning/{HeadcountPlanController, WorkforceForecastController}.php`
- `app/Http/Controllers/Api/V1/Competency/{CompetencyController, CompetencyAssessmentController, EmployeeCompetencyProfileController}.php`
- `app/Http/Requests/Api/V1/Planning/{IndexHeadcountPlanRequest, StoreHeadcountPlanRequest, IndexWorkforceForecastRequest, StoreWorkforceForecastRequest}.php`
- `app/Http/Requests/Api/V1/Competency/{IndexCompetencyRequest, StoreCompetencyRequest, StoreCompetencyAssessmentRequest}.php`
- `app/Http/Resources/Api/V1/Planning/{HeadcountPlanResource, WorkforceForecastResource}.php`
- `app/Http/Resources/Api/V1/Competency/{CompetencyResource, CompetencyAssessmentResource, CompetencyProfileResource}.php`
- `app/Actions/Planning/{CreateHeadcountPlanAction, CreateWorkforceForecastAction}.php`
- `app/Actions/Competency/{CreateCompetencyAction, CreateCompetencyAssessmentAction, BuildEmployeeCompetencyProfileAction}.php`
- `app/Policies/{PlanningPolicy, CompetencyPolicy}.php`
- `database/seeders/CompetencySeeder.php`
- `tests/Feature/Planning/HeadcountPlanningTest.php`, `Competency/CompetencyAssessmentTest.php`

**Verification**:
- Headcount plans and forecasts CRUD
- Competencies create and assessments record
- Profile aggregates latest assessment ratings

---

### Phase 12: Performance & KPI Module (Week 11-12)
**Goal**: Deliver KPI definitions, scorecards, and performance scoring.

**Steps** (*depends on Phase 3*):
1. Create IndexKpiRequest, StoreKpiRequest → KpiResource + KpiSeeder
2. Implement CreateKpiAction
3. Create KpiController with GET/POST endpoints
4. Create IndexScorecardRequest, StoreScorecardRequest → ScorecardResource
5. Implement SubmitScorecardScoresAction (validate scorecard period, KPI assignments, atomic score updates)
6. Create ScorecardController with GET/POST endpoints
7. Create SubmitScorecardScoresRequest, EmployeePerformanceSummaryRequest
8. Implement BuildPerformanceSummaryAction (aggregate scorecard scores per period, calculate aggregate)
9. Create EmployeePerformanceSummaryController with GET /employees/{employee}/performance-summary
10. Create PerformancePolicy (employees create/update own self-evals, managers score team, hr_managers view all)
11. Test: KPI CRUD, scorecard creation, score submission, summary aggregation

**Key files**:
- `app/Http/Controllers/Api/V1/Performance/{KpiController, ScorecardController, EmployeePerformanceSummaryController}.php`
- `app/Http/Requests/Api/V1/Performance/{IndexKpiRequest, StoreKpiRequest, IndexScorecardRequest, StoreScorecardRequest, SubmitScorecardScoresRequest}.php`
- `app/Http/Resources/Api/V1/Performance/{KpiResource, ScorecardResource, ScorecardScoresResource, PerformanceSummaryResource}.php`
- `app/Actions/Performance/{CreateKpiAction, SubmitScorecardScoresAction, BuildPerformanceSummaryAction}.php`
- `app/Policies/PerformancePolicy.php`
- `database/seeders/KpiSeeder.php`
- `tests/Feature/Performance/ScorecardWorkflowTest.php`

**Verification**:
- KPIs CRUD with weight, formula_type
- Scorecards creation per period
- Score submission with validation
- Performance summary aggregates

---

### Phase 13: Training & Learning Module (Week 12)
**Goal**: Deliver course management, enrollment, and quiz submission.

**Steps** (*depends on Phase 3*):
1. Create IndexCourseRequest, StoreCourseRequest → CourseResource + CourseSeeder
2. Implement CreateCourseAction
3. Create CourseController with GET/POST endpoints
4. Create StoreCourseEnrollmentRequest → EnrollmentResource
5. Implement EnrollEmployeeInCourseAction (enforce one enrollment per employee+course)
6. Create EnrollmentController with GET/POST endpoints
7. Create UpdateEnrollmentProgressRequest
8. Implement UpdateEnrollmentProgressAction (track progress_percent 0-100)
9. Create EmployeeEnrollmentController with POST /enrollments/{enrollment}/progress
10. Create EnrollmentQuizController with GET /quizzes/{quiz}, POST /quizzes/{quiz}/submit
11. Create QuizSubmissionResource, SubmitQuizRequest
12. Implement SubmitQuizAction (validate answers, score, update enrollment if passing)
13. Create TrainingPolicy (managers enroll team, employees view own enrollments, learning_officers manage course library)
14. Test: course CRUD, enrollment creation, progress tracking, quiz submission

**Key files**:
- `app/Http/Controllers/Api/V1/Training/{CourseController, EnrollmentController, QuizController}.php`
- `app/Http/Requests/Api/V1/Training/{IndexCourseRequest, StoreCourseRequest, StoreCourseEnrollmentRequest, UpdateEnrollmentProgressRequest, SubmitQuizRequest}.php`
- `app/Http/Resources/Api/V1/Training/{CourseResource, EnrollmentResource, QuizResource, QuizSubmissionResource}.php`
- `app/Actions/Training/{CreateCourseAction, EnrollEmployeeInCourseAction, UpdateEnrollmentProgressAction, SubmitQuizAction}.php`
- `app/Policies/TrainingPolicy.php`
- `database/seeders/CourseSeeder.php`
- `tests/Feature/Training/CourseEnrollmentTest.php`, `QuizSubmissionTest.php`

**Verification**:
- Courses CRUD
- Enrollment prevents duplicates
- Progress updates 0-100
- Quiz scoring and completion tracking

---

### Phase 14: Payroll Module (Weeks 13-14)
**Goal**: Deliver payroll run, line item generation, approval, and payslip generation.

**Steps** (*depends on Phase 3, Phase 5, Phase 6, Phase 7*):
1. Create IndexPayrollRunRequest, StorePayrollRunRequest → PayrollRunResource + PayrollRunSeeder
2. Implement CreatePayrollRunAction (period_start, period_end, branch_id, initial status)
3. Create ApprovePayrollRunRequest
4. Implement ApprovePayrollRunAction (atomic: validate run, generate line items for all employees in branch, transition to approved)
5. Implement GeneratePayrollLineItemsAction (calculate salary, deductions, allowances, overtime multiplier, leave deductions per contract and attendance data)
6. Create PayrollLineItemResource, IndexPayrollLineItemRequest
7. Create PayrollRunController with GET/POST /payroll-runs, POST /{payrollRun}/approve, GET /{payrollRun}/line-items
8. Create IndexPayslipRequest → PayslipResource, PayslipItemResource
9. Create PayslipController with GET /payslips, GET /payslips/{payslip}
10. Implement payslip generation as part of payroll approval (one payslip per employee per run)
11. Create PayrollPolicy (payroll_officers create/approve, employees view own)
12. Test: payroll run creation, line item generation with contract/attendance calculations, approval workflow, payslip retrieval

**Key files**:
- `app/Http/Controllers/Api/V1/Payroll/{PayrollRunController, PayrollLineItemController, PayslipController}.php`
- `app/Http/Requests/Api/V1/Payroll/{IndexPayrollRunRequest, StorePayrollRunRequest, ApprovePayrollRunRequest, IndexPayslipRequest}.php`
- `app/Http/Resources/Api/V1/Payroll/{PayrollRunResource, PayrollLineItemResource, PayslipResource, PayslipItemResource}.php`
- `app/Actions/Payroll/{CreatePayrollRunAction, ApprovePayrollRunAction, GeneratePayrollLineItemsAction}.php`
- `app/Services/Payroll/SalaryCalculationService.php` (encapsulate salary, deductions, allowances logic)
- `app/Policies/PayrollPolicy.php`
- `database/seeders/PayrollRunSeeder.php`
- `tests/Feature/Payroll/PayrollRunApprovalTest.php`, `SalaryCalculationTest.php`

**Verification**:
- Payroll run creation per branch-period
- Line item generation calculates salary + deductions + allowances + overtime
- Approval generates payslips atomically
- Employees see own payslips only
- Payroll officers approve and generate

---

### Phase 15: Reporting Module (Week 14-15)
**Goal**: Deliver report definition, ad-hoc report execution, and filtering.

**Steps** (*depends on Phase 3, and various transactional data*):
1. Create IndexReportDefinitionRequest, StoreReportDefinitionRequest → ReportDefinitionResource + ReportDefinitionSeeder
2. Implement CreateReportDefinitionAction (name, module, filter_schema_json)
3. Create ReportDefinitionController with GET/POST endpoints
4. Create ShowReportRunRequest, RunReportDefinitionRequest
5. Implement RunReportDefinitionAction (validate module, apply filters, execute query builder, serialize results)
6. Create ReportRunResource
7. Create ReportRunController with POST /report-definitions/{definition}/run, GET /report-runs/{reportRun}
8. Design report modules: employee_roster, attendance_summary, leave_usage, overtime_summary, payroll_summary, competency_matrix, training_progress
9. Create ReportQueryBuilder service per module (encapsulate query logic)
10. Create ReportPolicy (hr_managers, payroll_officers, learning_officers run reports per area)
11. Test: report definition CRUD, execution with filters, result serialization

**Key files**:
- `app/Http/Controllers/Api/V1/Reports/{ReportDefinitionController, ReportRunController}.php`
- `app/Http/Requests/Api/V1/Reports/{IndexReportDefinitionRequest, StoreReportDefinitionRequest, RunReportDefinitionRequest}.php`
- `app/Http/Resources/Api/V1/Reports/{ReportDefinitionResource, ReportRunResource}.php`
- `app/Actions/Reports/RunReportDefinitionAction.php`
- `app/Services/Reports/{EmployeeRosterReportBuilder, AttendanceSummaryReportBuilder, LeaveUsageReportBuilder, OvertimeSummaryReportBuilder, PayrollSummaryReportBuilder, CompetencyMatrixReportBuilder, TrainingProgressReportBuilder}.php`
- `app/Policies/ReportPolicy.php`
- `database/seeders/ReportDefinitionSeeder.php`
- `tests/Feature/Reports/ReportExecutionTest.php`

**Verification**:
- Report definitions CRUD
- Execution returns filtered results
- Results serialize to API format
- Scope-based access (payroll officer sees payroll reports, etc.)

---

### Phase 16: Testing & Documentation (Week 15-16)
**Goal**: Achieve 70%+ code coverage, document API, finalize seeding.

**Steps**:
1. Review all Feature tests: ensure 10+ scenarios per module endpoint
2. Add Unit tests for all Actions (input validation, business logic, transaction behavior)
3. Add Policy tests (authorization enforcement per role)
4. Create Factory definitions for all models with realistic relationships
5. Generate OpenAPI/Swagger documentation from request/resource annotations (optional: use Scramble or similar)
6. Finalize demo seeders: environment-gated production vs. demo data
7. Document seeder order and how to reset database locally
8. Test full seeding flow: seeders run without error, all relationships intact
9. Run all tests, verify no regressions

**Key files**:
- `tests/Feature/**` — All Feature tests per module
- `tests/Unit/Actions/**` — All Action unit tests
- `tests/Unit/Policies/**` — All Policy unit tests
- `database/factories/**` — All model factories
- `phpunit.xml` — Coverage config
- `README.md` or `docs/INSTALLATION.md` — Seeding instructions
- `routes/api.php` — Finalized route definitions

**Verification**:
- All tests pass ($ php artisan test)
- Code coverage ≥ 70%
- No security warnings ($ php artisan security-check or similar)
- Seeding works: $ php artisan migrate:fresh --seed
- API responds to health check, login, and sample data endpoints

---

## Dependency Graph & Parallelization Opportunities

**Critical path** (sequential dependencies):
1. Phase 1 (Foundation) → all others
2. Phase 2 (Auth) → all others (optional: can pair with early modules)
3. Phase 3 (HR Core) → Phase 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15

**Parallelization after Phase 3**:
- Phases 4, 5, 6, 7 can start simultaneously (independent modules)
- Phase 8 can start after Phase 5
- Phase 9 can start in parallel
- Phase 10 (depends on Phase 9 minimal hiring feature)
- Phase 11, 12, 13 can start independently
- Phase 14 (Payroll) depends on Phase 5 (Attendance), Phase 6 (Leave), Phase 7 (Overtime) — start after all three
- Phase 15 (Reporting) depends on data from all modules — start after Phase 14 or parallel for template definition

**Realistic single-developer schedule** (3-6 months, realistic pace):
- Weeks 1-2: Phase 1 (Foundation)
- Weeks 2-3: Phase 2 (Auth) + early Phase 3 (HR Core)
- Weeks 3-5: Phase 3, 4, 5 overlap
- Weeks 6-8: Phase 6, 7, 8 overlap
- Weeks 8-10: Phase 9, 10
- Weeks 11-12: Phase 11, 12, 13 overlap
- Weeks 13-14: Phase 14 (Payroll)
- Weeks 14-15: Phase 15 (Reporting)
- Weeks 15-16: Phase 16 (Testing & polish)

**Total**: ~4 months focused, 6 months realistic with interruptions/refactoring buffer.

---

## Key Architecture Patterns

### Actions Pattern
Every approval, state transition, and multi-table operation uses a dedicated Action class:
```
app/Actions/{Module}/{ActionName}Action.php
- Call via: new ActionName()->execute($input)
- Handle transactions internally
- Throw custom exceptions (handled by middleware)
- Logged/audited via observers or inline
```

### Policy-Based Authorization
```
app/Policies/{Module}Policy.php
- Used in controllers: $this->authorize('update', $resource)
- Query scopes add branch/dept filters for list endpoints
```

### Form Request Validation
```
app/Http/Requests/Api/V1/{Module}/{ActionName}Request.php
- Validates all input before controller
- Returns 422 if invalid
- Messages match contract exactly
```

### API Resource Serialization
```
app/Http/Resources/Api/V1/{Module}/{ResourceName}Resource.php
- Single resource: new Resource($model)
- Pagination: Resource::collection($paginated)
- Nested relationships: always Resource objects (no raw models)
```

### Database Transactions
Multi-table ops (Approval actions, balance adjustments, payroll) always:
```php
DB::transaction(function() {
  // 1. Update multiple tables
  // 2. Audit log
  // 3. Return result
}, attempts: 3);
```

### Audit Trail via Observers
```
app/Observers/{Model}Observer.php
- created(), updated(), deleted() hooks
- Dispatch to iamfarhad/laravel-audit-log
- No inline audit logic in controllers
```

---

## Scope Boundaries

**Included**:
- All 16 modules with endpoints per contract
- Sanctum token authentication
- Spatie role/permission framework
- Database migrations and seeders (reference data + demo)
- Form Requests, API Resources, Policies, Actions
- Feature + Unit tests with 70%+ coverage
- Audit logging via package

**Excluded**:
- Docker, deployment, or infrastructure
- Frontend or mobile client code
- Mobile push notifications (infrastructure; device token endpoints provided)
- Real-time WebSocket communication
- Advanced reporting (analytics engines, BI dashboards)
- Single Sign-On (SSO) / external auth providers
- API versioning beyond /v1
- Custom MCP servers or extensions

**Optional enhancements** (post-delivery):
- GraphQL API layer
- Event broadcasting for real-time updates
- Custom report visualization in frontend
- Batch job processing (payroll imports)
- Rate limiting and request throttling

---

## Decisions & Assumptions

1. **Enum storage**: Use indexed varchar strings (not DB enums) — easier to manage and client-compatible.
2. **Transactions**: All approval/balance/payroll actions use DB transactions with 3 retry attempts.
3. **Audit logging**: Use iamfarhad/laravel-audit-log package + model observers (not manual inline).
4. **Scope filtering**: Employees see own records + manager-team scope. Admins see all. Applied at query layer, not serialization.
5. **Seeding order**: Reference data first (roles, perms, branches, etc.), then demo transactional data, environment-gated.
6. **One active contract per employee**: Enforced by business logic in UpdateContractAction (partial unique index optional).
7. **Leave balance atomicity**: Approval deducts atomically; rejection does not. Adjust action is separate for manual corrections.
8. **Payslip generation**: Automatic on payroll approval (state != manual request).
9. **Device tokens**: Stored per notification infrastructure (no push gateway handling in this scope).
10. **Role inheritance**: No role inheritance; use permission bundles (sync multiple permissions per role).

---

## Testing Strategy

**Feature Tests** (10+ per module endpoint):
- Happy path (successful request)
- Validation errors (missing/invalid fields)
- Authorization failures (403 Forbidden)
- State transition failures (e.g., approve already-approved request)
- Scope filtering (employee sees own, manager sees team)

**Unit Tests** (per Action class):
- Input validation
- Business logic (balance deduction, swap logic, etc.)
- Transaction rollback on error
- Audit trail creation

**Policy Tests**:
- Authorized user can act
- Unauthorized user is denied

**Factory Usage**:
- All tests seed via factories, not hardcoded data
- Factories define realistic relationships

---

## Verification Checklist

**Pre-launch verification**:
- [ ] All 16 module controllers with GET/POST/PUT endpoints per contract
- [ ] All Form Requests validate per spec (422 on validation error)
- [ ] All API Resources serialize per contract format
- [ ] All Actions handle transactions and audit
- [ ] All Policies enforce authorization
- [ ] All tests pass: $ php artisan test
- [ ] Code coverage ≥ 70%: $ php artisan test --coverage
- [ ] Database seeding works: $ php artisan migrate:fresh --seed
- [ ] Health endpoint responds: GET /api/v1/health
- [ ] Login endpoint returns token: POST /api/v1/auth/login
- [ ] Auth middleware rejects invalid tokens: 401 Unauthorized
- [ ] Pagination works (per_page, page, total, last_page): GET /api/v1/{resource}?per_page=50&page=2
- [ ] Scope filters work (employees see own, managers see branch): Tested per policy
- [ ] Audit logs created: Check audit_logs table after approval/update
- [ ] Error responses match envelope: { "message": "...", "errors": {...} }

---

## Further Considerations

1. **Database migrations rollback strategy**: Design for safe down() methods; test fresh migrations regularly.
2. **Large dataset performance**: Add indexes per ERD; consider pagination limits if reporting becomes slow.
3. **Concurrent request handling**: Optimistic locking (timestamps) optional for high-concurrency scenarios (payroll, leave approval).
4. **Real-time notifications**: Device token infrastructure ready; push gateway integration deferred (external service).
5. **Data validation**: Consider additional runtime checks for business constraints (e.g., closing date > today for job postings) in Actions, not just Form Requests.

---

## Time Estimates (Single Senior Developer)

| Phase | Duration | Key Risk | Mitigation |
|-------|----------|----------|-----------|
| 1 | 1-2 weeks | Scope creep on infrastructure | Use minimal base classes; add later if needed |
| 2 | 1 week | Auth edge cases (token expiry) | Thorough test coverage before moving on |
| 3 | 2 weeks | Employee code generation conflicts | Pre-generate codes, test uniqueness |
| 4 | 1 week | Role/permission cascading | Use Spatie sync methods, clear cache |
| 5 | 2 weeks | Attendance timestamp edge cases | Validate time ordering, geo data optional |
| 6 | 1-2 weeks | Leave balance atomicity | Use transactions, test concurrent requests |
| 7 | 1-2 weeks | Device token registration | Start with mock, integrate gateway later |
| 8 | 1 week | Swap logic validation | Clear business rules before coding |
| 9 | 2 weeks | Applicant duplication | Unique email enforcement, test |
| 10 | 1 week | Onboarding complexity | Use simple checklists first, expand templates |
| 11 | 1 week | Planning forecast accuracy | Baseline with actual data, refine formulas |
| 12 | 1-2 weeks | KPI formula complexity | Start with simple aggregates, add later |
| 13 | 1-2 weeks | Quiz logic and scoring | Parameterize quiz types, test permutations |
| 14 | 2-3 weeks | **Payroll calculation complexity** | **Build salary calculator service early, test heavily** |
| 15 | 1-2 weeks | Report query performance | Build report query builders incrementally |
| 16 | 2 weeks | Test coverage gaps | Run coverage analysis per module as complete |

**Total**: 16-24 weeks calendar for one developer (about 4-6 months realistic with overlap and buffer).
