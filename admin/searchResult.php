<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 21/12/2017
 * Time: 11:00 AM
 */
get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() ); ?>

    <div id="main-content">

        <?php if ( ! $is_page_builder_used ) : ?>

        <div class="container">
            <div id="content-area" class="clearfix">
                <div id="left-area">

                    <?php endif; ?>

                    <?php while ( have_posts() ) : the_post(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                            <?php if ( ! $is_page_builder_used ) : ?>

                                <h1 class="main_title"><?php the_title(); ?></h1>
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

                            <div class="entry-content">
                                <?php
                                if( isset($_REQUEST['submit_tripinator_form']) == 'submit' ) {
                                    global $wpdb;
                                    $tripinatorDB = $wpdb->prefix . "tripinator";

                                    $sql_search = "SELECT * FROM {$tripinatorDB} 
                            WHERE days LIKE '%".$_POST['days']."%'
                            AND canoe_experience LIKE '%".$_POST['canoe']."%'
                            AND kayak_experience LIKE '%".$_POST['kayak']."%'
                            AND adversity LIKE '%".$_POST['adversity']."%'";

                                    $result = $wpdb->get_results($sql_search, 'ARRAY_A');
                                    if( !empty($result) ){
                                        ?>
                                        <table>
                                            <thead>
                                            <tr>
                                                <td>Trip</td>
                                                <td># of Days/hours</td>
                                                <td>Type</td>
                                                <td>Canoe Experience</td>
                                                <td>Kayak Experience</td>
                                                <td>handle adversity</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach($result as $data){ ?>
                                                <tr>
                                                    <td><?php echo $data['trip']; ?></td>
                                                    <td><?php echo $data['days']; ?></td>
                                                    <td><?php echo $data['type']; ?></td>
                                                    <td><?php echo $data['canoe_experience']; ?></td>
                                                    <td><?php echo $data['kayak_experience']; ?></td>
                                                    <td><?php echo $data['adversity']; ?></td>
                                                </tr>
                                            <?php }
                                            ?>
                                            </tbody>
                                        </table>
                                    <?php } else {
                                        echo 'No matching results found!';
                                    }
                                } else {
                                    echo 'No search result';
                                }

                                the_content();

                                if ( ! $is_page_builder_used )
                                    wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
                                ?>
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