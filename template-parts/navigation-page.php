<nav id="site-navigation" class="main-navigation" role="navigation">
  <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'nomades-projets' ); ?></button>
  <div class="row">
    <div class="col col-sm-3">

    </div>
    <div class="col col-sm-9">
        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
    </div>
  </div>
</nav><!-- #site-navigation -->
