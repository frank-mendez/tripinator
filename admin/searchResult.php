<?php
/*
Template Name: Tripinator Search
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
                            AND adversity LIKE '%".$_POST['adversity']."%'";

                                    $result = $wpdb->get_results($sql_search, 'ARRAY_A');
                                    if( !empty($result) ){

                                        foreach($result as $data){
                                            ?>
                                            <div class="row" style="margin-top: 30px;padding-bottom: 30px;">
                                                <div class="row-title">
                                                    <h3 style="font-size:22px;">
                                                        <?php echo "Trip: ".$data['trip']; ?>
                                                    </h3>
                                                </div>
                                                <div class="row-title">
                                                    <?php echo "Number of days and hours: ".$data['days']; ?>
                                                </div>
                                                <div style="margin-top:20px; margin-bottom:30px;">
                                                    <p>
                                                        <?php if(!empty($data['img_url'])) { ?>
                                                            <img style="width: 60%;" src="<?php echo $data['img_url']; ?>" alt="">
                                                        <?php } else {  ?>
                                                            <img style="width: 60%;" height="200" src="http://localhost/eventlisting/wp-content/uploads/2017/12/blank.jpg" alt="">
                                                        <?php } ?>
                                                    </p>
                                                </div>
                                                <div style="padding-bottom: 20px;">
                                                    <?php echo preg_replace('/\\\\/', '',$data['description']); ?>
                                                </div>
                                                <div style="height: 40px;margin-top: 30px;">
                                                    <!-- <a class="button media-button"
                                                       style="padding: 20px;
                                                                background: #2ea3f2;
                                                                color: white;
                                                                margin-bottom: 20px;
                                                                border-radius: 3px;"
                                                       href="<?php echo $data['external_url']; ?>">
                                                        More Info
                                                    </a> -->
                                                    <a class="button media-button"
                                                       style="padding: 20px;
                                                                background: #2ea3f2;
                                                                color: white;
                                                                margin-bottom: 20px;
                                                                border-radius: 3px;"
                                                       href="more-info?link=<?php echo trim($data['external_url']); ?>">
                                                        More Info
                                                    </a>
                                                </div>

                                            </div>
                                            <hr style="border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3);">
                                        <?php }  } else {
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