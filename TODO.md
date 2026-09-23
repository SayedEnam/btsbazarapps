# Monthly Bazar — Build Tracker

Tracks progress against the phased build order (spec section 61). Update
this file as each module ships — check items off, don't delete them, so the
history of what was built when stays visible.

Legend: `[x]` done · `[~]` partial / stubbed · `[ ]` not started

---

## Phase 1 — Foundation ✅ DONE

- [x] Laravel 12 project (PHP 8.3, MySQL, no Vite — CDN Bootstrap 5 + Bootstrap Icons)
- [x] Hand-rolled authentication (Login, Forgot Password, Reset Password) — Livewire 3.8, rate-limited
- [x] Roles & permissions schema (`roles`, `permissions`, `role_user`, `permission_role`)
- [x] `User`, `Role`, `Permission` models with `hasRole` / `hasPermission`
- [x] Global `Gate::before` for permission-slug abilities (`can('users.view')`) + `UserPolicy` for object-level invariants (no self-delete, protect last Super Admin)
- [x] `role` / `permission` route middleware, `EnsureUserIsActive` global middleware
- [x] Admin layout: sidebar (full IA from spec, unbuilt sections marked "Soon"), navbar, toast component
- [x] Full CRUD: Users, Roles, Permissions (search, filter, Bootstrap pagination, modals)
- [x] Profile page (update info / change password)
- [x] Seeders: full permission catalogue, 4 system roles, Super Admin, 20 demo users across every role/status
- [x] 17 passing tests (auth, gating, CRUD invariants, seeder)

---

## Phase 2 — Public Website ✅ DONE

- [x] Public layout (navbar, footer, Bootstrap 5, mobile-first) — `layouts/public.blade.php`, classic `@extends`/`@yield` (not Livewire, so no `$slot` injection)
- [x] Home (hero, intro, package highlight, benefits, how-it-works, referral explainer, why-us, FAQ, CTA, contact)
- [x] About (renders admin-editable `Page` content)
- [x] Packages (public-facing display of active packages, pulled from real `packages` table)
- [x] How It Works
- [x] Contact Us (Livewire form → `contact_messages` table + company info + map embed)
- [x] Terms & Conditions (admin-editable content)
- [x] Privacy Policy (admin-editable content)
- [x] Settings-driven company info (`settings` table, cached `Setting::get()`, Admin > Settings form) — no hardcoding
- [x] Admin > Pages module (edit About/Terms/Privacy content, records `updated_by`)
- [x] Sidebar restructured to match spec's IA — added a top-level "Website" section (Pages, Settings)
- [x] `packages` table/model pulled forward from Phase 5 (data model only — admin CRUD is still Phase 5's job; seeded with "Standard Membership — ৳1,000")
- [x] 12 new tests (public pages, settings, pages CRUD, contact form) — 29 total passing
- [x] Full Playwright pass: desktop + mobile nav, contact form submit, settings save reflected live on public site, zero console/HTTP errors

**Bug found and fixed during this phase:** Blade's `@section('name', $value)` treats a literal `null` as "capture this as a block" (its internal sentinel for the no-second-argument form), not as "the value is null." Passing a nullable Eloquent attribute (`$page->meta_description`) straight into that second argument silently opened an `ob_start()` on every request that never had a matching `@endsection` to close it — a real per-request memory leak that produced no error, no log entry, and no failed assertion, only PHPUnit's "risky: did not close its own output buffers" warning. Fixed by coalescing to `''` before handing it to `@section()`; regression test asserts `ob_get_level()` is unchanged across the request. Worth remembering for any future `@section('x', $model->nullableColumn)` call.

---

## Phase 3 — Customer Registration & Dashboard ✅ DONE

- [x] Customer registration (normal + `/r/{code}` referral capture — code is stashed in session, not relied on staying in the URL)
- [x] `customers` table + `Customer` model (belongsTo User), `referrals` table + `Referral` model (officer ↔ customer, unique per customer so a referral relationship is permanent)
- [x] `referral_code` pulled forward onto `users` (nullable, unique, `REF-0001` style via `User::generateReferralCode()`) so referral links resolve to a real, validated officer today — the fuller Officer profile (employee id, department, designation, salary) is still Phase 4's job
- [x] Customer status enum (Pending/Active/Inactive/Suspended) — distinct from the User account status (Active/Inactive/Suspended): account access vs. membership state are different concerns
- [x] Customer dashboard (account status, membership status, package placeholder, referral officer, notifications shell)
- [x] Customer profile (personal details + profile photo upload, change password)
- [x] Registration wrapped in `DB::transaction()` — user, role, customer profile, and referral all succeed or none do
- [x] `role:customer` route group + `EnsureUserHasRole`-based 403 for every other role; Login redirects Customer-role users to their own dashboard
- [x] Public nav/CTAs wired to the real `/register` route (previously placeholder links to Contact)
- [x] Seeded 8 officer referral codes + 9 Customer profiles (6 with a real referral relationship, 3 direct) on top of Phase 1's demo users
- [x] 12 new tests (registration happy/error paths, referral capture, dashboard authorization incl. cross-role and cross-customer isolation) — 41 total passing
- [x] Full Playwright pass: referral link → registration form → dashboard, profile update, invalid referral code handling, seeded customer with real referral officer — zero console/HTTP errors

---

## Phase 4 — Officers & Referral System ✅ DONE

- [x] `departments`, `designations`, `officers` tables + models (employee id, department, designation, salary, address, profile picture — on top of the existing `users.referral_code` from Phase 3)
- [x] Officer management CRUD (Admin) — `OfficerForm` creates the `User` + role + referral code + `Officer` profile atomically in one transaction; editing never touches account status (see bug note below)
- [x] Department / Designation CRUD (Admin) — deleting one unassigns it from officers rather than failing or cascading
- [x] QR code generation for the referral link + copy-to-clipboard, on the Officer's own Profile page
- [x] Officer dashboard (referral stats, Chart.js doughnut of referred-customer status) — Pending/Approved/Rejected application counts and package value are honestly labeled as "coming once the Application module ships" rather than faked
- [x] Officer's own-referrals list (search/sort/filter/pagination) — hard-scoped to `Auth::id()` with **no ID parameter anywhere in the component**, so there is nothing to tamper with (spec section 16)
- [x] Login redirects Marketing Officer role to `officer.dashboard` (previously fell through to a 403 on `/admin/dashboard`)
- [x] Admin sidebar Officers section activated (Officers, Departments, Designations); Salary Profiles stays "Soon" (Phase 6)
- [x] Seeded 8 officer profiles (employee id, department, designation, salary, joining date) on the existing Phase 3 demo officers
- [x] 12 new tests (officer CRUD incl. duplicate employee id, cross-officer/cross-role authorization, department/designation CRUD) — 53 total passing
- [x] Full Playwright pass: admin officer create/list, department/designation CRUD, officer login → dashboard → referrals → profile with working QR code and copy button, cross-officer isolation verified in a real browser — zero console/HTTP errors

**Two real bugs found and fixed during this phase:**
1. **Query-grouping bug (same class as a Phase 1 finding):** the Officers search query chained `->orWhere('employee_id', ...)` outside the nested closure wrapping the `whereHas('user', ...)` OR-group. Left as written, that `orWhere` attaches to the *outer* query and can silently short-circuit the status/department filters via SQL operator precedence. Fixed by wrapping the whole OR-group in one `where(fn ($q) => ...)`, matching the safe pattern already used in `Admin\Users\Index`.
2. **QR code CDN library was never actually browser-ready:** `qrcode@1.5.3`'s `/lib/browser.min.js` build still contains raw CommonJS `require()` calls — it's meant for a bundler, not a `<script src>` tag, and threw `require is not defined` in the browser (verified via a real Playwright run, not just "looks right"). Swapped to `qrcodejs@1.0.0` from cdnjs, which is a genuine standalone IIFE (`new QRCode(container, options)` rendering into a `<div>` rather than `QRCode.toCanvas` on a `<canvas>`). Worth remembering: a package's npm "browser" field existing doesn't guarantee the file is actually script-tag-safe — load it in a real browser before trusting it.

**Note on Officer deletion:** removing an Officer from the admin screen soft-deletes the `Officer` profile only — the underlying `User` account, Marketing Officer role, and referral code are left untouched (managed from the Users screen instead). This keeps "no longer an active officer" reversible and never silently deletes someone's login or orphans their historical referrals.

---

## Phase 5 — Packages & Applications ✅ DONE

- [x] Package management CRUD (Admin) — image upload, soft delete (existing applications keep their own price snapshot regardless of later edits/deletes)
- [x] `applications` table (application_number `APP-2026-000001` style, package_price snapshot at apply-time, officer_id independently reassignable from the permanent `referrals` relationship)
- [x] `application_status_histories` (full audit trail — every transition, including the initial submission, is a row)
- [x] `ApplicationStatus` enum encodes the legal transition graph (`canTransitionTo()`) — Pending/UnderReview → Approved/Rejected/Cancelled; Approved → Cancelled only; Rejected/Cancelled are terminal
- [x] `app/Actions/SubmitApplication.php` and `app/Actions/ChangeApplicationStatus.php` — the actual business logic, `DB::transaction()`-wrapped, reused identically by the Admin and Officer review screens (not duplicated per role)
- [x] Application submission (Customer) — blocks a second application while one is Pending/Under Review, both proactively (nicer UX) and at the transaction level with a row lock (actually safe under concurrency)
- [x] Approval activates the customer's membership; rejection stores a reason and never demotes a customer already Active through another application (spec section 50)
- [x] Admin application management — filters (search, status, package, officer, date range), view details with full status history, approve/reject/cancel/reassign officer, invalid transitions surfaced as a toast instead of a 500
- [x] Officer application review — hard-scoped to `officer_id = Auth::id()` via `ownApplicationOrFail()`; acting on another officer's application 404s, it doesn't silently no-op (spec section 16)
- [x] Notifications (database channel): officer notified on new submission, customer notified on approval/rejection — surfaced in a basic panel on the Customer/Officer dashboards (the full navbar dropdown + dedicated Notifications page is still Phase 8's job; Admin isn't notified on every application, since the admin panel already shows everything directly)
- [x] Customer/Officer dashboards now show real application data — the "coming once the Application module ships" placeholders from Phases 3–4 are gone
- [x] Sidebar Applications/Packages activated; public Packages/Home "Apply Now" now route logged-in customers straight to the apply flow instead of back through registration
- [x] Seeded 9 applications covering all 5 statuses with realistic status histories
- [x] 16 new tests (price snapshotting, duplicate-application guard, full transition graph incl. terminal states, cross-officer isolation via 404, reassignment leaving the referral untouched, package CRUD) — 71 total passing
- [x] Full Playwright pass: admin package/application CRUD, status history modal, reassign, customer reapply-after-cancellation and duplicate-block, officer review — zero console/HTTP errors

**Design note — Application.officer_id vs. the referral relationship:** these are deliberately two different things. `referrals.officer_id` is permanent (who first referred this customer, spec section 10); `applications.officer_id` is who's *currently handling this specific application* and can be reassigned by an admin (spec section 13) without touching the referral. `SubmitApplication` seeds the application's officer from the referral as a sensible default, but they diverge the moment someone reassigns.

---

## Phase 6 — Salary & Payroll ✅ DONE

- [x] `salary_profiles` — allowances/deduction as flat columns per spec section 18's own field list, not separate `allowances`/`deductions` tables (section 30's rougher outline never specifies fields those would need beyond what's already here — documented simplification)
- [x] Net salary computed server-side via `SalaryProfile::netSalary()` using `bcadd`/`bcsub` (never floats, never trusts a client-supplied figure) — spec section 18
- [x] `payrolls` + `payroll_items` (one batch per month, one item per officer, snapshotting the salary profile's figures so a later salary change never rewrites a past month — same principle as `Application.package_price`)
- [x] `PayrollStatus` enum: Draft → Approved → Paid, enforced via `canTransitionTo()`; skipping straight to Paid is rejected
- [x] `app/Actions/GeneratePayroll.php` — re-running it for a month that already exists only tops up officers who don't have an item yet (an officer added mid-month), it never overwrites; `app/Actions/ChangePayrollStatus.php` handles the status transitions
- [x] `salary_payments` (Cash/Bank/Mobile Banking/Other) — accounting record only, no payment gateway, matching Package/Application's payment model from Phase 1
- [x] Admin: Salary Profiles CRUD (activating one profile auto-deactivates the officer's previous Active one, in the same transaction), Payroll (generate/view/approve/pay), Salary Payments (record)
- [x] New `Salary Profiles` permission group, separate from `Payroll` (setting someone's base pay is a different concern from generating/approving a payroll run)
- [x] Sidebar Officers > Salary Profiles and Accounts > Payroll/Salary Payments activated
- [x] Seeded 8 salary profiles, a fully processed last-month payroll (generated → approved → paid, with matching salary payments), and a Draft this-month payroll ready to test the approval flow
- [x] 13 new tests (net-salary math, duplicate-generation is additive not destructive, price-snapshot survives a later salary change, full Draft→Approved→Paid transition graph incl. the skip-approval rejection, salary profile requires a real officer) — 83 total passing
- [x] Full Playwright pass: salary profile list, payroll generate/view/approve/mark-paid, salary payments list — zero console/HTTP errors

**Real bug found and fixed during this phase:** a plain `'date'` Eloquent cast still serializes to full `'Y-m-d H:i:s'` on save by default — it does not store a bare date just because the cast is named `date`. `GeneratePayroll`'s `Payroll::firstOrCreate(['month' => $x->toDateString()], ...)` queried with a plain date string that never matched the datetime-formatted value actually in the column, so the idempotency check silently failed and a second seeder run tried to insert a duplicate month, tripping the real unique constraint — caught by the full-seeder idempotency test, not by feature logic. Fixed with an explicit `'date:Y-m-d'` format on `Payroll.month`, and applied the same fix defensively to every other plain `'date'` cast in the app (`Officer.joining_date`, `Application.application_date`, `Customer.date_of_birth`, `SalaryProfile.effective_from`, `SalaryPayment.month`/`payment_date`) even though only `Payroll.month` was actually being exact-matched today — free insurance against the same landmine showing up in a future report filter.

---

## Phase 7 — Expenses & Accounts ✅ DONE

- [x] `expense_categories` (9 spec-default categories seeded) + `expenses` (category, amount, date, payee, reference, attachment — image/PDF, 5MB cap) + `created_by`
- [x] Deleting a category with recorded expenses is blocked with a friendly toast (checked proactively — the DB's `restrictOnDelete()` FK is the real enforcement, but a raw constraint exception is not a good user-facing error)
- [x] `financial_transactions` — a **purely manual** bookkeeping ledger (income/expense); nothing in the app ever writes to it automatically
- [x] **Accounts dashboard totals are computed directly from the real tables** (`Application.package_price` where Approved, `SalaryPayment.amount`, `Expense.amount`) — never from a synthesized transaction, proven by a test that asserts zero `FinancialTransaction` rows exist after approving an application, paying an officer, and recording an expense (spec section 23's core financial-integrity rule)
- [x] Accounts dashboard carries an explicit on-page warning that these are system-computed figures, not a real payment gateway balance (spec section 22)
- [x] New permission groups: `Expense Categories`, `Financial Transactions`, `Accounts` (separate from `Expenses` — viewing the accounts summary is a different concern from managing expense records)
- [x] Sidebar Accounts section fully activated (Accounts Dashboard, Payroll, Salary Payments, Expenses, Expense Categories, Financial Transactions)
- [x] Seeded 7 demo expenses across categories, ready for the accounts totals to be non-trivial
- [x] 8 new tests (category deletion guard, expense creation, the filtered-total-not-just-current-page correctness check, manual transaction creation, and the financial-integrity proof) — 90 total passing
- [x] Full Playwright pass: accounts dashboard, expense category/expense/transaction CRUD — zero console/HTTP errors; verified the reported totals against raw SQL by hand (৳349,000 salary total = exact sum of the 8 individual payments) rather than trusting the UI at face value

**Design note:** the dashboard's demo-data Net Balance is a large negative number (a full 8-officer payroll against only 5 seeded ৳1,000 applications) — that's an honest reflection of the seeded data's scale, not a bug; verified by hand-summing the underlying rows before accepting the on-screen total.

---

## Phase 8 — Reports, Exports, Activity Logs, Settings ✅ DONE

- [x] Customer / Referral / Package / Salary / Expense / Financial reports in one tabbed component (search, filter, date range, pagination) sharing a single `baseQuery()` between the on-screen table and the export so they can never drift apart
- [x] CSV export respecting active filters, streamed directly from the Livewire action via `response()->streamDownload()` (no Excel/PDF library — the app is CDN-only with no Vite/npm build step, and a spreadsheet-openable CSV meets the spec's "export filtered data" intent without pulling in a PDF/XLSX dependency)
- [x] Financial report tab reuses the Accounts Dashboard's financial-integrity logic (computed from real tables, never a synthesized transaction) but made date-range filterable; export button is hidden on this tab since it's 4 summary stats, not rows
- [x] `activity_logs` (user, action, module, model_type/model_id, description, old_values/new_values as JSON, IP, user agent) + filterable admin viewer with a before/after detail modal
- [x] `ActivityLogger::log()` called explicitly from business-logic call sites (`ChangeApplicationStatus`, `ChangePayrollStatus`, `SalaryProfileForm`, `UserForm`) rather than a generic model-event hook, so the log reads as a narrative ("System Administrator approved Application #...") instead of raw field diffs
- [x] New `Activity Logs` permission group (`view`)
- [x] Dynamic `settings` (company info, social links, currency, timezone) driving both public site and admin — shipped in Phase 2
- [x] Notification dropdown replaced with a real `NotificationBell` Livewire component (unread badge capped at "9+", mark-one-read, mark-all-read) + a dedicated full-page paginated Notifications screen; Super Admins are now also notified on application approval (previously only the customer and the assigned officer)
- [x] Sidebar Reports / Notifications / Activity Logs links activated (no longer "Soon")
- [x] 9 new tests (activity log written on approval, permission gates on both new index pages, CSV export streams the correct filtered rows and header, notification bell unread count and mark-all-read, notifications page mark-as-read) — 99 total passing
- [x] Full Playwright pass: all 6 report tabs, CSV export downloaded and its content verified, activity log list + filter + detail modal (verified against a real approval-generated entry, not just the empty state), notification bell empty state and real-notification state, dedicated notifications page — zero console/HTTP errors

**Design note:** the spec lists "Excel/PDF export" for reports; this phase ships CSV instead. Given the project's hard no-Vite/CDN-only constraint, a CSV needs no client-side library at all and opens natively in Excel/Sheets, whereas a real XLSX or PDF export would need either a PHP library (fine) plus non-trivial formatting code, or a JS library the CDN constraint makes awkward to source reliably. Flagging this as a deliberate scope call rather than a silent gap — worth confirming with the user if true XLSX/PDF is a hard requirement before final delivery.

---

## Phase 9 — Hardening & Polish ✅ DONE

- [x] **Security pass:**
  - File upload validation audited across every `WithFileUploads` field (profile photos, package images, expense attachments) — all already use Laravel's real-content `image`/`mimes:` rules (not extension sniffing) plus a size cap; no gaps found.
  - **Rate limiting audit found a real gap and fixed it:** `LoginForm` already had proper `RateLimiter` throttling, but the two other public, unauthenticated write endpoints — `RegisterForm::register()` and `ContactForm::send()` — had none, leaving them open to scripted spam/mass-account-creation. Added the same IP-keyed `RateLimiter` pattern to both (contact: 3 per 10 minutes, registration: 5 per 10 minutes), proven by 2 new tests that submit up to the limit and assert the next attempt is blocked.
  - XSS/CSRF reviewed: the only raw `{!! !!}` output is `Page.content` on the public About/Terms/Privacy pages — content only ever settable by Admin/Super Admin via `pages.edit`, so this is an intentional trusted-editor CMS pattern, not an injectable surface. CSRF is handled by Livewire automatically plus Laravel's default `VerifyCsrfToken` middleware; no raw forms bypass it.
- [x] **Performance pass:** audited every admin list component's `render()`/query-building method against what its Blade view actually touches. All already eager-load correctly (`Applications`, `Officers`, `SalaryProfiles`, `Payroll`, `SalaryPayments`, `FinancialTransactions`, `Roles`, `Users`, all six `Reports` tabs). Added one defensive `->with('category')` to the `Expenses` list's shared `filteredQuery()` helper so it stays eager-loaded even if reused elsewhere later (the actual `render()` already eager-loaded it, so this wasn't a live bug — verified with a regression test that fails if the eager load is ever removed from either place). Indexes reviewed: every filterable `status` column and the `activity_logs`/`financial_transactions` polymorphic pairs already carry an explicit index from their migrations.
- [x] Full test coverage reviewed against spec section 59's list (registration, applications, authorization, payroll, expenses, referrals) — all covered, plus Phase 8 and the two new security tests; **102 tests passing**.
- [x] **Final responsive/UX polish — a real mobile bug found and fixed:** the admin layout's sidebar-toggle script unconditionally added `sidebar-collapsed` on every page load under 992px width — but on mobile that class means *show* the off-canvas sidebar (the opposite of what it means on desktop, where the same class means *hide* it). The result: the sidebar opened on top of the page on every mobile page load, covering the toggle button itself so it couldn't even be tapped closed again. Fixed by removing the erroneous auto-add (the CSS's own mobile default already hides it correctly with no JS needed) and adding a tap-to-close backdrop overlay so an opened sidebar can be dismissed by tapping anywhere outside it, not just by navigating away. Verified via Playwright at a 375px viewport: sidebar now starts closed, the toggle opens it, and the new backdrop closes it — and confirmed the desktop toggle behavior (which relies on the same class/CSS) is unaffected.
- [x] **A second real mobile bug found and fixed:** the public homepage's referral-network section used a `row g-5` (3rem gutter) directly inside a `.container` (1.5rem padding) — since Bootstrap's gutter and container-padding variables aren't linked, the wider gutter's negative margin exceeded the container's padding and pushed the row past the viewport edge, adding a horizontal scrollbar on every mobile page load. Changed to `g-4` (1.5rem, matching the container's own gutter) to remove the excess without changing the visual spacing meaningfully. Verified with an automated overflow check (`scrollWidth` vs `clientWidth`) across the public site and every admin page tested at 375px — all clean now.

---

## Post-launch QA pass — full demo-data walkthrough + Admin dashboard rebuild

- [x] **Full demo-data QA**: drove the app end-to-end as every seeded role (Super Admin, a demo Admin, a Marketing Officer, a Customer, and an anonymous visitor) against real seeded data — 39 automated checks across every admin page, both officer views, the customer dashboard, and all 7 public pages, plus the full mobile-viewport pack — all passing with zero unexpected console/HTTP errors (the two expected 403s from officer/customer correctly blocked out of `/admin/*` are proof the boundary works, not failures).
- [x] **Admin dashboard rebuilt with real metrics** — it had been left as Phase 1's placeholder (Users/Roles/Permissions counts + a static "Foundation module... will populate as each module is built" banner) even after all 9 phases shipped. Replaced with real business metrics computed the same financial-integrity-safe way as the Accounts dashboard (Active Customers, Pending Applications, Active Officers, Total Revenue, Total Users, Applications This Month, Total Expense, Net Balance), plus an "Applications Needing Review" list and a "Recent Activity" feed (last 6 `ActivityLog` entries) so the home screen is actually actionable. Locked in with a new test asserting the dashboard reflects real seeded/created data and no longer shows the stale banner.
- [x] **A third real mobile bug found and fixed while reviewing the new dashboard**: the top stat-row's icon+value flex layout (inherited from the old Users/Roles/Permissions cards, which only ever held short integers) overlapped its icon on top of the currency text once a long value like "৳5,000.00" was put in it, on narrow screens. Fixed by giving currency-value cards the plain stacked layout (icon-free, like the existing Total Expense/Net Balance cards) and added a mobile-only `.stat-value` font-size reduction so long currency figures wrap cleanly instead of visually crowding a half-width mobile card. Re-verified with the full mobile pack — no overflow, no overlap.
- [x] **103 tests passing** after this pass (102 from Phase 9 + 1 new dashboard test).

---

## Last two sidebar "Soon" items shipped: Customers + Referral Management

- [x] **Admin → Customers**: a full list view of every `Customer` (search by name/mobile/email/NID, filter by status, referral officer + applications-count columns) with a detail modal showing full profile fields (parents' names, NID, profession, DOB, gender, address), the customer's application history, and a manual Active/Inactive/Suspended status control gated behind a new `customers.edit` check — most customers reach Active automatically via application approval, so this is the admin override for the rest (suspending membership, reactivating one). Every status change is written to the activity log.
- [x] **Admin → Referral Management**: a dedicated, deliberately **read-only** browsing/audit view of every `Referral` (search by officer/customer/referral code, filter by officer) — kept read-only on purpose, since the referral relationship is documented as permanent (Phase 5's design note): reassigning who *currently handles* an application is a separate concept already living on the Applications screen.
- [x] New `Referrals` permission group (`view`); `Customers` group already existed and was already granted to Admin.
- [x] **A real, sitewide bug found while wiring these up and fixed everywhere**: every admin/officer/customer page's browser tab always read "Dashboard — Monthly Bazar", and the navbar's page-heading next to the hamburger button never appeared on *any* page, no matter which Livewire `#[Title(...)]` attribute the component declared. Root cause: all three layouts (`layouts/admin`, `layouts/officer`, `layouts/customer`) and the admin navbar partial read a `$pageTitle` variable that nothing has ever set — Livewire's `#[Title]` attribute actually injects a `$title` variable into the layout, a different name. Fixed by renaming the layouts'/navbar's variable reference from `$pageTitle` to `$title`; spot-checked across 11 admin/officer pages post-fix and every one now shows its real title in both the tab and the navbar heading.
- [x] 7 new tests (permission gates on both screens, search/filter, detail-modal content, manual status change + its activity log entry, permission-gated status change is silently blocked not just visually hidden) — **110 tests passing**.
- [x] Full demo-data Playwright pass on both new screens plus a titles spot-check across admin and officer layouts — zero console/HTTP errors, sidebar no longer shows "Soon" anywhere.

---

## Production bug: reseeding failed after a soft-deleted Officer/Customer

- [x] **A real production failure, reported and fixed**: `php artisan db:seed` on a live/staging database threw `UniqueConstraintViolationException` ("Duplicate entry '8' for key `officers_user_id_unique`") inside `OfficerSeeder`. Root cause: `Officer` and `Customer` are both soft-delete models, but their seeders called `updateOrCreate(['user_id' => ...], ...)` without `withTrashed()` — Eloquent's default lookup excludes trashed rows, so once a demo officer/customer had ever been soft-deleted (which is exactly what the admin Officers/Customers "delete" action does — soft-delete only, by design), reseeding couldn't find the existing row and tried a fresh `INSERT` that collided with the trashed row's unique `user_id` index.
- [x] Fixed both seeders with `Model::withTrashed()->updateOrCreate([...], [..., 'deleted_at' => null])`, which finds the trashed row and restores it as part of the same update instead of blindly inserting.
- [x] **Reproduced the exact reported error** locally against real MySQL (soft-deleted `user_id=8` / `EMP-0004`, the same row from the error message) before and after the fix — confirmed it throws pre-fix and cleanly restores the row post-fix, with no duplicate and no leftover trashed copy.
- [x] 2 new regression tests (`DemoSeederTest`) that soft-delete an Officer/Customer and reseed, verified to actually fail without the fix (reproduced the same `UniqueConstraintViolationException` class by temporarily reverting) before confirming they pass with it — **112 tests passing**.

---

## `wire:navigate` added to every internal link, app-wide

- [x] Added the `wire:navigate` attribute to every internal `<a href="{{ route(...) }}">` across the app — public site nav/footer/CTAs, the guest/auth layout's home link, the admin sidebar (dynamic loop) and navbar (profile link, dashboard's "View all"/stat-card links), the officer navbar and dashboard buttons, and the customer navbar and dashboard's "Apply Now" buttons — so navigation is a soft, SPA-style transition instead of a full page reload everywhere in the app.
- [x] Deliberately left untouched: `target="_blank"` external links (social icons, WhatsApp), `#` dropdown-toggle placeholders, and the Logout `<form>` (a POST, not a link — `wire:navigate` only applies to `<a>` GET navigation) — none of those are candidates for soft navigation.
- [x] Verified with real Playwright *clicks* (not `page.goto`, which would bypass the click interception `wire:navigate` relies on), using a `window` marker planted before each click to detect whether the JS context survived (a real reload destroys it; Livewire's swap doesn't) — 20/20 checks: public nav, the public→guest cross-layout jump into `/login`, the admin sidebar and navbar, the officer navbar, and the customer navbar all confirmed as genuine soft navigations landing on the correct URL.
- [x] Specifically checked the one place this could have quietly broken something: the mobile sidebar's open/close state is a runtime-added CSS class (`body.sidebar-collapsed`), not part of the static Blade markup. Confirmed tapping a sidebar link while open correctly auto-closes the sidebar after the soft nav (the incoming page's fresh HTML never had the class to begin with), and confirmed the toggle button and its backdrop both still work via click on a page that was itself reached entirely through a prior soft navigation — proving the layout's inline `<script>` listeners survive Livewire's swap.
- [x] Full regression pass: 112 tests passing, 41/41 role-based smoke checks, 8/8 mobile checks — all still clean after the change.

---

## Username field + login-with-email-or-username

- [x] New nullable, unique `username` column on `users` (migration `2026_08_25_090000_add_username_to_users_table`) — nullable at the DB level like `phone` so no existing row can violate a constraint, but required by every form that creates or edits a user going forward (Register, admin Users, admin Officers, and all three self-service Profile pages). Validated everywhere with `regex:/^[a-zA-Z0-9._-]+$/` (letters, numbers, dot, underscore, dash) rather than Laravel's stock `alpha_dash`, specifically so it accepts the dotted style already used throughout (`karim.hossain`, Faker's own `userName()` output) — `alpha_dash` alone would reject the dot.
- [x] `LoginForm` reworked: the single `login` field accepts either — `filter_var(..., FILTER_VALIDATE_EMAIL)` decides whether to authenticate against `email` or `username`, so `Auth::attempt()` needs no custom guard or provider, just the right column picked per request. Rate-limiting/throttle key updated to key off whichever identifier was actually typed.
- [x] Backfilled every existing account: `AdminUserSeeder` gets `admin`; `DemoUserSeeder` derives one per demo user from their email's local part (`Str::before($email, '@')`) so the 20 seeded accounts already have real, readable usernames; `UserFactory` now generates one too so ad-hoc test users aren't null by default.
- [x] Admin Users list gained a Username column and the search box now matches it too; same for the Officers list (shown as `@username` next to the email) and its search.
- [x] 3 new tests (login with username instead of email, duplicate username rejected at registration, plus the existing email-based login/registration paths re-verified) — **114 tests passing**.
- [x] Full demo-data browser verification: logged in with a username, logged in with an email (both still work), a wrong password against a valid username fails cleanly, the Users list shows and searches real usernames, a brand-new officer created through the admin form could immediately log in with the username just given to them. Caught and fixed one real layout regression from adding the field: the registration page's Password/Confirm Password fields lost their row-mate and ended up half-width and orphaned on their own row — widened both to match.

---

## Production bug: dashboard/reports crashed when a customer's or officer's User was soft-deleted

- [x] **Reported live from production** (`masikbazzar.com`): `GET /admin/dashboard` → 500, `ErrorException: Attempt to read property "name" on null` at `dashboard.blade.php:112`. Root cause: the "Applications Needing Review" panel read `$application->customer->user->name` assuming the chain always resolves — but `User`, `Customer`, and `Package` are all soft-delete models, and the admin Users screen's delete action is soft-delete-only *by design* (spec-established, reversible). A pending application whose customer's account (or whose package) had since been soft-deleted made the relation resolve to `null`, and the unguarded property access crashed the whole page.
- [x] **Audited the entire codebase for the same crash class** (`->customer->user->`, `->officer->name`, `->package->name`, `->customer->status` and siblings) rather than patching only the reported line — found and fixed **18 more unguarded occurrences** across Applications (admin + officer), Referrals (admin + officer), Customers, Officers, Payroll, Salary Payments, Salary Profiles, and the Reports module's on-screen tables *and* its CSV export (which had the identical risk server-side, not just in Blade). Each fixed with `?->` + a `'Unknown ...'`/`'-'` fallback, matching the null-guard convention already used elsewhere in the app (e.g. `$officer->department?->name ?: '-'`). Confirmed `ExpenseCategory` doesn't need the same treatment — it's FK-restricted from deletion while expenses reference it, so `$expense->category` can never dangle.
- [x] **Reproduced the exact reported error locally** before fixing it (temporarily reverted the dashboard fix, hit the identical `ErrorException` message and line), then confirmed 2 new regression tests (`DashboardTest`) fail without the fix and pass with it — one for a soft-deleted customer's user, one for a soft-deleted package.
- [x] **120 tests passing** (2 new).

---

## Admin can now upload a logo and favicon

- [x] The `logo_path`/`favicon_path` `Setting` keys already existed in `SettingSeeder` (seeded empty) but nothing ever let an admin actually set them, and no layout rendered them — every page hardcoded a Bootstrap `bi-shop` icon and the literal text "Monthly Bazar", and no `<link rel="icon">` existed anywhere, so the browser tab always showed a generic default favicon.
- [x] Added upload/remove to the Settings page as two small standalone forms (separate from the main settings form, since they validate and submit independently): Logo (`image`, max 1MB) and Favicon (`mimes:ico,png,svg,jpg,jpeg`, max 512KB — deliberately not the generic `image` rule, which rejects `.ico`). `Setting::logoUrl()`/`faviconUrl()` helpers added to the model so every layout can ask a single place for the current branding.
- [x] Wired the real logo (falling back to the icon when unset) and a `<link rel="icon">` favicon tag into **every** layout: public site (navbar + footer), admin (sidebar + navbar), officer, customer, and the guest/auth pages — the admin sidebar's hardcoded "Monthly Bazar" text was also swapped for the real configured `company_name` while touching that file, since it was sitting right next to the icon and had the identical staleness problem.
- [x] 5 new tests (upload + remove for both logo and favicon, non-image upload rejected, `settings.edit` permission required to write branding even for a user who can view Settings) — **120 tests passing** in total across the app.
- [x] Full browser verification with real uploaded files: empty state before upload, success toast and preview after, the logo appears in the admin sidebar/public navbar/officer navbar/customer navbar, the favicon `<link>` appears in every layout's `<head>`, removal cleanly falls back to the default icon everywhere. Confirmed (not a bug) that branding changes apply on the *next* navigation rather than instantly on the settings page itself — the sidebar/`<head>` are part of the surrounding Blade layout, rendered once per full page load, outside Livewire's own re-rendered boundary; this is identical to how every other setting (e.g. company name) already behaves, not a new inconsistency.

---

## Admin panel design pass: icon-only sidebar collapse, styled scrollbars, rich text editor

- [x] **Sidebar toggle now collapses to an icon-only rail on desktop instead of hiding entirely.** Previously the one `sidebar-collapsed` class meant different things at different breakpoints (fully hide on mobile, fully hide on desktop too) — rather than overload that further, added a second, independent class (`sidebar-mini`) purely for the desktop icon-rail mode, so mobile's off-canvas behavior (fixed a few turns ago) and desktop's new mini mode never fight over what the same class name means. Labels/section headings hide, icons stay centered with a native `title` tooltip, the sidebar width and content margin animate together (`260px` ↔ `76px`).
- [x] The mini/full choice **persists across navigation** via `localStorage`, re-applied by the layout's inline script on every load (including `wire:navigate` soft transitions, which re-run it — verified this session). Deliberately **not** restored on a mobile-sized viewport even if it was last set on a wider screen, since mobile has its own separate, always-starts-closed mechanism; verified by seeding a stale desktop preference and loading on a 375px viewport.
- [x] **Custom scrollbar styling**: a branded green thumb on a light track for the whole admin panel (`scrollbar-color` for Firefox, `::-webkit-scrollbar*` for Chromium/Safari/Edge), plus a subtler translucent-white variant scoped to the sidebar's own nav scroll region so it reads correctly against the dark green background.
- [x] **Rich text editor on Page content** (About/Terms/Privacy), replacing the raw HTML `<textarea>`. First pass was a hand-rolled Quill + Alpine.js integration; swapped to the user-requested [`dasundev/livewire-quill-text-editor`](https://github.com/dasundev/livewire-quill-text-editor) package instead (`composer require`, no npm/Vite involved — it still loads Quill itself from a CDN, matching the project's build-step constraint). Usage is a single nested component: `<livewire:quill-text-editor wire:model.live="content" theme="snow" />`, keyed to the page being edited (`:key="'page-content-'.$editingId"`) so reopening the modal for a different page never shows stale content from the previous edit.
- [x] Full Playwright pass: sidebar starts full width, shrinks to the icon rail on toggle (labels hidden, icons visible), the choice survives a real navigation, expands back on toggle again; a stale desktop preference is correctly ignored on mobile; the packaged Quill editor renders pre-filled with the existing content inside the page-edit modal, typing and saving persists to the database (verified by reopening the same page), the new content appears on the live public page immediately, and editing a *different* page afterward shows that page's own content rather than leaking the previous edit. 120 tests passing (no PHP-level behavior changed, so no new automated tests — this phase is pure front-end/Blade plus one new Composer dependency).

---

## Admin panel: human-readable, real-time validation errors on every form

- [x] **Human-readable field labels app-wide.** Laravel 12 doesn't ship `lang/en/validation.php` by default; published it (`php artisan lang:publish`) and populated the `'attributes'` array with ~90 field-key → label mappings covering every admin form (`employee_id` → "employee ID", `nid_number` → "NID number", `father_name` → "father's name", `google_map_embed` → "Google Map embed URL", `rejectionReason` → "rejection reason", `generateMonth` → "month", etc.), so messages read "The employee ID field is required" instead of Laravel's default underscore-to-space "the employee id field is required".
- [x] **Errors now appear on blur and clear automatically once fixed**, without a full form resubmit. Built one shared trait, `App\Livewire\Concerns\ValidatesOnUpdate` (`app/Livewire/Concerns/ValidatesOnUpdate.php`), added to all 17 admin Livewire components (Users, Officers, Roles, Permissions, Departments, Designations, Packages, ExpenseCategories, Expenses, FinancialTransactions, SalaryPayments, SalaryProfiles, Profile, Settings, Pages, Applications, Payroll). Its generic `updated($property)` hook calls `$this->validateOnly($property)` — confirmed via Livewire's own source (`HandlesValidation.php`) that this correctly delegates to a nested `Form` object's own `rules()` when the property is a dotted `form.*` path, so one trait works for both Form-object-based components and inline-`rules()`-based ones with no per-field boilerplate. Paired with converting every genuinely-user-typed field's `wire:model` to `wire:model.blur` (~90 fields across 17 Blade views) so validation runs on blur rather than on every keystroke.
- [x] **File upload inputs deliberately left on plain `wire:model`** (Officers' `profile_picture`, Packages' `image`, Expenses' `attachment`, Settings' `logo`/`favicon`) — file selection is a discrete one-time action, not a type-and-fix flow, and Livewire's upload synth already fires immediately regardless of modifier.
- [x] Centralized `Profile`, `Settings`, `Pages`, `Applications`, and `Payroll` components' previously-inline `$this->validate([...])` arrays into proper `rules()` methods so the shared trait (and `validateOnly()`) has something to target.
- [x] **Found and fixed a real, pre-existing production bug while browser-testing file uploads weren't broken by the above:** `Officers\Index`, `Packages\Index`, and `Expenses\Index` were all missing the `WithFileUploads` trait on the *top-level* Livewire component — Livewire requires it there even when the actual file property lives on a nested `Form` object that already has it (confirmed via `vendor/livewire/livewire/src/Features/SupportFileUploads/SupportFileUploads.php`, which checks `method_exists($component, '_startUpload')` against the top-level component). Every upload attempt on Officer profile pictures, Package images, or Expense attachments was throwing `MissingFileUploadsTraitException` (a 500) — this predates this session entirely (`git log --all -p` shows the trait was never present). A follow-up audit found a 4th case, `App\Livewire\Public\Register` (the public registration form's profile-photo upload), with the same bug. All 4 fixed by adding `use Livewire\WithFileUploads;` to the parent component.
- [x] **121 tests passing** (1 new: a Users regression test proving a field error appears on an invalid value and clears once corrected, `UsersCrudTest::test_a_field_error_appears_and_then_clears_as_the_field_is_corrected`).
- [x] Full browser verification across Users, Officers, Expenses (numeric field), and Roles (checkbox-based form): errors appear on blur with the human-readable label, clear on the next blur once fixed, no console errors. Separately re-verified all 4 previously-broken file uploads (Officer photo, Package image, Expense attachment, public registration photo) now succeed end-to-end (success toast, no server error, no console error).

---

## GitHub issue #1 triage: auto-generated Package codes, manual referral code on registration, two more soft-delete crashes, required-field markers

- [x] **Triaged the full issue thread** (`gh api repos/mr-sabya/montly-bazar/issues/1` + its 10 comments, several with screenshots pulled via an authenticated redirect through `github.com/user-attachments/...` since the repo requires auth even for image assets). Several items were already fixed earlier this same session (ref-link registration crash, Personal Information trimmed to Profile Photo, Employee ID auto-generate, rich text editor for Pages) — this entry covers what was still open.
- [x] **Package `code` field now auto-generates** (`PKG-0001`, `PKG-0002`, ...) the same way Employee ID and Referral Code already do — `Package::generateCode()` added, `withTrashed()`-aware so a soft-deleted package's code is never reissued. Pre-filled but still editable when opening "Add Package", with the same "Auto-generated — you can change it if needed" hint used for Employee ID.
- [x] **Investigated "package added but doesn't show on the home page"** — not a bug. `home.blade.php` deliberately shows only one featured package (`$packages->first()`) in the pricing hero; the full list of active packages already renders correctly on the dedicated `/packages` page (confirmed by reading `packages.blade.php`'s `@foreach`). No code change made.
- [x] **Registration can now be attributed to an officer without a `/r/{code}` link.** Previously the *only* way to record which officer referred a customer was the special link — a customer registering directly at `/register` had no way to specify one, which is exactly what was reported ("Ref code option nai... eta kar ref a jabe bujho kivabe?"). Added an optional Referral Code field to `RegisterForm` (`nullable|exists:users,referral_code`). When arrived via a valid `/r/{code}` link the field is pre-filled and shown read-only (can't be accidentally cleared, losing the officer's credit); otherwise it's a normal editable optional field. `RegisterForm::register()` prefers the session-locked code over the typed one when both are somehow present.
- [x] **Fixed two more crashes in the same "soft-deleted User" class as the earlier dashboard fix**, found directly from the issue's screenshots:
  - `ChangeApplicationStatus.php`: approving/rejecting an application whose customer's User account had been soft-deleted threw `Call to a member function notify() on null` (a live 500 on production, screenshotted mid-crash) — `$application->customer->user->notify(...)` needed **two** null-safe operators (customer and user can each independently be missing), plus the neighboring `$application->customer->update(...)` had the same gap.
  - `User::generateReferralCode()` didn't use `withTrashed()`, so a soft-deleted officer's `REF-0001` was invisible to the "next number" query — a second officer creation would recompute `REF-0001` again and crash with `SQLSTATE[23000]... Duplicate entry 'REF-0001'` (also screenshotted from production, "Officer add problem"). Fixed the same way `Officer::generateEmployeeId()` was fixed earlier this session.
  - **Found one more while writing the regression test for the fix above**: the Officers list's new "Referral Code" column read `$officer->user->referral_code` with no null-guard at all (unlike the neighboring name/email cells on the same row, which already used `?->`) — one officer with a soft-deleted user would 500 the *entire* Officers page. Fixed with `?->`.
- [x] **Required-field asterisks added across all 17 admin forms** (plus public Registration) — `<span class="text-danger">*</span>` next to the label of every field that's actually required per that field's own validation rule, derived directly from each `rules()`/`#[Validate]` definition rather than guessed. Officer/User `password` shows the asterisk only when creating (`@unless ($editingId)`), since it's optional on edit. Login/Reset/Update-Password forms deliberately left alone (every field on those is self-evidently required).
- [x] **Confirmed already resolved, no action needed**: "Username option" — username field and login-by-username-or-email have existed since an earlier phase; About/Privacy/Terms already use the packaged rich text editor; Personal Information section already trimmed to Profile Photo with a live preview.
- [x] **6 new regression tests, 127 tests passing total**: profile-photo-only registration, manually-typed referral code (valid + invalid), soft-deleted-customer application approval, soft-deleted-officer referral code collision, Employee ID auto-fill (from the earlier entry).
- [x] Browser-verified: registration referral field shows editable+optional with no link, and pre-filled+readonly with a valid `/r/{code}` link; Package modal pre-fills `PKG-0001`; Officers list renders cleanly.

---

## Admin panel: QA Checklist page — a screenshot record of every module's List/Add/Edit/View screens

- [x] Ran a full Playwright sweep across all 23 admin sidebar modules, opening each module's List view and (where they exist) its Add, Edit, or View modal, capturing a full-page screenshot of each — 49 screenshots total, saved to `public/images/qa-checklist/` as plain static assets (no build step needed, matches the CDN-only/no-Vite constraint).
- [x] Built a new admin page, **QA Checklist** (`/admin/qa-checklist`, `App\Livewire\Admin\QaChecklist\Index`), added to the sidebar under System. It's a static documentation view — a jump-link nav to each module, then one card per module showing its captured screenshots (labeled List/Add/Edit/View), each screenshot clickable to open full-size in a new tab. The module list itself is a plain hand-curated PHP array in the component (this is documentation, not live data, so no database table for it).
- [x] Added a new `qa-checklist.view` permission (`PermissionSeeder`'s catalogue, `Str::slug('QA Checklist')` → `qa-checklist`), gated with `Gate::authorize()` in `mount()` like every other admin module — Super Admin gets it automatically via the existing `Gate::before` bypass; any other role needs it explicitly granted via Roles & Permissions, same as everywhere else.
- [x] 2 new tests: the permission gate (`assertForbidden` for a role with no permissions), and — more importantly — a test that parses the rendered page for every `qa-checklist/*.png` reference and asserts the file actually exists on disk, so a typo'd filename or a screenshot that never got captured shows up as a failing test instead of a silent broken `<img>` in production.
- [x] 129 tests passing (2 new). Browser-verified: all 49 images load with zero broken `<img>` tags, all 23 jump links and module cards render, sidebar entry navigates correctly, no console errors.
- [x] **This is a point-in-time snapshot**, not something that stays in sync automatically — if the UI changes later (new fields, restyled forms), the screenshots need to be recaptured manually. Worth knowing if this ever seems "out of date" — that's expected, not a bug.

---

## Referral codes: removed the "REF-" prefix everywhere, per client request

- [x] `User::generateReferralCode()` now generates plain zero-padded numbers (`0001`, `0002`, ...) instead of `REF-0001` — one-line format change, kept the `str_replace('REF-', '', $code)` in the max-computation so it still parses any legacy prefixed code correctly (a no-op on codes that never had the prefix).
- [x] **Migrated existing data**, not just future codes — a new migration (`strip_ref_prefix_from_referral_codes`) strips `REF-` from every already-issued `users.referral_code` and `referrals.referral_code` row on deploy, so an officer created before this change doesn't stay stuck with an old-format code while everyone after them gets a clean one. Verified both directions (`up`/`down`) against a manually-inserted `REF-9001` row.
- [x] Updated the registration form's placeholder text (`REF-0001` → `0001`) and every hardcoded `REF-####` literal across ~13 test files (mostly arbitrary test fixture values, but two — `OfficersCrudTest`'s format assertions — actually asserted the generator's real output shape and needed real fixes, not just cosmetic renames).
- [x] 129 tests passing (no new tests needed — this is a pure format change with existing coverage). Browser- and DB-verified: Officers list and the seeded `users` table both show clean `0001`–`0008` codes with no `REF-` anywhere.
- [x] **Left alone on purpose**: the 49 QA Checklist screenshots (previous entry) still show old `REF-000X` codes in a couple of frames — those are a point-in-time snapshot, not live data, and already documented as needing manual recapture if the UI changes.

---

## Home page now shows every active package, not just one

- [x] The home page used to feature only `$packages->first()` in a single centered card — a report that "new packages aren't showing on the frontend, only the default 1000 taka package" turned out to be exactly this by-design limitation (confirmed by actually adding a package through the admin UI end-to-end and checking both `/` and `/packages` — the full list was always correct on `/packages`, only the home page was capped at one).
- [x] Replaced the single-card section with a grid of every active package (`resources/views/public/home.blade.php`), reusing the same card layout as `/packages` for visual consistency. The lowest-sort-order package keeps the "Featured Package" badge and highlighted border it always had; the rest render as plain cards alongside it.
- [x] 1 new test (`test_home_page_shows_every_active_package_not_just_the_first`) plus the existing single-package and inactive-hiding tests all still pass unchanged — the underlying query (`Package::active()->ordered()->get()`) didn't change, only how many of its results the view renders. 130 tests passing.
- [x] Browser-verified with 3 active packages: all three render in a 3-column grid, first one still visually featured, no console errors.

---

## Frontend theme customization — colors, hero, footer, heading/paragraph sizes

- [x] Added a **Theme & Appearance** section to the admin Settings page: 5 colors (Primary, Primary Dark, Primary Light, Footer Background, Footer Text) with paired native color pickers + hex text inputs, plus 7 text sizes (H1–H6, Paragraph) as dropdowns of preset rem values. A "Reset to Defaults" button restores the exact original hardcoded values.
- [x] **Scoped to the public-facing frontend only** (`layouts/public.blade.php` — home/about/packages/contact/etc. — and `layouts/guest.blade.php` — login/register): the admin/customer/officer panel layouts deliberately keep their own fixed colors, so the internal tool stays visually stable no matter what a client picks for their public site. Said so explicitly in the Settings UI copy so it's not a surprise later.
- [x] `Setting::THEME_DEFAULTS` (a new const on the `Setting` model) + `Setting::theme($key)` helper centralize the 12 default values in one place — the admin form, the public layout, and the guest layout all read from the same source, so "not customized yet" always means "looks exactly like it did before this feature shipped."
- [x] **Hero banner isn't a separate color setting** — it's a gradient built from the three Primary colors, same as before, so customizing "Primary" automatically re-colors the hero (and every button, link, and accent) coherently in one place rather than needing a redundant fourth color that could drift out of sync.
- [x] Heading sizes are applied via `h1`–`h6 { font-size: ...!important; }` — the `!important` is required specifically to win against Bootstrap's `.display-1`–`.display-6` utility classes, which every page's main `<h1>` title actually uses (`display-5`/`display-6`, not a bare tag) and which would otherwise silently ignore the H1 setting.
- [x] 4 new tests: saving custom colors/sizes persists correctly, an invalid hex/size format is rejected (regex-validated), Reset to Defaults restores `Setting::THEME_DEFAULTS` exactly, and — the one that actually proves the feature works, not just that data saves — a customized primary color shows up in the home page's rendered `<style>` block. 134 tests passing.
- [x] Browser-verified end-to-end: set a bold red-orange theme + larger heading/body sizes + a dark navy footer through the admin form, confirmed via computed styles that the hero gradient, H1 size (56px), body size (18px), primary buttons, and footer background all changed correctly on the live public home page and the login page; Reset to Defaults confirmed to restore the original green gradient exactly.

---

## Production bug: package images uploaded in admin never appeared on the public site

- [x] Confirmed the package's `image` field was correctly uploaded, stored, and shown as a thumbnail in the **admin** Packages list — but neither public template (`home.blade.php`'s package grid, `packages.blade.php`'s full list) ever referenced `Package::imageUrl()` at all. The photo silently never rendered anywhere a visitor could see it.
- [x] Added the image to both public package card layouts, matching the admin list's fallback-free pattern (no placeholder when unset — it's optional, so cards without one just show the text content, unchanged from before). Restructured each card from `p-4` directly on `.card-feature` to a full-bleed image + an inner `.p-4` content wrapper (standard Bootstrap card pattern), and added `overflow: hidden` to `.card-feature` so the image respects the card's rounded corners.
- [x] 1 new test asserting the uploaded image path renders on **both** `/` and `/packages`. 135 tests passing.
- [x] Browser-verified with a real (visibly colored) uploaded image end-to-end: correct image, correct rounded top corners, correct 180px crop (`object-fit: cover`), on both pages.

---

## Hero slider and testimonials slider on the home page

- [x] Two new admin-managed content modules, `hero_slides` and `testimonials` (new tables, models, enums, `HeroSlideForm`/`TestimonialForm`, and full CRUD Livewire components under Admin → Website), following the exact same pattern as Packages (soft-deleted, `active()`/`ordered()` scopes, `created_by`/`updated_by`, optional image upload). New `hero-slides.*` and `testimonials.*` permissions.
- [x] **Hero section** on the home page becomes a Bootstrap carousel (`carousel-fade`, auto-rotating every 6s) when at least one active hero slide exists — each slide has its own title/subtitle/button text+link, with an optional background image (dark gradient overlay for text contrast) that falls back to the existing theme-color gradient when unset. **When zero slides exist, the home page renders the exact original static hero, byte-for-byte unchanged** — this is opt-in, not a forced redesign.
- [x] **Testimonials carousel** added right after the packages section (auto-rotating every 7s) — customer photo (optional, falls back to a person icon), star rating (optional), quote, name, role/company. Section doesn't render at all when there are no active testimonials.
- [x] **Found and fixed a real contrast bug while browser-verifying**: the testimonial carousel's indicator dots were invisible (white-on-white) because Bootstrap's own `.carousel-indicators [data-bs-target]` selector is more specific than the page's `.bg-brand` utility class, so the utility class silently lost that specificity fight. Fixed with an inline `style="background-color: var(--brand)"` on the indicator buttons themselves, which always wins. (The hero carousel's indicators were unaffected — white is correct there, against the dark hero background.)
- [x] 11 new tests: full CRUD + validation for both new modules, permission gates, and — the ones that actually catch a broken integration, not just data-layer correctness — that active slides/testimonials render on the live home page, inactive ones are hidden, and the page falls back to the default hero when no slides exist. 146 tests passing.
- [x] Browser-verified end-to-end: created 2 hero slides and 2 testimonials through the admin UI, confirmed the hero carousel's next/prev arrows and indicator dots correctly cycle between slide titles, and the testimonial carousel correctly shows quote/rating/name with visible (post-fix) indicator dots.

---

## Demo data for hero slides and testimonials

- [x] `HeroSlideSeeder` (3 slides) and `TestimonialSeeder` (4 testimonials, 4–5 star ratings), both `firstOrCreate`-idempotent like `PackageSeeder` — safe to re-run without duplicating. Wired into `DatabaseSeeder` right after `PackageSeeder`.
- [x] 146 tests still passing (no new tests needed — this is demo content, already covered by the rendering tests from the previous entry). Browser-verified: all 3 hero slides and all 4 testimonials render and cycle correctly on the live home page.

---

## Slider polish: hero transition, testimonial controls

- [x] **Hero carousel**: dropped `carousel-fade`, so it uses Bootstrap's default sliding transition (slides in from the side) instead of a cross-fade.
- [x] **Testimonial carousel controls redesigned.** The old prev/next arrows used Bootstrap's default `.carousel-control-prev/-next`, absolute-positioned at 5% from the edges of the full-width section — visually disconnected from the centered, narrower testimonial card sitting in the middle. Replaced with small outlined circular chevron buttons + brand-colored dots, all grouped in one row directly below the card. Kept the `.carousel-indicators` class on the dots wrapper (only overriding size/color via new `.testimonial-indicators` CSS, not replacing the element) specifically so Bootstrap's own JS still auto-tracks which dot is active as slides change — a plain custom `<div>` there would have silently broken that tracking.
- [x] 146 tests still passing (pure CSS/markup change, no PHP touched). Browser-verified: hero next/prev now visibly slides rather than fades; testimonial prev/next buttons sit centered below the card; clicking next correctly advances both the visible testimonial and which dot is marked active.

---

## Home page section backgrounds no longer clash

- [x] Adding the Testimonials section broke the home page's established white/light-grey alternating rhythm: Company Intro (white) → Packages (white) → Testimonials (white) were three white sections in a row with no visual boundary between them, which is what made Packages and Testimonials specifically look like one un-separated block.
- [x] Gave the Packages section `bg-light` (and removed its old `pt-0`, no longer needed now that a background-color change does the visual separation instead of tight spacing). Restores full alternation end to end: white → light → white → light → white → light → white → light → brand CTA — verified programmatically (every consecutive section pair now has a different computed background color) and visually via a full-page screenshot.
- [x] 146 tests still passing (pure CSS class change).

---

## Hero slides: per-slide "Text & Button" vs "Image Only" style

- [x] Added a `display_mode` column to `hero_slides` (new `HeroSlideDisplayMode` enum: `text_and_button` default, `image_only`), settable per slide via a radio choice in the Add/Edit form. Choosing "Image Only" live-collapses the form (Livewire `wire:model.live` on the radio) to hide Subtitle/Button Text/Button Link entirely, and makes the image field required — a slide with neither text nor an image would just be a blank rectangle. Editing an existing image-only slide without re-uploading doesn't re-demand one; the image already on record satisfies it.
- [x] Public hero carousel now branches per slide: `text_and_button` renders exactly as before (badge, heading, subtitle, buttons, decorative shop icon); `image_only` renders nothing but the slide's image filling the banner — no badge, no title, no button. Added `.hero .carousel-item { min-height: 460px }` so slides don't jump in height when auto-rotating between a tall text slide and a bare image slide.
- [x] Admin list gained a "Style" column (badge showing which mode each slide uses) and a thumbnail per row, so it's obvious at a glance which slides are text-driven vs. pure images.
- [x] 7 new tests: image-only requires an image on create but not on an edit that keeps the existing one, and — the ones that actually prove the visual behavior, not just data validation — an image-only slide's title/button never leak onto the rendered home page while a text-and-button slide's do. 151 tests passing.
- [x] Browser-verified end-to-end: created one of each mode through the admin UI (confirmed the form correctly collapses/expands and blocks saving an image-only slide with no image), then confirmed on the live home page that the image-only slide shows a clean full-bleed image with zero text overlay while the text-and-button slide shows its full original layout.

---

## Hero slider: fixed a self-inflicted display bug and a real overlap bug, made responsive at every width

- [x] **Found and fixed a real bug introduced by an earlier CSS change in this same session.** The `.hero .carousel-item { display: flex; ... }` rule added to vertically-center slide content didn't exclude non-active slides, which overrides Bootstrap's own `.carousel-item:not(.active) { display: none }` — so **every** hero slide was rendering simultaneously stacked on top of each other instead of only the active one. This silently broke the whole carousel (confirmed by inspecting computed styles directly: the DOM had the right content, `innerText` even read correctly, but the wrong slide painted on screen because an inactive one was stacked on top). Fixed by scoping the flex/centering rule to `.hero .carousel-item.active` only.
- [x] **Found and fixed a real overlap bug that existed since the hero carousel was first built**, confirmed via full-page screenshots at mobile/tablet/desktop: Bootstrap's default `.carousel-control-prev`/`-next` are absolutely positioned, vertically centered over the *entire* slide height — which put the arrow icons directly on top of the subtitle line at every viewport width, not just mobile.
- [x] Moved the prev/next arrows and indicator dots into a `hero-nav-btn`/`hero-indicators` control row that sits in normal document flow *below* the slide content (same pattern already used for the testimonial carousel) — physically impossible to overlap the text now, regardless of subtitle length or viewport width. Small `min-height` reduction on very small screens (`max-width: 575.98px`) so the hero doesn't reserve more vertical space than short slide text actually needs.
- [x] 1 new regression test asserting the new `hero-nav-btn`/`hero-indicators` markup is present and Bootstrap's old overlapping `carousel-control-*-icon` markup is gone — a plain "does the page load" test wouldn't have caught either of the two bugs above, both needed actually rendering and inspecting the page. 152 tests passing.
- [x] Browser-verified at 375px/768px/1440px for both slide styles (text-and-button and image-only): no overlap at any width, controls cleanly grouped below content, carousel correctly shows only one slide at a time again.

---

## Departments: sub-departments (one level of nesting), Officers updated to match

- [x] Added a nullable `parent_id` self-reference to `departments`. **Deliberately one level of nesting only** — a department's parent must itself be top-level (`Rule::exists(...)->whereNull('parent_id')`), enforced with three server-side guards: a sub-department can never be chosen as someone else's parent, a department can't be its own parent, and a department that already has children of its own can't be given a parent (would create a second level). Deleting a parent never cascade-deletes its children — `parent_id` is `nullOnDelete`, so they simply become top-level departments, matching how deleting a department already unassigns it from officers rather than failing.
- [x] Admin Departments page redesigned as a tree: each top-level department followed by its indented sub-departments (with a "↳" marker), plus a one-click **"+ Sub"** button per row that opens Add Department with that row pre-selected as the parent. Switched from a paginated list to a plain `get()` — a company's department list is realistically a handful of entries, and pagination would've split a parent from its own children across pages.
- [x] **Updated Officers to match, as asked**: both the Department filter and the Add/Edit Officer form's Department field now group sub-departments under their parent using native `<optgroup>` (no JS needed). Filtering the Officers list by a *parent* department also now includes officers assigned to its sub-departments — picking "Sales" surfaces officers in "Retail Sales" and "Corporate Sales" too, not just officers assigned to "Sales" directly. Officer list/profile pages showing a department now use a new `Department::fullName()` helper ("Sales — Retail Sales") so a sub-department is never shown out of context.
- [x] Seeded a working example (`Sales` → `Retail Sales`, `Corporate Sales`) so the feature is visible immediately after a fresh seed, not just in an empty state.
- [x] 9 new tests: create/validate a sub-department, all three nesting guards, delete-doesn't-cascade, and the officer-filter-includes-sub-departments behavior. 158 tests passing.
- [x] Browser-verified: the tree view renders correctly, the "+ Sub" shortcut correctly pre-selects the parent, and the Officer form's department `<select>` correctly shows an `<optgroup>` for Sales with both sub-departments nested inside.

---

## Known deferred items (intentional, not bugs)

All 9 phases plus the post-launch QA/Customers/Referral Management pass are complete.

- **Reports export is CSV, not Excel/PDF** (spec section 61 lists "Excel/PDF export"). Given the hard no-Vite/CDN-only constraint, CSV needs no client-side library and opens natively in Excel/Sheets; a true XLSX/PDF export would need extra formatting work for marginal benefit. Worth confirming with the user if a real XLSX/PDF file is a hard requirement — see Phase 8's design note.
