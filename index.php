<?php
    get_header();
    $options = get_design_plus_option();

    if (is_category()) {
      $query_obj = get_queried_object();
      $current_cat_id = $query_obj->term_id;
      $term_meta = get_option( 'taxonomy_' . $current_cat_id, array() );
      $title = $query_obj->name;

    } elseif(is_tag()) {
      $query_obj = get_queried_object();
      $title = $query_obj->name;

    } elseif(is_author()) {
      $author_info = $wp_query->get_queried_object();
      $author_id = $author_info->ID;
      $title = get_userdata($author_id)->display_name;

    } elseif (is_search()) {
      if( !empty( get_search_query() ) ) {
        $title = sprintf( __( 'Search result for %s', 'tcd-w' ), get_search_query() );
      } else {
        $title = __( 'Search result', 'tcd-w' );
      }

    } else {
      $title = get_the_title( get_option( 'page_for_posts' ));
    }

?>
<div id="main_contents">

  <div id="main_col" class="archive_page">
    <div class="article_top">
      <h1 class="title rich_font"><?php echo $title ?></h1>
    </div>
    <div class="main_col_inner">

    <?php if ( have_posts() ) : ?>
      <!-- ★修正部分 -->
      <div class="blog_list<?php if(!array_key_exists("archive_blog_show_date", $options) || !$options['archive_blog_show_date']){ echo ' no_date'; };?>">

      <?php
          while ( have_posts() ) : the_post();
            if(has_post_thumbnail()) {
              $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'size3' );
            } elseif($options['no_image1']) {
              $image = wp_get_attachment_image_src( $options['no_image1'], 'full' );
            } else {
              $image = array();
              $image[0] = esc_url(get_bloginfo('template_url')) . "/img/common/no_image2.gif";
            }

            $category = wp_get_post_terms( $post->ID, 'category' , array( 'orderby' => 'term_order' ));
            if ( $category && ! is_wp_error($category) ) {
              foreach ( $category as $cat ) :
                $cat_name = $cat->name;
                $cat_id = $cat->term_id;
                $cat_url = get_term_link($cat_id,'category');
                break;
              endforeach;
            };
      ?>
        <article class="item">
          <h3 class="title rich_font"><a href="<?php the_permalink(); ?>" class="title_link"><span><?php the_title(); ?></span></a></h3>
          <div class="item_inner">
            <p class="meta">
              <span class="date"><time class="entry-date updated"><?php the_time('Y.m.d'); ?></time></span>
              <?php if ( !is_category() && $category && ! is_wp_error($category) ) { ?>
              <a class="category cat_id<?php echo esc_attr($cat_id); ?>" href="<?php echo esc_url($cat_url); ?>"><?php echo esc_html($cat_name); ?></a>
              <?php }; ?>
            </p>
            <a class="link animate_background clearfix" href="<?php the_permalink(); ?>">
              <div class="image_outer">
              <div class="image_wrap">
                <div class="image" style="background:url(<?php echo esc_attr($image[0]); ?>) no-repeat center center; background-size:cover;"></div>
              </div>
              </div>
              <div class="content">
                <div class="content_inner">
                  <p class="desc"><span><?php echo trim_excerpt(150); ?></span></p>
                </div>
              </div>
            </a>
          </div>
        </article>
      <?php endwhile; ?>
      </div><!-- END .blog_list -->
      <?php
          get_template_part('template-parts/navigation');
          else: // if no post
      ?>
      <p id="no_post"><?php _e('There is no registered post.', 'tcd-w');  ?></p>
      <?php endif; ?>
    </div><!-- END .main_col_inner -->
  </div><!-- END #main_col -->

  <?php
      // widget
      get_sidebar();
  ?>

</div><!-- END #main_contents -->
<?php get_footer(); ?>
