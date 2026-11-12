# Connect Me - what was changed

1. Find Jobs search: Job Title (suggestions), Qualifications, Experience, Company Name dropdowns + Apply button.
   (partials/job-filters.blade.php, Vacancy model filter, Page/Youth controllers)
2. Employer "Find Skilled Youth": Name, Qualifications (degree class / university suggestions), Location (area suggestions) + "Contact Skilled Employee" button.
3. Admin login fix: the database had NO admin account. Migration 2026_11_12_000000 creates it; `php artisan admin:create` creates/resets it.
   Admin also got: Contact Messages page, Platform Report.
4. Light / dark mode toggle (navbar + dashboards, remembered in the browser).
5. Sign-up notification by email (Laravel mail) and SMS (app/Services/SmsService.php - Africa's Talking or Twilio).
6. Reports: /youth/my-report (weekly / monthly / annual, printable), /employer/recruitment-report, /admin/platform-report.

Run:  php artisan migrate   then   php artisan admin:create   then   php artisan optimize:clear
Admin: admin@connectme.com / Admin@12345  (change after first login)
