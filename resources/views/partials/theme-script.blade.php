<script>
document.addEventListener('click', function (e) {
    var b = e.target.closest('#themeToggle'); if (!b) return;
    var next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    document.documentElement.setAttribute('data-bs-theme', next);
    try { localStorage.setItem('cm-theme', next); } catch (e) {}
});
</script>
