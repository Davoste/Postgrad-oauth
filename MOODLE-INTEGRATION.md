# Moodle LMS Integration

The portal and Moodle stay as **separate systems with separate databases**. They're
linked through Moodle's **Web Services API**: when a student accepts an offer and is
enrolled, the portal provisions a matching Moodle account automatically. The join key
between the two systems is the **admission number** (stored as Moodle's `idnumber`).

## How it works

1. Applicant accepts an offer → `ApplicationController::enrollStudent()` creates the
   portal student account.
2. It dispatches a queued, idempotent job — `App\Jobs\ProvisionMoodleUser`.
3. The job calls Moodle:
   - looks the user up by `idnumber` (admission no.), then `email`;
   - if none exists, creates one (`core_user_create_users`);
   - stores the returned Moodle user id on `students.moodle_user_id`;
   - optionally adds them to a default cohort (`core_cohort_add_cohort_members`),
     which can auto-enrol them into any courses linked to that cohort.

Because the job is idempotent and guarded, it's safe to re-run and does nothing if the
integration is switched off or the student is already linked.

## Moodle-side setup (once)

On the Moodle site (as admin):

1. **Enable web services:** *Site administration → Advanced features →* tick
   **Enable web services**.
2. **Enable the REST protocol:** *Server → Web services → Manage protocols →* enable **REST**.
3. **Create a dedicated integration user** (e.g. `portal-integration`) — a normal account
   the portal will act as.
4. **Create an external service** (*Web services → External services → Add*), tick
   *Enabled* and *Authorised users only*, and add these functions:
   - `core_webservice_get_site_info`
   - `core_user_get_users_by_field`
   - `core_user_create_users`
   - `core_cohort_add_cohort_members`
   - `enrol_manual_enrol_users` *(only if you enrol into specific courses)*
5. **Authorise** the integration user on that service and give their role the matching
   capabilities (`moodle/user:create`, `moodle/cohort:assign`, `enrol/manual:enrol`,
   `webservice/rest:use`).
6. **Create a token:** *Web services → Manage tokens → Add* — for the integration user on
   that service. Copy the token.

## Portal-side setup

In `.env`:

```dotenv
MOODLE_BASE_URL=https://elearning.jooust.ac.ke
MOODLE_TOKEN=<the token from step 6>
MOODLE_AUTH=manual
MOODLE_DEFAULT_COHORT_ID=      # optional cohort id to add students to
MOODLE_STUDENT_ROLE_ID=5       # Moodle's default student role id
```

Then:

```bash
php artisan migrate            # adds moodle_user_id / moodle_synced_at to students
php artisan config:clear
php artisan moodle:ping        # verifies the URL + token, prints the site name
```

The provisioning runs on the queue, so a worker must be running in production:

```bash
php artisan queue:work
```

To provision students who already exist (backfill):

```bash
php artisan moodle:sync-students        # only unsynced students
php artisan moodle:sync-students --all  # re-queue everyone
```

## Single sign-on — now implemented

Students no longer need two accounts. The portal is now an OAuth 2 identity provider
(a custom, self-contained implementation — no Passport/third-party package required),
so Moodle can authenticate against it directly. Once configured, students click
**Learning Environment** in the sidebar (or "Log in with {{ your institution }} Portal"
on Moodle) and land in Moodle already signed in — no second password.

### 1. Register Moodle as a client (one-time, on the portal)

Either via the admin UI — **Admin > Single Sign-On** — or from the CLI:

```bash
php artisan oauth:client:create "Moodle LMS" "https://elearning.example.edu/admin/oauth2callback.php" --trusted
```

`--trusted` skips the "Allow Moodle to access your account?" consent screen — sensible
for your own institution's Moodle, since there's no real choice being made. Save the
printed **Client ID** and **Client Secret**; the secret is shown once.

### 2. Configure the OAuth 2 issuer on Moodle

Site administration → Server → **OAuth 2 services** → "Custom" (or "Other") issuer.
Recent Moodle versions (4.x) only show these fields on this screen — there's no
separate Authorization/Token/UserInfo URL box, and that's fine:

| Moodle field                                  | Value                                                        |
|------------------------------------------------|---------------------------------------------------------------|
| Name                                           | e.g. `Portal SSO`                                             |
| Client ID                                      | the Client ID from step 1                                     |
| Client secret                                  | the Client Secret from step 1                                 |
| Authenticate token requests via HTTP headers   | either — the portal accepts credentials both ways             |
| Service base URL                               | `https://<portal-domain>` (the portal's root URL, no path)    |
| Logo URL                                       | see below                                                      |
| This service will be used                      | **Login page and internal services** (or *Login page only*)   |
| Scopes / additional params                     | leave all blank — the portal doesn't require any               |

Moodle discovers the actual authorize/token/userinfo URLs itself by fetching
`{Service base URL}/.well-known/openid-configuration` — the portal now serves that
document (see below), so you never need to type those three URLs manually. Save the
issuer, then open it again — if endpoints were discovered, Moodle will show them
read-only under an **Endpoints** tab; if your Moodle version doesn't auto-fetch on
save, that same Endpoints tab lets you paste the three URLs in by hand:

```
Authorization endpoint URL:  https://<portal-domain>/oauth/authorize
Token endpoint URL:          https://<portal-domain>/oauth/token
UserInfo endpoint URL:       https://<portal-domain>/oauth/userinfo
```

For **Logo URL**, use whatever your portal logo resolves to — check
Admin > Branding on the portal, or default to `https://<portal-domain>/assets/img/JOOUSTLOGO1.png`
(or a custom uploaded logo, which now resolves to `https://<portal-domain>/brand-asset/<filename>`).

Under that issuer's **User field mappings** tab, map: `email` → email, `given_name` →
firstname, `family_name` → lastname, `idnumber` → idnumber. Then enable the **OAuth 2**
authentication plugin (Site administration → Plugins → Authentication) and set it as
an available login method.

### 3. Switch provisioning to OAuth auth

In the portal's `.env`:

```
MOODLE_AUTH=oauth2
```

`ProvisionMoodleUser` already checks this — once set, newly-provisioned Moodle accounts
are created without a Moodle password (auth method `oauth2`), so the only place a
student ever types a password is the portal's own login page.

### Endpoints this adds

| Route                  | Method | Purpose                                              |
|-------------------------|--------|-------------------------------------------------------|
| `/oauth/authorize`      | GET    | Browser-facing; requires a portal login (this *is* the SSO) |
| `/oauth/authorize`      | POST   | Consent approval (skipped for trusted clients)        |
| `/oauth/token`          | POST   | Server-to-server code/refresh-token exchange (CSRF-exempt) |
| `/oauth/userinfo`       | GET    | Bearer-token protected profile lookup                 |
| `/.well-known/openid-configuration` | GET | OIDC discovery document — lets Moodle auto-find the three URLs above from just the "Service base URL" |
| `/brand-asset/{path}`   | GET    | Serves uploaded logo/hero image (bypasses the storage:link symlink) |
| `/admin/sso`            | —      | Admin UI to add/revoke/trust OAuth clients            |

Codes are single-use and expire in 10 minutes; access tokens expire in 60 minutes;
refresh tokens rotate on every use and expire after 30 days. All secrets/tokens are
stored hashed, never in plaintext.

Other options, if OAuth 2 doesn't fit your setup:

- **LTI:** launch specific Moodle courses/activities embedded inside the portal, using
  the stored `moodle_user_id`, without sending the student to Moodle's own UI at all.
- **Shared directory (LDAP):** if both systems authenticate against the same directory,
  set `MOODLE_AUTH=ldap` and skip password provisioning entirely.

## What's not included yet (easy follow-ups)

- **Course enrolment mapping** — map each `Programme` to a Moodle course/category and call
  `enrol_manual_enrol_users` (helper `MoodleClient::enrolInCourse()` is already provided).
- **De-provisioning / suspension** — suspend the Moodle account when a student is withdrawn.
  This matters more now that SSO makes the portal the *only* front door.
- **LTI embedding** for specific courses/activities inside portal pages.
