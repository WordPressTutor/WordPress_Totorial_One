<?php

function search_option()
{
    $search_query = get_search_query();

?>
    <form role="search" method="get" id="searchform">
        <div>
            <label for="s">Search for:</label>
            <input type="text" value="<?php echo $search_query; ?>" name="s" id="s" />
            <input type="submit" id="searchsubmit" value="Search" />
        </div><br><br>
    </form>

    <?php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $posts_per_page = 5;
    $post_args = array(
        'post_type'      => 'custompost',
        'posts_per_page' => -1,
        's'              => $search_query,
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
    );
    $post_query = new WP_Query($post_args);
    if ($post_query->have_posts()) :
    ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Post Image</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($post_query->have_posts()) :
                    $post_query->the_post();
                ?>
                    <tr>
                        <td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
                        <td><?php the_excerpt(); ?></td>
                        <td><?php the_post_thumbnail('thumbnail');?></td>
                    </tr>
                <?php
                endwhile;
                ?>
            </tbody>
        </table>
<?php
        $total_pages = $post_query->max_num_pages;
        if ($total_pages > 1) {
            echo '<div class="pagination">';
            echo paginate_links(array(
                'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'format'    => '?paged=%#%',
                'current'   => max(1, get_query_var('paged')),
                'total'     => $total_pages,
                'prev_text' => __('« Prev'),
                'next_text' => __('Next »'),
            ));
            echo '</div>';
        }

        // Reset post data.
        wp_reset_postdata();

    else :

        echo __('No posts found', 'textdomain');
    endif;
}

add_action('get_search_form', 'search_option');


?>