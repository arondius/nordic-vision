<div id="sidebar-reispagina" class="sidebar">
<?php

	echo '<h2>Reisdetails</h2>';
	echo '<strong>'. 'reiscode:' . '</strong>';
	echo '<p>'. get_post_meta($post->ID, 'reiscode_1', true). '</p>';
	echo '<strong>'. 'reisdatum' . '</strong>';
	echo '<p>'. get_post_meta($post->ID, 'reisdatum_1', true). '</p>';
	echo '<strong>'. 'inbegrepen' . '</strong>';
	echo '<ul>';
	echo '<li>'. get_post_meta($post->ID, 'inbegrepen_1', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'inbegrepen_2', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'inbegrepen_3', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'inbegrepen_4', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'inbegrepen_5', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'inbegrepen_6', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'inbegrepen_7', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'inbegrepen_8', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'inbegrepen_9', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'inbegrepen_10', true). '</li>';
	echo '</ul>';
	echo '<strong>'. 'niet inbegrepen' . '</strong>';
	echo '<ul>';
	echo '<li>'. get_post_meta($post->ID, 'niet_inbegrepen_1', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'niet_inbegrepen_2', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'niet_inbegrepen_3', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'niet_inbegrepen_4', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'niet_inbegrepen_5', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'niet_inbegrepen_6', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'niet_inbegrepen_7', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'niet_inbegrepen_8', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'niet_inbegrepen_9', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'niet_inbegrepen_10', true). '</li>';
	echo '</ul>';
	echo '<strong>'. 'prijs' . '</strong>';
	echo '<p>'. get_post_meta($post->ID, 'prijs_1', true). '</p>';
	echo '<strong>'. 'optioneel mogelijk' . '</strong>';
	echo '<ul>';
	echo '<li>'. get_post_meta($post->ID, 'optie_1', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'optie_2', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'optie_3', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'optie_4', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'optie_5', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'optie_6', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'optie_7', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'optie_8', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'optie_9', true). '</li>';
	echo '<li>'. get_post_meta($post->ID, 'optie_10', true). '</li>';
	echo '</ul>';
	echo '<strong>'. 'aantal deelnemers' . '</strong>';
	echo '<p>'. get_post_meta($post->ID, 'aantal_deelnemers', true). '</p>';
	echo '<strong>'. 'zwaarte reis' . '</strong>';
	echo '<p>'. get_post_meta($post->ID, 'zwaarte_reis', true). '</p>';
if( (get_post_meta($post->ID, "overig_1", true)) || (get_post_meta($post->ID, "overig_2", true)) || (get_post_meta($post->ID, "overig_3", true)) ) {
	echo '<strong>'. 'overige informatie' . '</strong>';
	echo '<p>'. get_post_meta($post->ID, 'overig_1', true). '</p>';
	echo '<p>'. get_post_meta($post->ID, 'overig_2', true). '</p>';
	echo '<p>'. get_post_meta($post->ID, 'overig_3', true). '</p>';
}
	echo '<strong>'. 'routebeschrijving' . '</strong>';
	echo '<p>'. get_post_meta($post->ID, 'routebeschrijving', true). '</p>';

	echo do_shortcode('[boekingsform]');
?>