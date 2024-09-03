<div x-data="{ isVisible: false }" x-init="
        window.addEventListener('scroll', () => {
            isVisible = window.scrollY > 100;
        });
    "
    class="position-fixed bottom-0 end-0 m-3">

    <button x-show="isVisible"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="btn btn-dark btn-lg shadow">
        <i class="fa-solid fa-arrow-up"></i>
    </button>
</div>
