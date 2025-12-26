</main>

<footer class="text-center py-4 mt-5">
    <p>&copy; <?php echo date("Y"); ?> ToolForge. All Rights Reserved.</p>
    <p class="text-muted">This project is open-source on <a href="https://github.com/efemehmet1965/ToolForge" target="_blank" class="text-primary text-decoration-none">GitHub</a>.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.select();
                    document.execCommand('copy');
                    const originalText = this.textContent;
                    this.textContent = 'Copied!';
                    setTimeout(() => {
                        this.textContent = originalText;
                    }, 2000);
                }
            });
        });
    });
</script>
</body>
</html>