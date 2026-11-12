<?php
declare(strict_types=1);
session_start();
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php', true, 303);
    exit;
}

function value(string $key): string {
    $raw = $_POST[$key] ?? '';
    return is_string($raw) ? trim($raw) : '';
}

function safe(string $input): string {
    return htmlspecialchars($input, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$validCsrf = isset($_SESSION['registration_csrf'])
    && hash_equals($_SESSION['registration_csrf'], value('csrf_token'));

$fields = [
    'Full name' => value('full_name'),
    'Email address' => value('email'),
    'Phone number' => value('phone'),
    'Date of birth' => value('dob'),
    'Gender' => value('gender'),
    'Course / program' => value('program'),
    'Highest qualification' => value('qualification'),
    'Year of study' => value('study_year'),
    'Street address' => value('address'),
    'City' => value('city'),
    'State' => value('state'),
    'PIN code' => value('pincode')
];

$allowedGenders = ['Male', 'Female', 'Non-binary', 'Prefer not to say'];
$allowedPrograms = ['Computer Science & Engineering', 'Artificial Intelligence & Machine Learning', 'Information Science & Engineering', 'Electronics & Communication', 'Mechanical Engineering', 'Civil Engineering', 'BCA', 'MCA', 'Other'];
$allowedQualifications = ['PUC / 12th', 'Diploma', 'Undergraduate degree', 'Postgraduate degree', 'Other'];
$allowedYears = ['1st year', '2nd year', '3rd year', '4th year'];

$birth = $fields['Date of birth'];
$birthDate = DateTimeImmutable::createFromFormat('!Y-m-d', $birth);
$validDate = $birthDate !== false && $birthDate->format('Y-m-d') === $birth && $birthDate <= new DateTimeImmutable('today');

$errors = [];
if (!$validCsrf) $errors[] = 'The session has expired or the request is invalid. Please reload the form.';
if (!preg_match('/^.{3,80}$/us', $fields['Full name'])) $errors[] = 'Enter a valid full name.';
if (!filter_var($fields['Email address'], FILTER_VALIDATE_EMAIL) || strlen($fields['Email address']) > 150) $errors[] = 'Enter a valid email address.';
if (!preg_match('/^[6-9][0-9]{9}$/D', $fields['Phone number'])) $errors[] = 'Enter a valid 10-digit Indian mobile number.';
if (!$validDate) $errors[] = 'Enter a valid date of birth.';
if (!in_array($fields['Gender'], $allowedGenders, true)) $errors[] = 'Choose a valid gender option.';
if (!in_array($fields['Course / program'], $allowedPrograms, true)) $errors[] = 'Choose a valid course.';
if (!in_array($fields['Highest qualification'], $allowedQualifications, true)) $errors[] = 'Choose a valid qualification.';
if (!in_array($fields['Year of study'], $allowedYears, true)) $errors[] = 'Choose a valid year of study.';
if (!preg_match('/^.{5,350}$/us', $fields['Street address'])) $errors[] = 'Enter a complete street address.';
if (!preg_match('/^.{2,80}$/us', $fields['City'])) $errors[] = 'Enter a valid city.';
if (!preg_match('/^.{2,80}$/us', $fields['State'])) $errors[] = 'Enter a valid state.';
if (!preg_match('/^[1-9][0-9]{5}$/D', $fields['PIN code'])) $errors[] = 'Enter a valid 6-digit PIN code.';
if (value('terms') !== 'yes') $errors[] = 'You must confirm the declaration.';

$success = count($errors) === 0;
if (!$success) {
    http_response_code(422);
    // Let the user correct invalid details instead of starting from a blank form.
    $_SESSION['registration_old'] = [
        'full_name' => $fields['Full name'],
        'email' => $fields['Email address'],
        'phone' => $fields['Phone number'],
        'dob' => $fields['Date of birth'],
        'gender' => $fields['Gender'],
        'program' => $fields['Course / program'],
        'qualification' => $fields['Highest qualification'],
        'study_year' => $fields['Year of study'],
        'address' => $fields['Street address'],
        'city' => $fields['City'],
        'state' => $fields['State'],
        'pincode' => $fields['PIN code'],
        'terms' => value('terms')
    ];
} else {
    unset($_SESSION['registration_csrf']); // One-time token; use a fresh form for another submission.
}

$dateSubmitted = (new DateTimeImmutable('now', new DateTimeZone('Asia/Kolkata')))->format('d M Y, h:i A');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title><?= $success ? 'Registration Submitted' : 'Please Check Your Details' ?> | CampusForm</title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="result-page">
  <div class="page-shell">
    <header class="site-header">
      <a class="brand" href="index.php" aria-label="CampusForm home"><span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 32 32" fill="none"><path d="m3 13 13-7 13 7-13 7L3 13Z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/><path d="M9 17v7c4 3 10 3 14 0v-7M29 14v10" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg></span><span>campus<span class="brand-accent">form</span></span></a>
      <span class="header-meta"><span class="status-dot" aria-hidden="true"></span> Student application portal</span>
    </header>
    <main class="result-main">
      <?php if ($success): ?>
        <section class="result-card" aria-labelledby="result-title">
          <div class="result-hero">
            <div class="success-icon" aria-hidden="true"><svg viewBox="0 0 32 32" fill="none"><path d="m7 16 6 6 12-13" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
            <span class="section-kicker">SUBMISSION COMPLETE</span>
            <h1 id="result-title">You're all set, <?= safe(explode(' ', $fields['Full name'])[0]) ?>!</h1>
            <p>Your form was submitted successfully. Here's a neatly organized copy of the information you provided.</p>
            <span class="submitted-at">Submitted on <?= safe($dateSubmitted) ?> IST</span>
          </div>
          <div class="summary-heading"><div><span class="section-kicker">YOUR REGISTRATION</span><h2>Application summary</h2></div><span class="summary-chip">SUBMITTED</span></div>

          <section class="summary-section" aria-labelledby="personal-summary"><h3 id="personal-summary"><span class="group-number">01</span> Personal information</h3><dl class="summary-grid">
            <?php foreach (['Full name', 'Email address', 'Phone number', 'Date of birth', 'Gender'] as $key): ?>
              <div><dt><?= safe($key) ?></dt><dd><?= safe($key === 'Phone number' ? '+91 ' . $fields[$key] : ($key === 'Date of birth' ? $birthDate->format('d F Y') : $fields[$key])) ?></dd></div>
            <?php endforeach; ?>
          </dl></section>
          <section class="summary-section" aria-labelledby="academic-summary"><h3 id="academic-summary"><span class="group-number">02</span> Academic information</h3><dl class="summary-grid">
            <?php foreach (['Course / program', 'Highest qualification', 'Year of study'] as $key): ?>
              <div><dt><?= safe($key) ?></dt><dd><?= safe($fields[$key]) ?></dd></div>
            <?php endforeach; ?>
          </dl></section>
          <section class="summary-section" aria-labelledby="contact-summary"><h3 id="contact-summary"><span class="group-number">03</span> Contact information</h3><dl class="summary-grid">
            <?php foreach (['Street address', 'City', 'State', 'PIN code'] as $key): ?>
              <div><dt><?= safe($key) ?></dt><dd><?= safe($fields[$key]) ?></dd></div>
            <?php endforeach; ?>
          </dl></section>
          <div class="result-actions"><button class="submit-button print-btn" type="button" onclick="window.print()"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 15h12v6H6v-6Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg> Print application</button><a class="secondary-button" href="index.php">Start a new registration</a></div>
          <p class="demo-note">Demo notice: information is displayed for this submission only. It is not saved to a database and does not complete enrollment with an institution.</p>
        </section>
      <?php else: ?>
        <section class="result-card error-card" aria-labelledby="result-title">
          <div class="result-hero"><div class="failure-icon" aria-hidden="true">!</div><span class="section-kicker">ACTION NEEDED</span><h1 id="result-title">Please check your submission</h1><p>We could not complete your request. Please correct the following issues:</p></div>
          <ul class="error-list"><?php foreach ($errors as $error): ?><li><?= safe($error) ?></li><?php endforeach; ?></ul>
          <div class="result-actions"><a class="submit-button" href="index.php">Return to form</a></div>
        </section>
      <?php endif; ?>
    </main>
    <footer class="site-footer"><span>© <?= date('Y') ?> CampusForm · Student registration demo</span><span>Built with HTML, CSS, JavaScript, jQuery &amp; PHP</span></footer>
  </div>
</body>
</html>
