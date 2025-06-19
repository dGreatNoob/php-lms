document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('theme-toggle');
  const html = document.documentElement;
  // Load saved theme
  const saved = localStorage.getItem('theme');
  if (saved === 'dark') {
    html.classList.remove('theme-light');
    html.classList.add('theme-dark');
  } else {
    html.classList.remove('theme-dark');
    html.classList.add('theme-light');
  }
  btn.addEventListener('click', function () {
    if (html.classList.contains('theme-dark')) {
      html.classList.remove('theme-dark');
      html.classList.add('theme-light');
      localStorage.setItem('theme', 'light');
    } else {
      html.classList.remove('theme-light');
      html.classList.add('theme-dark');
      localStorage.setItem('theme', 'dark');
    }
  });
}); 