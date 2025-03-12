<?php
/*
Plugin Name: Filmvanalledag Book List
Description: Displays a sortable table of posts with custom fields (boek_auteur, boek_achternaam, boek_titel)
Contains heavily tweaked css.
Version: 1.2
Author: Maarten (filmvanalledag.nl)

Verwijderd uit begin


*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Shortcode function
function custom_book_list_shortcode() {
    $args = array(
        'post_type'      => 'post', // Change if needed
        'posts_per_page' => 200, // Adjust as necessary
        'orderby'        => 'date',
        'order'          => 'DESC',
        'category_name'  => 'boeken' // Filter by category 'boeken'
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        $output = '<table class="custom-book-table" id="bookTable" style="border-style: none;">
                    <tbody>';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $auteur = get_post_meta(get_the_ID(), 'boek_auteur', true);
            $achternaam = get_post_meta(get_the_ID(), 'boek_achternaam', true);
            $titel = get_post_meta(get_the_ID(), 'boek_titel', true);
            
            $cover = get_post_meta(get_the_ID(), 'boek_cover', true);
            
            if (is_numeric($cover)) {
                $cover = wp_get_attachment_url($cover);
            }

            $cover_img = $cover ? '<img src="' . esc_url($cover) . '" style="max-width: 140px; height: auto; border-radius: 8px;" alt="Cover Image">' : '';
          
          
            $output .= '<tr>';
//          $output .= '<td>' . esc_html($achternaam) . '</td>';
			$output .= '<td rowspan="4" style="width:160px">' . $cover_img . '</td>';
            $output .= '<td><div style="font-size: 130%;font-weight:bold">' . esc_html($titel) . '</div></td></tr>';

            $output .= '<tr><td style="white-space: nowrap; font-size: 70%;">' . esc_html($auteur) . '</td></tr>';
            $output .= '<tr><td style="white-space: nowrap; font-size: 70%;"><a href="' . get_permalink() . '">' . get_the_title() . '</a></td></tr>';
            $output .= '<tr><td style="white-space: nowrap; font-size: 70%;">' . get_the_date('d-m-Y') . '</td>';
            $output .= '</tr>';
        }
        
        $output .= '</tbody></table>';
        wp_reset_postdata();
        

        
        return $output;
    } else {
        return '<p>No books found.</p>';
    }
}

// Register shortcode
add_shortcode('custom_book_list', 'custom_book_list_shortcode');

// Optional: Add basic CSS styling
function custom_book_list_styles() {
    echo '<style>
        .custom-book-table { width: 100%; border-collapse: collapse;}
        .custom-book-table th, .custom-book-table td { padding-bottom: 8px; border: 0px solid #ddd; text-align: left; vertical-align: top;}
        .custom-book-table th { cursor: pointer; background: #f4f4f4; }
        .custom-book-table img { display: block; max-width: 200px; height: auto; padding-bottom: 20px }
        
    </style>';
}
add_action('wp_head', 'custom_book_list_styles');
