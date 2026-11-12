<script>
(function () {
    try {
        var t = localStorage.getItem('cm-theme');
        if (!t) t = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', t);
        document.documentElement.setAttribute('data-bs-theme', t);
    } catch (e) {}
})();
</script>
