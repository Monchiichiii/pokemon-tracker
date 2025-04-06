<div id="image-modal" class="image-modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="modal-img">
</div>

<footer>
    <div class="footer-content">
        <p>&copy; <?= date('Y') ?> Pokémon Card Tracker. Not affiliated with Nintendo or The Pokémon Company.</p>
    </div>
</footer>

<style>
footer {
    background-color: #ef5350;
    color: white;
    text-align: center;
    padding: 1rem 0;
    margin-top: 4rem;
    font-size: 0.9rem;
    position: relative;
    bottom: 0;
    width: 100%;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("image-modal");
    const modalImg = document.getElementById("modal-img");
    const closeBtn = document.querySelector(".image-modal .close");

    document.querySelectorAll(".enlargeable").forEach(img => {
        img.addEventListener("click", () => {
            modal.style.display = "block";
            modalImg.src = img.dataset.full;
        });
    });

    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
});
</script>

