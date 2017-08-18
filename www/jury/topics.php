<?php
/**
 * View the users
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

require('init.php');
$title = 'Topics';

$courseid =  $_GET['courseid'];
if ( empty($courseid) ) error ("No selected course.");
if (!is_numeric($courseid)) error ("Invalid course id");

$topics = $DB->q('SELECT t.*, c.coursename
                 FROM topic t, course c where t.courseid = c.courseid and t.courseid = ' . $courseid);

require(LIBWWWDIR . '/header.php');

echo "<h1>Topics</h1>\n\n";

if( $topics->count() == 0 ) {
	echo "<p class=\"nodata\">No topics defined</p>\n\n";
} else {
	echo "<table class=\"list sortable\">\n<thead>\n" .
	    "<tr><th scope=\"col\">topic name</th>" .
	    "<th scope=\"col\">course name</th>" .
	    "<th scope=\"col\">description</th>" .
	    "<th></th>" .
	    "</tr>\n</thead>\n<tbody>\n";

	while( $row = $topics->next() ) {
		$link = '<a href="topic.php?id='.urlencode($row['topicid']) . '">';
		echo "<tr class=\"\">".
		    "<td class=\"username\">" . $link .
		        specialchars($row['topicname'])."</a></td>".
		    "<td>" . $link .
		        specialchars($row['coursename'])."</a></td>".
		    "<td>" . $link .
		        specialchars($row['description'])."</a></td>";
		if ( IS_ADMIN ) {
			echo "<td class=\"editdel\">" .
			    editLink('topic', $row['topicid']) . "&nbsp;" .
			    delLink('topic','topicid',$row['topicid'],$row['topicname']) . "</td>";
		}
		echo "</tr>\n";
	}
	echo "</tbody>\n</table>\n\n";
}

if ( IS_ADMIN ) {
	echo "<p>" .addLink('topic') . "</p>\n";
}

require(LIBWWWDIR . '/footer.php');
