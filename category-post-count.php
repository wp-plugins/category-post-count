<?php

/**
 * 
 * @link              http://www.karalamalar.net/category-post-count/
 * @since             0.1.2
 * @package           Category_Post_Count
 *
 * @wordpress-plugin
 * Plugin Name:       Category Post Count
 * Plugin URI:        http://wordpress.org/plugins/category-post-count/
 * Description:       With this plugin you can set the posts_per_page and posts_per_rss settings for individual categories.
 * Version:           0.1.2
 * Author:            Emre Erkan
 * Author URI:        http://www.karalamalar.net/category-post-count/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       category-post-count
 * Domain Path:       /languages
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Category_Post_Count {
  private $cat_key;
  private $counts;
  
  function __construct() {
    add_action( 'plugins_loaded', array( $this, 'init' ) );
    add_action( 'parse_query', array( $this, 'parse_query' ) );
  }
  
  function init() {
    load_plugin_textdomain( 'category-post-count', false, 'category-post-count/languages/' );
    if ( is_admin() ) {
      add_action( 'init', array( $this, 'admin_init' ) );
    }
  }

  function pre_get_posts( $query ) {
    if ( $query->is_main_query() ) {
      $query->set( 'posts_per_page', $this->counts[ $this->cat_key ][ 'post_count' ] );
    }
  }
  
  function parse_query( $query ) {
    if ( ! is_admin() && is_category() ) {
      $cat = get_queried_object();
      
      if ( $cat ) {
        $this->cat_key = 'cat_' . $cat->term_id;
        $this->counts = get_option( 'category-post-count' );
        
        if ( is_array( $this->counts ) && array_key_exists( $this->cat_key, $this->counts ) ) {
          if ( is_feed() && isset( $this->counts[ $this->cat_key ][ 'feed_count' ] ) ) {
            add_filter( 'pre_option_posts_per_rss', $this->counts[ $this->cat_key ][ 'feed_count' ] );
          } elseif ( isset( $this->counts[ $this->cat_key ][ 'post_count'] )  ) {
            add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
          }
        }
      }
    }
  }

  /* Admin functions */
  function add_fields_to_category_form( $taxonomy ) {
  ?>
  <div class="form-field">
    <label for="post_count"><?php _e( 'Post count', 'category-post-count' ); ?></label>
    <input name="post_count" id="post_count" type="text" value="" size="40" />
    <p><?php _e( 'Post count for this category to display', 'category-post-count' ); ?></p>
  </div>
  <div class="form-field">
    <label for="feed_count"><?php _e( 'Feed count', 'category-post-count' ); ?></label>
    <input name="feed_count" id="feed_count" type="text" value="" size="40" />
    <p><?php _e( 'Post count for this category to display in RSS feed', 'category-post-count' ); ?></p>
  </div>
  <?php
  }
   
  function edit_fields_of_category_form( $tag, $taxonomy ) {
      $this->cat_key = 'cat_' . $tag->term_id;
      $this->counts = get_option( 'category-post-count' );
  ?>
  <tr class="form-field">
    <th scope="row"><label for="post_count"><?php _e( 'Post count', 'category-post-count' ); ?></label></th>
    <td>
      <input type="text" name="post_count" id="post_count" value="<?php echo esc_attr( $this->counts[ $this->cat_key ][ 'post_count'] ) ? esc_attr( $this->counts[ $this->cat_key ][ 'post_count'] ) : ''; ?>" size="40" />
      <p class="description"><?php _e( 'Post count for this category to display', 'category-post-count' ); ?></p>
    </td>
  </tr>
  <tr class="form-field">
    <th scope="row"><label for="feed_count"><?php _e( 'Feed count', 'category-post-count' ); ?></label></th>
    <td>
      <input type="text" name="feed_count" id="feed_count" value="<?php echo esc_attr( $this->counts[ $this->cat_key ][ 'feed_count' ] ) ? esc_attr( $this->counts[ $this->cat_key ][ 'feed_count' ] ) : ''; ?>" size="40" />
      <p class="description"><?php _e( 'Post count for this category to display in RSS feed', 'category-post-count' ); ?></p>
    </td>
  </tr>
  <?php
  }

  function category_form_save( $term_id, $tt_id ) {
    if ( isset( $_POST[ 'post_count' ] ) || isset( $_POST[ 'feed_count' ] ) ) {
      $this->cat_key = 'cat_' . $term_id;
      $this->counts = get_option( 'category-post-count' );
      if ( isset( $_POST[ 'post_count' ] ) ) {
        $this->counts[ $this->cat_key ][ 'post_count' ] = $_POST[ 'post_count' ];
      }
      if ( isset( $_POST[ 'feed_count' ] ) ) {
        $this->counts[ $this->cat_key ][ 'feed_count' ] = $_POST[ 'feed_count' ];
      }
      update_option( 'category-post-count', $this->counts );
    }
  }

  function add_column_header( $columns ) {
    $columns['post_feed_count'] = __( 'Post/Feed Count', 'category-post-count' );
    return $columns;
  }

  function display_values( $deprecated, $column_name, $term_id ) {
    if ($column_name == 'post_feed_count') {
      $post_count = get_option( 'posts_per_page' );
      $feed_count = get_option( 'posts_per_rss' );
      $this->cat_key = 'cat_' . $term_id;
      $this->counts = get_option( 'category-post-count' );
      if( is_array( $this->counts ) && array_key_exists( $this->cat_key, $this->counts ) ) {
        if( array_key_exists( 'post_count', $this->counts[ $this->cat_key ] ) && !empty( $this->counts[ $this->cat_key ][ 'post_count' ] ) ) {
          $post_count = $this->counts[ $this->cat_key ][ 'post_count' ];
        }
        if( array_key_exists( 'feed_count', $this->counts[ $this->cat_key ] ) && !empty( $this->counts[ $this->cat_key ][ 'feed_count' ] ) ) {
          $feed_count = $this->counts[ $this->cat_key ][ 'feed_count' ];
        }
      }
      printf( '%s / %s', $post_count, $feed_count );
    }
  }

  function admin_init() {
    add_action( 'category_add_form_fields', array( $this, 'add_fields_to_category_form' ), 10 );
    add_action( 'category_edit_form_fields', array( $this, 'edit_fields_of_category_form' ), 10, 2 );
    add_action( 'created_category', array( $this, 'category_form_save' ), 10, 2 ); 
    add_action( 'edited_category', array( $this, 'category_form_save' ), 10, 2 );
    add_action( 'manage_edit-category_columns', array( $this, 'add_column_header' ), 10, 2 );
    add_filter( 'manage_category_custom_column', array( $this, 'display_values' ), 10, 3 );
  }

}
$category_post_count = new Category_Post_Count();
?>