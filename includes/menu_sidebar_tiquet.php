<!-- Panell lateral dret amb el detall d'un tiquet -->
<aside class="sidebar" id="sidebar-tiquet" aria-label="Detall del tiquet">

    <div class="sidebar__capcalera">
        <h2>Tiquet</h2>
        <a href="admin.php?apartat=tiquets" class="sidebar__tancar" aria-label="Tancar el panell">&times;</a>
    </div>

    <?php mostrar_tiquet($tiquetObert); ?>

</aside>
