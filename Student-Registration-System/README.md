# CampusForm — Online Student Registration System

**Complete college web-technology mini-project** built using **HTML5, CSS3, JavaScript, jQuery 3.6.1 and PHP 8**.

CampusForm provides a modern registration interface, validates details before sending them, and presents a **formatted, printable confirmation page after successful submission**. The project uses plain PHP; no framework, npm setup or database is necessary.

## Features

- Modern responsive design for desktop, tablet and mobile
- Personal, academic and contact information (12 required fields)
- Native HTML inputs plus custom jQuery validation with clear error messages
- Live progress indicator and numeric-only checks for phone/PIN fields
- PHP server-side validation (the server never relies on JavaScript for correctness)
- Session-based CSRF protection and escaped HTML output
- Print/Save-as-PDF button on the successful confirmation page
- Previously entered values restored when correcting server validation errors
- Local copy of jQuery, so it does not require a CDN
- No account, database, API key or external service required

## Project structure

```text
Student-Registration-System/
├── index.php                 # Registration form: HTML + PHP session token
├── submit.php                # PHP validator + formatted confirmation page
├── assets/
│   ├── favicon.svg           # Browser tab icon
│   ├── css/
│   │   └── style.css         # Responsive design and printer-friendly CSS
│   └── js/
│       ├── jquery-3.6.1.min.js  # Locally bundled jQuery library
│       └── script.js         # Input validation and progress logic
├── docs/
│   ├── PROJECT_EXPLANATION.md
│   └── TEST_CASES.md
├── .gitignore
└── README.md
```

## Upload to GitHub — no need to execute the application

1. Download and **extract** the ZIP file.
2. Open your repository: <https://github.com/GM-PREETHAM/Student-Registration-System>.
3. Select **Add file → Upload files**.
4. Open the extracted `Student-Registration-System` folder and drag **its contents** into GitHub (`index.php`, `submit.php`, `assets`, `docs`, `.gitignore`, `README.md`).
5. Use the commit message `Add complete Student Registration System` and select **Commit changes**.

If your repository already has a `README.md`, GitHub will show a replacement when you upload this README. That is okay if you want to use the more detailed one included in this ZIP.

> **GitHub is a source code host, not a PHP server.** You can upload, view and share all the code without installing XAMPP or running the project. **GitHub Pages does not execute PHP**, so this PHP form cannot process real submissions on GitHub Pages.

## Run locally (optional; only if you ever need to demonstrate functionality)

### Option A: XAMPP on Windows

1. Install XAMPP from <https://www.apachefriends.org/> if needed.
2. Copy the extracted `Student-Registration-System` folder to `C:\xampp\htdocs\`.
3. In XAMPP Control Panel, start **Apache**; MySQL is **not** required.
4. Visit <http://localhost/Student-Registration-System/>.
5. Fill the form, click **Submit registration**, then view/print the confirmation.

### Option B: PHP's built-in development server

With PHP 8+ installed, open a terminal inside the project folder:

```bash
php -S localhost:8000
```

Open <http://localhost:8000/>. The built-in server is for local development/testing only, not production deployment.

**Do not double-click `index.php`** expecting it to run: a PHP-capable server is required for PHP session tokens and form submissions.

## How it works

```text
User enters details in index.php
      ↓
JavaScript / jQuery checks required fields and updates progress
      ↓
Click Submit Registration → POST request to submit.php
      ↓
PHP checks CSRF token, lengths, formats, date and allowed options
      ├── Invalid → Display errors and restore previous form values
      └── Valid → Display styled application summary and Print button
```

## Technical notes

- Browser validation improves user experience; PHP validation is **authoritative**.
- The application escapes user-submitted strings before adding them to HTML.
- A session token protects the form against cross-site request forgery.
- Submission is a **demo only**: it **does not store personal information**, email it, create a login, or register anyone at an institution.
- The printable page displays submitted values in the response to a valid POST. If you refresh that page, the browser might ask to resubmit the form; a one-time CSRF token rejects duplicate submissions.
- The page uses an optional Google Fonts stylesheet. Without internet, fallback fonts remain usable and jQuery is local.

## Documentation

- [Project Explanation and Viva Notes](docs/PROJECT_EXPLANATION.md)
- [Manual Test Cases](docs/TEST_CASES.md)

## Third-party library

jQuery v3.6.1 is distributed under the **MIT License** by the OpenJS Foundation and contributors. Its license is referenced in the banner of `assets/js/jquery-3.6.1.min.js` and at <https://jquery.org/license/>.
