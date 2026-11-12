# Manual Test Cases

These cases can be checked after launching the project with a PHP server.

| Test | Input or action | Expected result |
| --- | --- | --- |
| T01 | Open `index.php` | Responsive form loads with all fields |
| T02 | Submit empty form | Submit is blocked; required fields show errors |
| T03 | Enter invalid email | Email validation message appears |
| T04 | Enter a phone with letters | Letters are removed by the UI |
| T05 | Enter a 5-digit PIN code | Postal code validation error appears |
| T06 | Choose future date of birth | Date is rejected |
| T07 | Leave declaration unchecked | Form cannot be submitted |
| T08 | Enter valid details and submit | PHP displays success page with all details |
| T09 | Select Print application | Browser print dialog opens; actions are hidden in print view |
| T10 | Send a manipulated POST without CSRF token | Backend rejects submission |
| T11 | Send HTML tags inside a field | Output is escaped, not run as HTML |
| T12 | Submit invalid data via PHP then return to form | Previous input is restored for correction |
| T13 | Resize browser to a mobile width | Form stacks vertically without horizontal overflow |
| T14 | Resubmit the exact same POST with its used token | PHP rejects the duplicate request |

## Valid sample inputs (for classroom demonstration)

| Field | Example |
| --- | --- |
| Full name | Ananya Rao |
| Email | ananya@example.com |
| Phone | 9876543210 |
| Date of birth | 2003-08-20 |
| Gender | Female |
| Course / program | Computer Science & Engineering |
| Highest qualification | Diploma |
| Year of study | 3rd year |
| Address | 12 MG Road, Indiranagar |
| City | Bengaluru |
| State | Karnataka |
| PIN code | 560001 |
| Declaration | Checked |

The values above are **fictional demo details**, not real student information.
