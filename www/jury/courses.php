<?php
/**
 * View the users
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

require('init.php');
$title = 'Courses';

$courses = $DB->q('SELECT c.*
                 FROM bg_course c');

require(LIBWWWDIR . '/header.php');

echo "<h1>Courses</h1>\n\n";

if( $courses->count() == 0 ) {
	echo "<p class=\"nodata\">No courses defined</p>\n\n";
} else {
	echo "<table class=\"list sortable\">\n<thead>\n" .
	    "<tr><th scope=\"col\">course name</th>" .
	    "<th scope=\"col\">contest id</th>" .
	    "<th scope=\"col\">start date</th>" .
	    "<th scope=\"col\">end date</th>" .
	    "<th scope=\"col\">type</th>" .
	    "<th></th>" .
	    "</tr>\n</thead>\n<tbody>\n";

	while( $row = $courses->next() ) {

		// $status = 0;
		// if ( isset($row['last_login']) ) $status = 1;

		$link = '<a href="course.php?id='.urlencode($row['courseid']) . '">';
		echo "<tr class=\"\">".
		    "<td class=\"username\">" . $link .
		        specialchars($row['coursename'])."</a></td>".
		    "<td>" . $link .
		        specialchars($row['contestid'])."</a></td>".
		    "<td>" . $link .
		        specialchars($row['starttime'])."</a></td>".
		    "<td>" . $link .
		        specialchars($row['endtime'])."</a></td>";
		    "<td>" . $link .
		        specialchars($row['catid'])."</a></td>";
		if ( IS_ADMIN ) {
			echo "<td class=\"editdel\">" .
			    editLink('course', $row['courseid']) . "&nbsp;" .
			    delLink('course','courseid',$row['courseid'],$row['coursename']) . "</td>";
		}
		echo "</tr>\n";
	}
	echo "</tbody>\n</table>\n\n";
}

if ( IS_ADMIN ) {
	echo "<p>" .addLink('course') . "</p>\n";
}

require(LIBWWWDIR . '/footer.php');
