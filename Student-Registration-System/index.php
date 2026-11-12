<?php
declare(strict_types=1);
session_start();
date_default_timezone_set('Asia/Kolkata');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');

// Restore previously entered fields after a server-side validation error.
$previous = $_SESSION['registration_old'] ?? [];
unset($_SESSION['registration_old']);
function oldValue(string $name): string {
    global $previous;
    $value = $previous[$name] ?? '';
    return htmlspecialchars(is_string($value) ? $value : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function oldSelected(string $name, string $option): string {
    global $previous;
    return isset($previous[$name]) && $previous[$name] === $option ? ' selected' : '';
}

if (empty($_SESSION['registration_csrf'])) {
    $_SESSION['registration_csrf'] = bin2hex(random_bytes(32));
}
$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Online student registration form with instant validation and a printable confirmation page.">
  <title>Student Registration | CampusForm</title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="page-shell">
    <header class="site-header">
      <a class="brand" href="index.php" aria-label="CampusForm home">
        <span class="brand-mark" aria-hidden="true">
          <svg viewBox="0 0 32 32" fill="none"><path d="m3 13 13-7 13 7-13 7L3 13Z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/><path d="M9 17v7c4 3 10 3 14 0v-7M29 14v10" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
        <span>campus<span class="brand-accent">form</span></span>
      </a>
      <span class="header-meta"><span class="status-dot" aria-hidden="true"></span> Student application portal</span>
    </header>

    <main class="layout">
      <aside class="intro-panel" aria-label="Registration introduction">
        <div class="eyebrow"><span class="eyebrow-icon">✦</span> A SIMPLE WAY TO GET STARTED</div>
        <h1>Start your <span>next chapter.</span></h1>
        <p class="intro-text">One form, one step closer to your goals. Complete your details below to create a clear, printable registration summary.</p>
        <div class="intro-points">
          <div class="intro-point"><span class="point-icon" aria-hidden="true">01</span><div><strong>Fill your details</strong><span>Personal, academic and contact information.</span></div></div>
          <div class="intro-point"><span class="point-icon" aria-hidden="true">02</span><div><strong>Check for accuracy</strong><span>Instant validation helps prevent mistakes.</span></div></div>
          <div class="intro-point"><span class="point-icon" aria-hidden="true">03</span><div><strong>View your summary</strong><span>Submit to see and print your information.</span></div></div>
        </div>
        <div class="intro-bottom">
          <span class="small-badge">2026 – 2027</span>
          <p>Registration form demonstration</p>
        </div>
      </aside>

      <section class="form-panel" aria-labelledby="form-title">
        <div class="form-heading">
          <span class="section-kicker">REGISTRATION FORM</span>
          <h2 id="form-title">Tell us about yourself</h2>
          <p>Fields marked <span class="required">*</span> are required.</p>
        </div>
        <div class="progress-panel">
          <div class="progress-copy"><strong>Form completion</strong><span id="progress-text" aria-live="polite">0 of 12 fields completed</span></div>
          <div class="progress-track" role="progressbar" aria-label="Form completion" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" id="progress-bar"><span id="progress-fill"></span></div>
        </div>

        <form id="registration-form" action="submit.php" method="post" novalidate>
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['registration_csrf'], ENT_QUOTES, 'UTF-8') ?>">

          <div class="group-title"><span class="group-number">01</span><div><h3>Personal information</h3><p>Let's start with the basics</p></div></div>
          <div class="fields-grid">
            <div class="field span-2"><label for="full_name">Full name <span class="required">*</span></label><input type="text" id="full_name" name="full_name" placeholder="e.g. Alex Kumar" autocomplete="name" maxlength="80" aria-describedby="full_name-error" required value="<?= oldValue('full_name') ?>"><span class="field-error" id="full_name-error"></span></div>
            <div class="field"><label for="email">Email address <span class="required">*</span></label><input type="email" id="email" name="email" placeholder="alex@example.com" autocomplete="email" maxlength="150" aria-describedby="email-error" required value="<?= oldValue('email') ?>"><span class="field-error" id="email-error"></span></div>
            <div class="field"><label for="phone">Phone number <span class="required">*</span></label><div class="phone-input"><span aria-hidden="true">+91</span><input type="tel" id="phone" name="phone" inputmode="numeric" placeholder="9876543210" autocomplete="tel-national" maxlength="10" aria-describedby="phone-error" required value="<?= oldValue('phone') ?>"></div><span class="field-error" id="phone-error"></span></div>
            <div class="field"><label for="dob">Date of birth <span class="required">*</span></label><input type="date" id="dob" name="dob" max="<?= $today ?>" value="<?= oldValue('dob') ?>" aria-describedby="dob-error" required><span class="field-error" id="dob-error"></span></div>
            <div class="field"><label for="gender">Gender <span class="required">*</span></label><select id="gender" name="gender" aria-describedby="gender-error" required><option value="">Select gender</option><option value="Male"<?= oldSelected('gender', 'Male') ?>>Male</option><option value="Female"<?= oldSelected('gender', 'Female') ?>>Female</option><option value="Non-binary"<?= oldSelected('gender', 'Non-binary') ?>>Non-binary</option><option value="Prefer not to say"<?= oldSelected('gender', 'Prefer not to say') ?>>Prefer not to say</option></select><span class="field-error" id="gender-error"></span></div>
          </div>

          <div class="section-divider"></div>
          <div class="group-title"><span class="group-number">02</span><div><h3>Academic information</h3><p>What are you studying?</p></div></div>
          <div class="fields-grid">
            <div class="field span-2"><label for="program">Course / program <span class="required">*</span></label><select id="program" name="program" aria-describedby="program-error" required><option value="">Choose your course</option><option value="Computer Science & Engineering"<?= oldSelected('program', 'Computer Science & Engineering') ?>>Computer Science &amp; Engineering</option><option value="Artificial Intelligence & Machine Learning"<?= oldSelected('program', 'Artificial Intelligence & Machine Learning') ?>>Artificial Intelligence &amp; Machine Learning</option><option value="Information Science & Engineering"<?= oldSelected('program', 'Information Science & Engineering') ?>>Information Science &amp; Engineering</option><option value="Electronics & Communication"<?= oldSelected('program', 'Electronics & Communication') ?>>Electronics &amp; Communication</option><option value="Mechanical Engineering"<?= oldSelected('program', 'Mechanical Engineering') ?>>Mechanical Engineering</option><option value="Civil Engineering"<?= oldSelected('program', 'Civil Engineering') ?>>Civil Engineering</option><option value="BCA"<?= oldSelected('program', 'BCA') ?>>BCA</option><option value="MCA"<?= oldSelected('program', 'MCA') ?>>MCA</option><option value="Other"<?= oldSelected('program', 'Other') ?>>Other</option></select><span class="field-error" id="program-error"></span></div>
            <div class="field"><label for="qualification">Highest qualification <span class="required">*</span></label><select id="qualification" name="qualification" aria-describedby="qualification-error" required><option value="">Select qualification</option><option value="PUC / 12th"<?= oldSelected('qualification', 'PUC / 12th') ?>>PUC / 12th</option><option value="Diploma"<?= oldSelected('qualification', 'Diploma') ?>>Diploma</option><option value="Undergraduate degree"<?= oldSelected('qualification', 'Undergraduate degree') ?>>Undergraduate degree</option><option value="Postgraduate degree"<?= oldSelected('qualification', 'Postgraduate degree') ?>>Postgraduate degree</option><option value="Other"<?= oldSelected('qualification', 'Other') ?>>Other</option></select><span class="field-error" id="qualification-error"></span></div>
            <div class="field"><label for="study_year">Year of study <span class="required">*</span></label><select id="study_year" name="study_year" aria-describedby="study_year-error" required><option value="">Select year</option><option value="1st year"<?= oldSelected('study_year', '1st year') ?>>1st year</option><option value="2nd year"<?= oldSelected('study_year', '2nd year') ?>>2nd year</option><option value="3rd year"<?= oldSelected('study_year', '3rd year') ?>>3rd year</option><option value="4th year"<?= oldSelected('study_year', '4th year') ?>>4th year</option></select><span class="field-error" id="study_year-error"></span></div>
          </div>

          <div class="section-divider"></div>
          <div class="group-title"><span class="group-number">03</span><div><h3>Contact details</h3><p>Where can we reach you?</p></div></div>
          <div class="fields-grid">
            <div class="field span-2"><label for="address">Street address <span class="required">*</span></label><textarea id="address" name="address" rows="3" placeholder="House / street / area" maxlength="350" autocomplete="street-address" aria-describedby="address-error" required><?= oldValue('address') ?></textarea><span class="field-error" id="address-error"></span></div>
            <div class="field"><label for="city">City <span class="required">*</span></label><input type="text" id="city" name="city" placeholder="e.g. Bengaluru" autocomplete="address-level2" maxlength="80" aria-describedby="city-error" required value="<?= oldValue('city') ?>"><span class="field-error" id="city-error"></span></div>
            <div class="field"><label for="state">State <span class="required">*</span></label><input type="text" id="state" name="state" placeholder="e.g. Karnataka" autocomplete="address-level1" maxlength="80" aria-describedby="state-error" required value="<?= oldValue('state') ?>"><span class="field-error" id="state-error"></span></div>
            <div class="field span-2"><label for="pincode">PIN code <span class="required">*</span></label><input type="text" id="pincode" name="pincode" placeholder="6-digit postal PIN" inputmode="numeric" autocomplete="postal-code" maxlength="6" aria-describedby="pincode-error" required value="<?= oldValue('pincode') ?>"><span class="field-error" id="pincode-error"></span></div>
          </div>

          <div class="consent-wrap"><label class="consent-label" for="terms"><input type="checkbox" id="terms" name="terms" value="yes" <?= oldSelected('terms', 'yes') === ' selected' ? 'checked' : '' ?> aria-describedby="terms-error" required><span>I confirm that the information provided is accurate and complete.</span></label><span class="field-error" id="terms-error"></span></div>
          <div class="submit-area"><button class="submit-button" type="submit"><span>Submit registration</span><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button><p>Your details will appear on the next page. No database storage is used in this demo.</p></div>
        </form>
      </section>
    </main>
    <footer class="site-footer"><span>© <?= date('Y') ?> CampusForm · Student registration demo</span><span>Built with HTML, CSS, JavaScript, jQuery &amp; PHP</span></footer>
  </div>
  <script src="assets/js/jquery-3.6.1.min.js"></script>
  <script src="assets/js/script.js"></script>
</body>
</html>
