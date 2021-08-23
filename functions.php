<?php
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles() {
  // 親テーマのスタイル
  wp_enqueue_style(
    'parent-style',
    get_template_directory_uri() . '/style.css',
  );
  // 子テーマのスタイル
  wp_enqueue_style(
    'child-style',
    get_stylesheet_directory_uri() . '/style.css',
  );
}


// bodyタグにclassを追加（★上書き） --------------------------------------------------------------------------------
function child_tcd_body_classes($classes) {
  global $wp_query, $post;
  $options = get_design_plus_option();

  if (is_front_page()) {
    $display_header_content = '';
    if (!is_mobile() && $options['show_index_slider']) {
      $display_header_content = 'show';
    } elseif (is_mobile() && array_key_exists('mobile_show_index_slider', $options)) { //★修正部分
      if ($options['mobile_show_index_slider'] != 'type3'){ //★修正部分
        $display_header_content = 'show'; //★修正部分
      } //★修正部分
    }
    if ($display_header_content != 'show') {
      $classes[] = 'no_index_header_content';
    }
  };
  if (is_page() && get_post_meta($post->ID, 'page_hide_header', true)) {
    $classes[] = 'hide_header';
  };
  if (is_page() && get_post_meta($post->ID, 'page_hide_global_menu', true)) {
    $classes[] = 'hide_global_menu';
  };
  if (is_page() && get_post_meta($post->ID, 'hide_page_header_type2', true)) {
    $classes[] = 'hide_header_title';
  };
  if (is_404() && $options['hide_header_404']) {
    $classes[] = 'hide_header';
  };
  if (is_404() && $options['hide_footer_404']) {
    $classes[] = 'hide_footer';
  };
  if (is_archive()) {
    global $wp_query;
    if ($wp_query->max_num_pages == 1) {
      $classes[] = 'no_page_nav';
    }
  }
  if (wp_is_mobile()) {
    $classes[] = 'mobile_device';
  };
  if (is_page() && !is_front_page()) {
    $classes[] = 'sub_page';
  };

  return array_unique($classes);
};
add_filter('body_class', 'tcd_body_classes');


// テーマのfunctions.phpが読み込まれた後で関数を再定義 --------------------------------------------------------------------------------
function after_parent_theme_func() {
  remove_filter('body_class', 'tcd_body_classes');
  add_filter('body_class', 'child_tcd_body_classes');
}
add_action('after_setup_theme', 'after_parent_theme_func', 20);
