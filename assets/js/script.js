/* Client-side validation and progress updates with jQuery.
   The server performs independent validation in submit.php. */
$(function () {
  'use strict';

  const $form = $('#registration-form');
  if (!$form.length) return;

  const requiredFields = [
    'full_name', 'email', 'phone', 'dob', 'gender', 'program',
    'qualification', 'study_year', 'address', 'city', 'state', 'pincode'
  ];

  const rules = {
    full_name(value) {
      return value.length >= 3 && value.length <= 80 ? '' : 'Please enter your full name (3–80 characters).';
    },
    email(value) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) && value.length <= 150 ? '' : 'Enter a valid email address.';
    },
    phone(value) {
      return /^[6-9]\d{9}$/.test(value) ? '' : 'Enter a valid 10-digit Indian mobile number.';
    },
    dob(value) {
      if (!/^\d{4}-\d{2}-\d{2}$/.test(value)) return 'Please select your date of birth.';
      const [year, month, day] = value.split('-').map(Number);
      const date = new Date(year, month - 1, day);
      const now = new Date();
      now.setHours(0, 0, 0, 0);
      const realDate = date.getFullYear() === year && date.getMonth() === month - 1 && date.getDate() === day;
      return realDate && date <= now ? '' : 'Enter a valid past date.';
    },
    gender: required,
    program: required,
    qualification: required,
    study_year: required,
    address(value) {
      return value.length >= 5 && value.length <= 350 ? '' : 'Please enter your full address (at least 5 characters).';
    },
    city(value) {
      return value.length >= 2 && value.length <= 80 ? '' : 'Enter a valid city name.';
    },
    state(value) {
      return value.length >= 2 && value.length <= 80 ? '' : 'Enter a valid state name.';
    },
    pincode(value) {
      return /^[1-9]\d{5}$/.test(value) ? '' : 'Enter a valid 6-digit PIN code.';
    }
  };

  function required(value) {
    return value ? '' : 'This field is required.';
  }

  function fieldValue(name) {
    return String($form.find('[name="' + name + '"]').val() || '').trim();
  }

  function validationMessage(name) {
    return rules[name](fieldValue(name));
  }

  function markField(name, message) {
    const $field = $form.find('[name="' + name + '"]');
    $field.attr('aria-invalid', Boolean(message));
    $field.closest('.field').toggleClass('has-error', Boolean(message));
    $('#' + name + '-error').text(message);
  }

  function updateProgress() {
    const completed = requiredFields.filter(name => !validationMessage(name)).length;
    const percentage = Math.round(completed / requiredFields.length * 100);
    $('#progress-text').text(completed + ' of ' + requiredFields.length + ' fields completed');
    $('#progress-fill').css('width', percentage + '%');
    $('#progress-bar').attr('aria-valuenow', percentage);
  }

  // Keep numeric inputs free of letters and punctuation.
  $('#phone, #pincode').on('input', function () {
    const length = this.id === 'phone' ? 10 : 6;
    this.value = this.value.replace(/\D/g, '').slice(0, length);
  });

  $form.on('input change blur', 'input, select, textarea', function (event) {
    const name = this.name;
    if (!requiredFields.includes(name)) {
      if (name === 'terms' && this.checked) {
        $('#terms-error').text('');
        $('.consent-wrap').removeClass('has-error');
      }
      return;
    }
    if (event.type === 'blur' || $(this).attr('aria-invalid') === 'true' || event.type === 'change') {
      markField(name, validationMessage(name));
    }
    updateProgress();
  });

  $form.on('submit', function (event) {
    let firstInvalid = null;
    requiredFields.forEach(name => {
      const message = validationMessage(name);
      markField(name, message);
      if (message && !firstInvalid) firstInvalid = $form.find('[name="' + name + '"]');
    });

    const agreed = $('#terms').is(':checked');
    $('#terms-error').text(agreed ? '' : 'Please confirm the declaration.');
    $('.consent-wrap').toggleClass('has-error', !agreed);
    if (!agreed && !firstInvalid) firstInvalid = $('#terms');

    updateProgress();
    if (firstInvalid) {
      event.preventDefault();
      firstInvalid.trigger('focus');
      firstInvalid[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }
    $('.submit-button').addClass('is-loading').prop('disabled', true).find('span').text('Submitting…');
    // Normal form POST: PHP validates the data and renders the confirmation page.
  });

  updateProgress();
});
