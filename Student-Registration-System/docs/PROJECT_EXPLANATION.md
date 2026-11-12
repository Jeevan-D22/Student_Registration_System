# Project Explanation — Online Student Registration

## 1. Project title
**CampusForm — Online Student Registration System**

## 2. Problem statement
Manual student registration forms can be time-consuming, difficult to verify and inconvenient to print. This mini-project demonstrates a browser-based registration form that accepts details, validates them and produces a readable summary after submission.

## 3. Objectives
- Build a responsive user-friendly form using HTML and CSS.
- Use JavaScript and jQuery for client-side validation and dynamic UI feedback.
- Send form data securely to a PHP script using HTTP POST.
- Validate inputs on the server and display a successful formatted confirmation page.
- Enable printing of the submitted information directly from the browser.

## 4. Technologies used

| Technology | Usage |
| --- | --- |
| HTML5 | Input controls, sections, semantic labels and required attributes |
| CSS3 | Layout, typography, responsive design, error styling and print styles |
| JavaScript | Date validation and interactive browser logic |
| jQuery | DOM selection, event handling, progress updates and field validation |
| PHP | Sessions, CSRF tokens, request handling, field validation and confirmation output |

## 5. Main modules

**Registration module (`index.php`).** Displays personal fields (name, email, phone, birth date and gender), academic fields (program, qualification and study year), and contact fields (address, city, state and postal PIN). A declaration checkbox must be checked.

**Front-end validation (`assets/js/script.js`).** Calculates form completion and warns about invalid or missing entries. It removes non-digits from phone and PIN fields and prevents submission if the inputs do not meet requirements.

**Backend processing (`submit.php`).** Accepts a POST request, validates a session CSRF token and checks field values independently. If the request is invalid, it shows specific errors. If valid, it presents a summary containing all submitted details.

**Print output (CSS + `window.print()`).** The print button opens the browser print dialog; a dedicated CSS media query hides navigation/actions when printing.

## 6. Data flow

```text
                         +--------------------+
                         |     Student/User   |
                         +---------+----------+
                                   |
                                   v
                         +--------------------+
                         | index.php (HTML)   |
                         | 12 fields + terms  |
                         +---------+----------+
                                   |
                                   v
                         +--------------------+
                         | jQuery/JavaScript  |
                         | Client validation  |
                         +---------+----------+
                                   |
                                   v
                         +--------------------+
                         | HTTP POST          |
                         | submit.php         |
                         +---------+----------+
                                   |
                                   v
                         +--------------------+
                         | PHP validation     |
                         | + CSRF protection  |
                         +-----+---------+----+
                               |         |
                     Invalid   |         | Valid
                               v         v
                         +----------+ +------------------+
                         | Errors + | | Confirmation +   |
                         | retry    | | Print application|
                         +----------+ +------------------+
```

## 7. Input validation

- Full name: 3–80 characters
- Email: valid email format, up to 150 characters
- Phone: Indian mobile number (10 digits starting with 6–9)
- Date of birth: real date, not in the future
- Gender/course/qualification/year: one of the listed choices
- Address: 5–350 characters
- City and state: 2–80 characters
- PIN: six digits, first digit non-zero
- Declaration checkbox: required

## 8. Security and limitations

The project uses server-side validation, CSRF protection and safe HTML escaping. For simplicity, **no database or user authentication exists**, so the form does not store or issue an official registration ID. In a real institution, add HTTPS, consent/privacy details, protected data storage and appropriate administrative access controls.

## 9. Expected result

When valid information is submitted, the next page shows a green success indicator, date/time, clearly grouped student details and a print button. If validation fails, the server displays errors and the user can return to the form with their previous details restored.

## 10. Short viva questions

**Q. What is the role of HTML?** It provides the structure of the form and defines input elements.

**Q. What is the role of CSS?** It styles the layout, supports mobile screens and controls print formatting.

**Q. Why use JavaScript and jQuery together?** JavaScript provides browser programming; jQuery simplifies DOM queries and event handlers.

**Q. Why do we need PHP?** PHP runs on the server, receives submitted data, validates it and generates the result HTML.

**Q. What does `method="post"` do?** It sends form values in the HTTP request body instead of placing them in the URL query string.

**Q. Why validate in PHP when JavaScript validates already?** Client-side checks can be bypassed; the server must verify all inputs independently.

**Q. What is CSRF?** Cross-site request forgery. A session-backed token helps reject requests not originating from the expected form workflow.

**Q. Is information stored?** No. This demonstration only displays validated input, without a database.

**Q. Can GitHub Pages run the PHP form?** No. GitHub Pages serves static sites and does not execute PHP.

**Q. How do users print their information?** The page calls `window.print()`; print-specific CSS hides unnecessary elements.
