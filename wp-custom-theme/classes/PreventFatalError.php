<?php

namespace Start;

final class PreventFatalError {

  // 'acf', 'woocommerce', 'contact-form-7'
  private const REQUIRED_PLUGINS_TEXT_DOMAIN = [
    'acf',
  ];

  // 'acf', 'woocommerce', 'wpcf7'
  private const REQUIRED_PLUGINS_CLASS_NAME = [
    'acf',
  ];

  public function __construct()
  {
    add_filter( 'plugin_action_links', array( $this, 'admin_disable_deactivation_links_for_plugins' ), 10, 4 );
    add_action( 'template_redirect', array( $this, 'front_redirect_user_if_fatal_error' ) );
  }

  public function admin_disable_deactivation_links_for_plugins( $actions, $plugin_file, $plugin_data, $context )
  {
    if (
      in_array( $plugin_data[ 'TextDomain' ], self::REQUIRED_PLUGINS_TEXT_DOMAIN )
      &&
      isset( $actions[ 'deactivate' ] )
    ) {
      unset( $actions[ 'deactivate' ] );
    }

    return $actions;
  }

  public function front_redirect_user_if_fatal_error()
  {
    foreach ( self::REQUIRED_PLUGINS_CLASS_NAME as $class_name ) {
      if ( ! class_exists( $class_name ) ) {
        load_template( get_stylesheet_directory() . '/templates/prevent-fatal-error.php' );
        exit;
      }
    }
  }

  public function is_success(): bool
  {
    if ( ! $this->is_required_plugins_active() ) {
      return false;
    } else {
      return true;
    }
  }

  private function is_required_plugins_active(): bool
  {
    foreach ( self::REQUIRED_PLUGINS_CLASS_NAME as $class_name ) {
      if ( ! class_exists( $class_name ) ) {
        add_action( 'admin_notices', array( $this, 'admin_notice' ) );
        return false;
      }
    }
    return true;
  }

  public function admin_notice()
  {
    ?>
    <div class="notice notice-error">
      <p>
        <strong>Warning:</strong> The required plugins for this theme are deactivated.<br>
        It will break view and functionality of website. Please activate them.
      </p>
      <pre>
        <?php foreach ( self::REQUIRED_PLUGINS_TEXT_DOMAIN as $text_domain ) : ?>
          <strong><?php echo strtoupper( $text_domain ); ?></strong>
        <?php endforeach; ?>
      </pre>
    </div>
    <?php
  }

}
