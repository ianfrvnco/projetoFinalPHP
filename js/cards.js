const modal = document.getElementById('modalNoticia');
const fecharModal = document.getElementById('fecharModal');
const modalTitulo = document.getElementById('modalTitulo');
const modalConteudo = document.getElementById('modalConteudo');
const modalAutor = document.getElementById('modalAutor');
const modalEditado = document.getElementById('modalEditado');

document.querySelectorAll('.btn-expandir').forEach(btn => {
    btn.addEventListener('click', () => {
        modalTitulo.textContent = btn.dataset.titulo;
        modalConteudo.textContent = btn.dataset.conteudo;
        modalAutor.textContent = btn.dataset.autor;
        modalEditado.textContent = "Publicado/Última edição: " + btn.dataset.editado;
        modal.style.display = 'flex';
    });
});

document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        window.location.href = "editarNoticia.php?id=" + id;
    });
});

fecharModal.addEventListener('click', () => {
    document.getElementById('modalNoticia').style.display = 'none';
});