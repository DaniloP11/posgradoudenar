function confirmDelete(url) {
    if (confirm('¿Estás seguro de eliminar estos datos?')) {
        window.location.href = url;
    }
}
