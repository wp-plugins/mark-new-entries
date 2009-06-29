<?php
/*
Plugin Name:  Mark New Entries Widget
Plugin URI: http://www.vjcatkick.com/?page_id=10748
Description: Adds function for template marking new entries
Version: 0.0.2
Author: V.J.Catkick
Author URI: http://www.vjcatkick.com/
*/

/*
License: GPL
Compatibility: WordPress 2.6 with Widget-plugin.

Installation:
Place the widget_single_photo folder in your /wp-content/plugins/ directory
and activate through the administration panel, and then go to the widget panel and
drag it to where you would like to have it!
*/

/*  Copyright V.J.Catkick - http://www.vjcatkick.com/

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


/* Changelog
* Jun 27 2009 - v0.0.1
- Initial release
* Jun 29 2009 - v0.0.2
- svn version - function argument is no longer needed.
*/

function is_new_post( $arg ) {
	$entry_unix_timestamp = get_the_time( 'U' );

	$options = get_option('widget_mark_new_entries');
	$widget_mark_new_entries_day = $options['widget_mark_new_entries_day'];
	$widget_mark_new_entries_hour = $options['widget_mark_new_entries_hour'];

	$cur_time = time();
	$offset_d = 60 * 60 * 24 * $widget_mark_new_entries_day;
	$offset_h = 60 * 60 * $widget_mark_new_entries_hour;
	$offset_total = $offset_d + $offset_h;
	$offset_value = $cur_time - $offset_total;

	if( $offset_value < $entry_unix_timestamp ) return true;
	return false;
} /* is_new_post() */


function mark_new_entries_options_page() {
	$output = '';

	$options = $newoptions = get_option('widget_mark_new_entries');
	if ( $_POST["widget_mark_new_entries_submit"] ) {
		$newoptions['widget_mark_new_entries_day'] = (int)$_POST["widget_mark_new_entries_day"];
		$newoptions['widget_mark_new_entries_hour'] = (int)$_POST["widget_mark_new_entries_hour"];
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_mark_new_entries', $options);
	}

	$widget_mark_new_entries_day = $options['widget_mark_new_entries_day'];
	$widget_mark_new_entries_hour = $options['widget_mark_new_entries_hour'];

	if( !$widget_mark_new_entries_day ) $widget_mark_new_entries_day = 0;
	if( !$widget_mark_new_entries_hour ) $widget_mark_new_entries_hour = 0;

	$output .= '<h2>Mark New Entries</h2>';
	$output .= '<form action="" method="post" id="widget_mark_new_entries_form" style="margin: auto; width: 600px; ">';

	$output .= 'Offset: <br />';
	$output .= '<input style="width: 50px;" id="widget_mark_new_entries_day" name="widget_mark_new_entries_day" type="text"	value="'.$widget_mark_new_entries_day.'" /> day(s)<br />';

	$output .= '<input style="width: 50px;" id="widget_mark_new_entries_hour" name="widget_mark_new_entries_hour" type="text"	value="'.$widget_mark_new_entries_hour.'" /> hour(s)<br />';

	$output .= '<p class="submit"><input type="submit" name="widget_mark_new_entries_submit" value="'. 'Update options &raquo;' .'" /></p>';

$output.='Put following code to your template file:<br /><br />
&lt;?php if( is_new_post( ) ) : ?&gt;<br />
&nbsp;&nbsp;<span style="color: #888;">&lt;h2 class="new" &gt;[New]</span> <span style="font-size: 0.9em; color: #FF0000;" >... any html you want for new entries</span><br />
&lt;?php else : ?&gt;<br />
&nbsp;&nbsp;<span style="color: #888;">&lt;h2&gt;</span> <span style="font-size: 0.9em; color: #FF0000;" >... for not new entries</span><br />
&lt;?php endif; ?&gt;<br />';

	$output .= '</form>';

	echo $output;
} /* mark_new_entries_options_page() */

add_action('admin_menu', 'mark_new_entries_options');

function mark_new_entries_options() {
	add_options_page('Mark New Entries', 'Mark New Entries', 8, 'mark_new_entries_options', 'mark_new_entries_options_page');
} /* mark_new_entries_options() */

?>