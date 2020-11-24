</div>
<!-- FOOTER -->
<?php
if (is_active_sidebar('de-footer-1') || is_active_sidebar('de-footer-2')
    || is_active_sidebar('de-footer-3') || is_active_sidebar('de-footer-4')
) { ?>
    <footer>


        <div class="container">
            <div class="row">
                <div class="primero col-md-3 col-sm-12">
                    <?php if (is_active_sidebar('de-footer-1')) dynamic_sidebar('de-footer-1'); ?>
                </div>
                <div class="col-md-3 col-sm-4">
                    <?php if (is_active_sidebar('de-footer-2')) dynamic_sidebar('de-footer-2'); ?>
                </div>
                <div class="col-md-3 col-sm-4">
                    <?php if (is_active_sidebar('de-footer-3')) dynamic_sidebar('de-footer-3'); ?>
                </div>
                <div class="col-md-3 col-sm-4">
                    <?php if (is_active_sidebar('de-footer-4')) dynamic_sidebar('de-footer-4'); ?>
                </div>
            </div>
        </div>
    </footer>
    <!-- FOOTER / End -->
<?php } ?>
<!-- Copyright -->
<div class="copyright-wrapper">
    <div class="contenedor">
        <div class="row">
            <div class="col-md-12">
                <p>&copy; Servilisto.com 2017. Todos los derechos reservados.</p>
            </div>

        </div>
    </div>
</div>
<!-- Copyright / End -->
<?php
wp_footer(); ?>

</body>
</html>