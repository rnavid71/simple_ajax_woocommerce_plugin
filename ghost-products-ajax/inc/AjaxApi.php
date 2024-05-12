<?php


namespace inc;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
use WP_Query;

class AjaxApi
{
    public function import_form(){
        $this->import_styles();
        $this->import_html();
        $this->import_script();
    }

    protected function import_html(){
        echo '
          <div style="position: relative">
            <input type="text" class="ghost-product-name" id="ghost-search-input" placeholder="search for Products">
            <div id="ghost-products-container" style="display: none;"></div>
          </div>
          
        ';
    }

    protected function import_script(){
        wp_enqueue_script('jquery');
        wp_enqueue_script('ghost_script', PLUGIN_DIR_URI . 'assets/js/app.js');
        wp_localize_script('ghost_script', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }

    protected function import_styles(){
        wp_enqueue_style('ghost_styles', PLUGIN_DIR_URI . 'assets/css/app.css');
    }

    public function search(){
        // Check for the search term
        $search_term = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';
        // WP_Query arguments
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 8,
            's' => $search_term
        );
        // The Query
        $query = new WP_Query($args);
        // The Loop
        $this->serach_results($query);

        // Restore original Post Data
        wp_reset_postdata();

        // Always die in functions echoing AJAX content
        wp_die();
    }

    private function serach_results($query)
    {
        $html_response = '<div class="ghost-container">';
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $_product = wc_get_product( get_the_ID() );
//                var_dump($_product);
//                die();
                $html_response .= '
                <div class="ghost-product">
                    <div class="g-image"><a href="'.$_product->get_permalink().'"><img src="'.wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ) )[0].'"></a></div>
                    <div class="g-info">
                        <div class="g-title"><h3><a href="'.$_product->get_permalink().'">'.$_product->get_title().'</a></h3></div>
                        <div class="g-price">'.wc_price($_product->get_price()).'</div>
                        <div class="g-link"><a href="'.$_product->get_permalink().'">show product</a></div>
                    </div>
                </div>
                ';
            }
        } else {
            echo 'No products found';
        }
        $html_response .= '</div>';
        echo $html_response;
    }
}