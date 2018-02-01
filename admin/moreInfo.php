<?php
/*
Template Name: More Info
*/

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>
<style type="text/css">
    /*** Take out the divider line between content and sidebar ***/
#main-content .container:before {background: none;}
#main-content{
        min-height: 1100px;
}
 
/*** Hide Sidebar ***/
#sidebar {display:none;}
 
/*** Expand the content area to fullwidth ***/
@media (min-width: 981px){
#left-area {
    width: 100%;
    padding: 23px 0px 0px !important;
    float: none !important;
}
}


</style>

<div id="main-content">

<?php if ( ! $is_page_builder_used ) : ?>

    <div class="container">
        <div id="content-area" class="clearfix">
            <div id="left-area">

<?php endif; ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <?php if ( ! $is_page_builder_used ) : ?>

                    <h1 class="entry-title main_title"><?php the_title(); ?></h1>
                <?php
                    $thumb = '';

                    $width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

                    $height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
                    $classtext = 'et_featured_image';
                    $titletext = get_the_title();
                    $thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
                    $thumb = $thumbnail["thumb"];

                    if ( 'on' === et_get_option( 'divi_page_thumbnails', 'false' ) && '' !== $thumb )
                        print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
                ?>

                <?php endif; ?>

                    <div class="entry-content" style="min-height: 800px;">
                    <?php
                        the_content();

                        if ( ! $is_page_builder_used )
                            wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
                        if (isset($_GET['link'])) {
                            $link = $_GET['link'];
                        } else {
                            $link = '';
                        }
                    ?>
                    <iframe style="width:100%;height:100%;position: absolute;" src="<?php echo $link; ?>"></iframe>
                    </div> <!-- .entry-content -->

                <?php
                    if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
                ?>

                </article> <!-- .et_pb_post -->

            <?php endwhile; ?>

<?php if ( ! $is_page_builder_used ) : ?>

            </div> <!-- #left-area -->

            <?php get_sidebar(); ?>
        </div> <!-- #content-area -->
    </div> <!-- .container -->

<?php endif; ?>

</div> <!-- #main-content -->

<?php get_footer(); ?>