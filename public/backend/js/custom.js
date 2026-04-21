!(function ($) {
  "use strict";
  // input sticky placeholder for input & normal select
  document.addEventListener("DOMContentLoaded", function () {
    var inputs = document.querySelectorAll('.input_wrap input, .input_wrap select');

    inputs.forEach(function (input) {
      toggleLabel(input);

      // For input elements
      if (input.tagName === 'INPUT') {
        input.addEventListener('input', function () {
          toggleLabel(input);
        });
      }

      // For select elements
      if (input.tagName === 'SELECT') {
        input.addEventListener('change', function () {
          toggleLabel(input);
        });
      }

      // Additional blur event for better UX
      input.addEventListener('blur', function () {
        toggleLabel(input);
      });
    });

    function toggleLabel(element) {
      if (element.tagName === 'INPUT') {
        if (element.value.trim() !== '') {
          element.classList.add('has-value');
        } else {
          element.classList.remove('has-value');
        }
      }

      if (element.tagName === 'SELECT') {
        if (element.value !== '') {
          element.classList.add('has-value');
        } else {
          element.classList.remove('has-value');
        }
      }
    }
  });

  // input sticky placeholder for select2
  $(document).ready(function () {

    $('.select2').select2();

    $('.select2').each(function () {
      let select = this;
      let wrapper = select.closest('.input_wrap');
      let label = wrapper.querySelector('.label_text');

      // Edit page load
      if (select.value) {
        label.classList.add('has-value');
      }

      $(select).on('change select2:open', function () {
        label.classList.add('has-value');
      });

      $(select).on('select2:close', function () {
        if (!select.value) {
          label.classList.remove('has-value');
        }
      });
    });
});
})(jQuery);