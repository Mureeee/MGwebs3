<!-- Footer -->
<footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Sobre MGwebs</h3>
                <p>Tu pagina web <br>de las paginas Webs</p>
            </div>

            <div class="footer-section">
                <h3>Enlaces Útiles</h3>
                <ul class="footer-links">
                    <li><a href="<?php echo APP_URL; ?>">Inicio</a></li>
                    <li><a href="<?php echo APP_URL; ?>/segunda-mano">Segunda Mano</a></li>
                    <li><a href="<?php echo APP_URL; ?>/soporte">Soporte</a></li>
                    <li><a href="<?php echo APP_URL; ?>/contactanos">Contacto</a></li>
                    <?php if (!$isLoggedIn): ?>
                        <li><a href="<?php echo APP_URL; ?>/login">Iniciar Sesión</a></li>
                        <li><a href="<?php echo APP_URL; ?>/registrarse">Registrarse</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo APP_URL; ?>/perfil">Mi Perfil</a></li>
                        <li><a href="<?php echo APP_URL; ?>/carrito">Mi Carrito</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Síguenos</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h3>Contacto</h3>
                <ul class="footer-links">
                    <li><span>📞 +34 123 456 789</span></li>
                    <li><span>✉️ info@mgwebs.com</span></li>
                    <li><span>📍 Calle Diagonal 123, Barcelona</span></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p> MGwebs. Todos los derechos reservados.</p>
        </div>
    </footer>
<script src="<?php echo APP_URL; ?>/public/js/script.js"></script> 