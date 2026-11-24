</main>
<footer class="footer text-center py-4">
    © <?= date('Y') ?> LIVE Campus · Academic Learning Platform
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Magnetic effect on landing-page action buttons
document.querySelectorAll('.hero-actions .btn-live').forEach(button => {
    button.addEventListener('mousemove', function(e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;
        this.style.transform = `translate(${x * 0.12}px, ${y * 0.12}px) scale(1.05)`;
    });
    button.addEventListener('mouseleave', function() {
        this.style.transform = '';
    });
});
</script>
</body>
</html>
