</div> <!-- Затваряне на container -->

<footer class="mt-5 py-3 bg-dark text-white text-center">
    <div class="container">
        <p class="mb-0">&copy; <?= date('Y') ?> Управление на потребители. Всички права запазени.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Активиране на dropdowns
    document.querySelectorAll('.dropdown-toggle').forEach(item => {
        item.addEventListener('click', function (e) {
            if (document.documentElement.clientWidth < 992) {
                e.preventDefault();
                this.nextElementSibling.classList.toggle('show');
            }
        });
    });
</script>
</body>
</html>