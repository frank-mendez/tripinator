
<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

    <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
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

                        foreach($result as $data){
                            ?>
                        <div class="row" style="margin-top: 30px;border-bottom:solid 1px grey;">
                            <div class="row-title">
                                <?php echo "Trip: ".$data['trip']; ?>
                            </div>
                            <div class="row-title">
                                <?php echo "# of days/# of hours: ".$data['days']; ?>
                            </div>
                            <div class="row-image">
                                <p>
                                    <?php if(!empty($data['img_url'])) { ?>
                                    <img width="200" height="200" src="<?php echo $data['img_url']; ?>" alt="">
                                    <?php } else {  ?>
                                    <img width="200" height="200" src="http://localhost/eventlisting/wp-content/uploads/2017/12/blank.jpg" alt="">
                                    <?php } ?>
                                </p>
                                <?php echo $data['description']; ?>
                                <a href="https://www.adventurecentral.com/user/web/cart/wfRentalDetails.aspx?RUID=196&C=1&CLUID=22177c21-3e92-4945-a0a9-9a553f413e66">More Info</a>

                            </div>

                        </div>
                    <?php }  } else {
                        echo 'No matching results found!';
                    }
                } else {
                    echo 'No search result';
                } ?>
                <?php
                while ( have_posts() ) : the_post();

                    get_template_part( 'template-parts/page/content', 'page' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
                ?>

            </main><!-- #main -->
        </div><!-- #primary -->
    </div><!-- .wrap -->

<?php get_footer();

