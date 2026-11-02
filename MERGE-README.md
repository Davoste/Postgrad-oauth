## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
php artisan serve
```

## Registration = admission application (ADM-AF/01A)

Registration follows the university's real admissions flow rather than a plain
sign-up. There is **no self-service account creation** — portal accounts are
created automatically when an applicant accepts an offer.

1. **Public landing page** (`/`) — a themed hero, programme catalogue, and a
   step-by-step "how to apply" guide, with **Apply** and **Sign in** actions.
2. **Apply** (`/apply`) — the full **ADM-AF/01A** form: personal details,
   academic background, programme + mode, and two academic referees.
3. **Confirmation + tracking** — the applicant gets a reference number and a
   private tracking link (`/track/{token}`) to follow status without an account.
4. **Review** — HOD recommends → Director, BPS decides → an offer email goes out.
   Reviewers work from the **Admissions** screen in the portal.
5. **Accept & enrol** — the applicant accepts online; a `student` account and an
   admission number are created automatically and emailed to them.


## Roles (10 — union of both apps)

`admin`, `student`, `supervisor`, `hod`, `dean`, `director_bps`, `finance`,
`registrar`, `ethics_reviewer`, `committee`.
(The base's `registry` is folded into `registrar`.) Full permission matrix in
`database/seeders/RbacSeeder.php`.

### Demo accounts (password shown)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@postgrad.ac.ke | admin123 |
| Supervisor | supervisor@postgrad.ac.ke | supervisor123 |
| Student | student@postgrad.ac.ke | student123 |
| Ethics reviewer | ethics@postgrad.ac.ke | ethics123 |
| HOD | hod@postgrad.ac.ke | hod123 |
| Dean | dean@postgrad.ac.ke | dean123 |
| Director (BPS) | director@postgrad.ac.ke | director123 |
| Finance | finance@postgrad.ac.ke | finance123 |
| Registrar | registrar@postgrad.ac.ke | registrar123 |
| Committee | committee@postgrad.ac.ke | committee123 |

---

