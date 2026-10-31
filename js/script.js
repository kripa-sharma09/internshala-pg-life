document.addEventListener('DOMContentLoaded', () => {

  // Prevent accidental double submission.
  document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', () => {
      const submitButton = form.querySelector('button[type="submit"]');

      if (submitButton && !form.checkValidity()) return;

      if (submitButton && !submitButton.dataset.noDisable) {
        submitButton.dataset.originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Please wait…';
      }
    });
  });

  // Show loading animation when sorting properties.
  const sortForm = document.getElementById('sort-form');
const sortSelect = document.getElementById('sort');

if (sortForm && sortSelect) {
    sortSelect.addEventListener('change', () => {
        sortForm.requestSubmit();
    });
}
    // Hide the full-page loader after the page finishes loading.
  const pageLoader = document.getElementById('page-loader');

  window.addEventListener('load', () => {
    if (pageLoader) {
      pageLoader.classList.add('loader-hidden');
    }
  });

});